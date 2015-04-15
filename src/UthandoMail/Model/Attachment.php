<?php
namespace UthandoMail\Model;

class Attachment
{
    /**
     * @var string
     */
    private $binary;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @return string
     */
    public function getBinary()
    {
        return $this->binary;
    }
    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * @param string $binary
     * @return Attachment
     */
    public function setBinary($binary)
    {
        $this->binary = $binary;
        return $this;
    }
    /**
     * @param string $fileName
     * @return Attachment
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
}