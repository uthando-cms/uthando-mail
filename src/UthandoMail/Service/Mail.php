<?php
namespace UthandoMail\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Stdlib\Exception\DomainException;
use Zend\View\Model\ViewModel;
use UthandoCommon\Stdlib\OptionsTrait;
use Zend\Mail\Transport\TransportInterface;
use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\View\Renderer\RendererInterface;
use Zend\Mail\Address;
use Zend\Mime\Mime;
use UthandoMail\Model\Attachment;

class Mail
{
    use OptionsTrait;
    
    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $view;
    
    /**
     * @var array
     */
    protected $transport = [];
    
    /**
     * @var \Zend\View\Model\ViewModel
     */
    protected $layout;
    
    protected $attachments = [];
    
    public function __construct(RendererInterface $view, $options)
    {
        $this->setOptions($options);
        $this->setView($view);
        
        $this->setLayout();
    }
    
    public function setLayout($layout = null)
    {
    	if (null !== $layout && !is_string($layout) && !($layout instanceof ViewModel)) {
    		throw new InvalidArgumentException(
				'Invalid value supplied for setLayout.'.
				'Expected null, string, or Zend\View\Model\ViewModel.'
    		);
    	}
    	
    	if (null === $layout && $this->hasOption('layout')) {
    		return;
    	}
    	
    	if (null === $layout) {
    		$layout = (string) $this->getOption('layout');
    	}
    	
    	if (is_string($layout)) {
    		$template = $layout;
    		$layout = new ViewModel;
    		$layout->setTemplate($template);
    	}
    	
    	$this->layout = $layout;
    	
    	return $this;
    }
    
    public function getLayout()
    {
    	return $this->layout;
    }
    
    public function getMessageBody($body, $mimeType = null)
    {
        // Make sure we have a string.
        if ($body instanceof ViewModel) {
        	$body = $this->getView()->render($body);
        	$detectedMimeType = Mime::TYPE_HTML;
        } elseif (null === $body) {
        	$detectedMimeType = Mime::TYPE_TEXT;
        	$body = '';
        }
        
        if (null !== ($layout = $this->getLayout())) {
        	$layout->setVariables(array(
        		'content' => $body,
        	));
        	
        	$detectedMimeType = Mime::TYPE_HTML;
        	$body = $this->parseTemplate($layout);
        }
        
        if (null === $mimeType && !isset($detectedMimeType)) {
        	$mimeType = preg_match("/<[^<]+>/", $body) ? Mime::TYPE_HTML : Mime::TYPE_TEXT;
        } elseif (null === $mimeType) {
        	$mimeType = $detectedMimeType;
        }
        
        $htmlPart = new MimePart($body);
        $htmlPart->type = $mimeType;
        $htmlPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        
        if (null !== ($charset = $this->getOption('charset'))) {
        	$htmlPart->charset = $charset;
        }
        
        if ($mimeType === Mime::TYPE_HTML) {
        	$text = $this->renderTextBody($body);
        	$textPart = new MimePart($text);
        	$textPart->type = Mime::TYPE_TEXT;
            $textPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        	
        	if ($this->getOption('charset')) {
        		$textPart->charset = $this->getOption('charset');
        	}
        }

        $bodyMessage = new MimeMessage();
        
        if (count($this->attachments) > 0) {
            $content = new MimeMessage();
            $content->addPart($textPart);
            $content->addPart($htmlPart);

            $contentPart = new MimePart($content->generateMessage());
            $contentPart->type = 'multipart/alternative; boundary="' . $content->getMime()->boundary() . '"';

            $bodyMessage->addPart($contentPart);
            $messageType = Mime::MULTIPART_RELATED;

            foreach ($this->attachments as $attachment) {
                $bodyMessage->addPart($attachment);
            }
        } else {
            if (isset($textPart)) {
                $bodyMessage->addPart($textPart);
            }

            if (isset($htmlPart)) {
                $bodyMessage->addPart($htmlPart);
            }

            $messageType = Mime::MULTIPART_ALTERNATIVE;
        }


        
        return ['body' => $bodyMessage, 'type' => $messageType];
    }
    
    public function mimeByExtension($filename)
    {
        if (is_readable($filename) ) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'gif':
                    $type = 'image/gif';
                    break;
                case 'jpg':
                case 'jpeg':
                    $type = 'image/jpg';
                    break;
                case 'png':
                    $type = 'image/png';
                    break;
                default:
                    $type = 'application/octet-stream';
            }
        }
    
        return $type;
    }
    
    public function compose($body = null, $mimeType = null)
    {
    	// Supported types are null, ViewModel and string.
    	if (null !== $body && !is_string($body) && !($body instanceof ViewModel)) {
    		throw new InvalidArgumentException(
    			'Invalid value supplied. Expected null, string or instance of Zend\View\Model\ViewModel.'
    		);
    	}
    	
    	$bodyParts = $this->getMessageBody($body, $mimeType);
    	$message = new Message;
    	$message->setBody($bodyParts['body']);
        $message->getHeaders()->get('content-type')->setType($bodyParts['type']);
        $message->setEncoding('UTF-8');
    	
    	return $message;
    }
    
    public function send(Message $message, $transport = null)
    {
		if (!$message->getSender()) {
			$sender       = ($transport) ? $transport : 'default';
			$emailAddress = $this->getOption('addressList')[$sender];

			$message->setSender($emailAddress['address'], $emailAddress['name']);
		}
        
        return $this->getMailTransport($transport)
            ->send($message);
    }
    
    public function parseTemplate($stringOrView)
    {
        if ($stringOrView instanceof ViewModel) {
            $stringOrView = $this->getView()->render($stringOrView);
        }
        
        // find inline images.
        $xml = new \DOMDocument();
        $xml->loadHTML($stringOrView);
        
        $images = $xml->getElementsByTagName('img');
        
        foreach ($images as $image) {
            $file = $image->getAttribute('src');
            
            $binary = file_get_contents($file);
            $mime = $this->mimeByExtension($file);

            $fileName = pathinfo($file, PATHINFO_BASENAME);

            $attachment = new MimePart($binary);
            $attachment->setType($mime);
            $attachment->setDisposition(Mime::DISPOSITION_ATTACHMENT);
            $attachment->setEncoding(Mime::ENCODING_BASE64);

            $attachment->setFileName($fileName);
            $attachment->setId('cid_' . md5($fileName));
            
            $stringOrView = str_replace($file, 'cid:' . $attachment->getId(), $stringOrView);

            $this->attachments[] = $attachment;
        }
        
        return $stringOrView;
    }
    
    /*public function addAttachment(Attachment $file, $mimeType)
    {
        $attachment = new MimePart($file->getBinary());
        $attachment->type = $mimeType;
        $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = Mime::ENCODING_BASE64;
        
        if ($file->getFileName() !== null) {
            $attachment->filename = $file->getFileName();
            $attachment->id = $file->getFileName();
        }
        
        $this->attachments[] = $attachment;
        
    }*/
    
    public function renderTextBody($body)
    {
    	$body = html_entity_decode(
    		trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s', '', $body))), ENT_QUOTES
    	);
    	 
    	if (empty($body)) {
    		$body = 'To view this email, open it an email client that supports HTML.';
    	}
    	 
    	return $body;
    }
    
    public function createAddress($email, $name = null)
    {
    	return new Address($email, $name);
    }
    
    public function getMailTransport($config = null)
    {
        $config = ($config) ? (string) $config : 'default';
        
        if (array_key_exists($config, $this->transport)) {
        	return $this->transport[$config];
        }
        
        $transportConfig  = $this->getOption('mailTransport')[$config];
        $class            = $transportConfig['class'];
        $options          = $transportConfig['options'];
        
        switch ($class) {
        	case 'Zend\Mail\Transport\Sendmail':
        	case 'Sendmail':
        	case 'sendmail';
        	   $transport = new Transport\Sendmail();
        	   break;
        	case 'Zend\Mail\Transport\Smtp';
        	case 'Smtp';
        	case 'smtp';
        	   $options = new Transport\SmtpOptions($options);
        	   $transport = new Transport\Smtp($options);
        	   break;
        	case 'Zend\Mail\Transport\File';
        	case 'File';
        	case 'file';
        	   $options = new Transport\FileOptions($options);
        	   $transport = new Transport\File($options);
        	   break;
        	default:
        		throw new DomainException(sprintf(
                    'Unknown mail transport type provided ("%s")',
                    $class
        		));
        }
        
        $this->transport[$config] = $transport;
        
        return $this->transport[$config];
    }
    
    public function setTransport(TransportInterface $transport, $config = 'default')
    {
        $this->transport[$config] = $transport;
    }
    
	/**
	 * @return \Zend\View\Renderer\RendererInterface $view
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @param \Zend\View\Renderer\RendererInterface $view
	 * @return $this
	 */
	public function setView($view)
	{
		$this->view = $view;
		return $this;
	}
}
