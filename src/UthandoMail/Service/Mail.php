<?php
namespace UthandoMail\Service;

use Zend\Mail\Transport;
use Zend\Mime\Part as MimePart;
use Zend\Stdlib\Exception\DomainException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Mail implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    protected $config;
    
    public function send($data)
    {
        $to         = $data['recipient'];
        $from       = $data['from'];
        $subject    = $data['subject'];
        $bodyText   = $data['bodyText'];
        $bodyHtml   = $data['bodyHtml'];
        
        $message = $this->getMailMessage()
            ->setSubject($subject);
        
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
        
        if ($bodyText) {
            $text = new MimePart($bodyText);
            $text->type = "text/plain";
        }
        
        if ($bodyHtml) {
            $html = new MimePart($bodyHtml);
            $html->type = "text/html";
        }
        
        $message->setParts(array($text, $html));
        
        return $this->getMailTransport()
            ->send($message);
    }
    
    public function getMailMessage()
    {
        $class = $this->config->getMessageClass();
        $message = new $class();
        
        return $message;
    }
    
    public function getMailTransport()
    {
        $config  = $this->config->getMailTransport();
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
}
