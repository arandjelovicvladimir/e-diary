<?php

include_once 'vendor/rospdf/pdf-php/src/Cezpdf.php';

class Creport extends Cezpdf
{
    public function __construct($p, $o)
    {
        parent::__construct($p, $o, 'none', array());
        $this->isUnicode = true;
        $this->allowedTags .= '|uline';
        // always embed the font for the time being
        //$this->embedFont = false;
    }
}