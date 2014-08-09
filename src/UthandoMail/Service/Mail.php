<?php
namespace UthandoMail\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Stdlib\Exception\DomainException;
use Zend\View\Model\ViewModel;
use UthandoCommon\Stdlib\OptionsTrait;

class Mail
{
    use OptionsTrait;
    
    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $view;
    
    public function send($data)
    {
        $to         = $data['recipient'];
        $from       = $data['sender'];
        $subject    = $data['subject'];
        $bodyText   = $this->parseTemplate($data['bodyText']);
        $bodyHtml   = $this->parseTemplate($data['bodyHtml']);
        
        $message = new Message();
        
        $message->setSubject($subject);
        
        if (is_array($to)) {
        	$message->setTo($to['address'], $to['name']);
        } else {
        	$message->setTo($to);
        }
        
        if (is_array($from)) {
        	$message->setFrom($from['address'], $from['name']);
        } else {
        	$message->setFrom($from);
        }
        
        if ($bodyHtml) {
            $body = new MimeMessage();
            
            $parts = [];
            
            $html = new MimePart($bodyHtml);
            $html->type = "text/html";
            $parts[] = $html;
            
            if ($bodyText) {
            	$text = new MimePart($bodyText);
            	$text->type = "text/plain";
            	$parts[] = $text;
            }
            
            $body->setParts($parts);
            $message->setBody($body);
        } else {
            $message->setBody($bodyText);
        }
        
        return $this->getMailTransport()
            ->send($message);
    }
    
    public function parseTemplate($stringOrView)
    {
        if ($stringOrView instanceof ViewModel) {
            $stringOrView = $this->getView()->render($stringOrView);
        }
        
        return $stringOrView;
    }
    
    public function getMailTransport()
    {
        $config  = $this->getOption('mailTransport');
        $class   = $config['class'];
        $options = $config['options'];
        
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
        
        return $transport;
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
	 */
	public function setView($view)
	{
		$this->view = $view;
		return $this;
	}
}
