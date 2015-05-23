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
     * @var \
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
        	$detectedMimeType = 'text/html';
        } elseif (null === $body) {
        	$detectedMimeType = 'text/plain';
        	$body = '';
        }
        
        if (null !== ($layout = $this->getLayout())) {
        	$layout->setVariables(array(
        		'content' => $body,
        	));
        	
        	$detectedMimeType = 'text/html';
        	$body = $this->parseTemplate($layout);
        }
        
        if (null === $mimeType && !isset($detectedMimeType)) {
        	$mimeType = preg_match("/<[^<]+>/", $body) ? 'text/html' : 'text/plain';
        } elseif (null === $mimeType) {
        	$mimeType = $detectedMimeType;
        }
        
        $bodyMessage = new MimeMessage();
        
        //$multiPartContentMessage = new MimeMessage();
        
        $mimePart = new MimePart($body);
        $mimePart->type = $mimeType;
        
        if (null !== ($charset = $this->getOption('charset'))) {
        	$mimePart->charset = $charset;
        }
        
        if ($this->getOption('generateAlternativeBody') && $mimeType === 'text/html') {
        	$generatedBody = $this->renderTextBody($body);
        	$altPart = new MimePart($generatedBody);
        	$altPart->type = 'text/plain';
        	
        	if ($this->getOption('charset')) {
        		$altPart->charset = $this->getOption('charset');
        	}
        	
        	$bodyMessage->addPart($altPart);
        }
        
        $bodyMessage->addPart($mimePart);
        
        //$multiPartContentMimePart = new MimePart($multiPartContentMessage->generateMessage());
        
        //$multiPartContentMimePart->type = 'multipart/alternative;' . PHP_EOL . ' boundary="' .
            //$multiPartContentMessage->getMime()->boundary() . '"';
        
       // $bodyMessage->addPart($multiPartContentMimePart);
        
        //foreach ($this->attachments as $attachment) {
            //$bodyMessage->addPart($attachment);
        //}
        
        return $bodyMessage;
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
    	
    	$body = $this->getMessageBody($body, $mimeType);
    	$message = new Message;
    	$message->setBody($body);
    	
    	if ($this->getOption('generateAlternativeBody') && count($body->getParts()) > 1) {
    		$message->getHeaders()->get('content-type')->setType('multipart/alternative');
    	}
    	
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
        
        $imgs = $xml->getElementsByTagName('img');
        
        foreach ($imgs as $img) {
            $file = $img->getAttribute('src');
            
            $base64 = base64_encode(file_get_contents($file));
            $mime = $this->mimeByExtension($file);
            
            $stringOrView = str_replace($file, 'data:' . $mime . ';base64,' . $base64, $stringOrView);
        }
        
        return $stringOrView;
    }
    
    public function addAttachment(Attachment $file, $mimeType)
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
        
    }
    
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
