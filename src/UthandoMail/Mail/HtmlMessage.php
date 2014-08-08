<?php
namespace UthandoMail\Mail;

use Zend\Mail\Message;
use Zend\Mime\Mime;

class HtmlMessage extends Message
{
    public function buildHtml()
    {
        // Important, without this line the example don't work!
        // The images will be attached to the email but these will be not
        // showed inline
        $this->setType(Mime::MULTIPART_RELATED);
    
        $matches = array();
    
        preg_match_all(
            "#file://([^'\"]+)#i",
            $this->getBodyHtml(true),
            $matches
        );
        $matches = array_unique($matches[1]);
        
        if (count($matches ) > 0) {
            foreach ($matches as $key => $filename) {
                if (is_readable($filename)) {
                    $at = $this->createAttachment(
                        file_get_contents($filename)
                    );
                    
                    $at->type = $this->mimeByExtension($filename);
                    $at->disposition = Mime::DISPOSITION_INLINE;
                    $at->encoding = Mime::ENCODING_BASE64;
                    $at->id = 'cid_' . md5_file($filename);
                    $this->setBodyHtml(
                        str_replace(
                            'file://' . $filename,
                            'cid:' . $at->id,
                            $this->getBodyHtml(true)
                        ),
                        'UTF-8',
                        Mime::ENCODING_8BIT
                    );
                }
            }
        }
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
}
