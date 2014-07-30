<?php

namespace Acme\UrlshortenerBundle\Service;

class UrlshortenerService
{
    private $_chars;

    function __construct($chars)
    {
        $this->_chars = $chars;
    }

    public function generateCode($id)
    {
        $id = intval($id);
        if ($id < 1) {
            throw new \Exception("ID isn't int");
        }

        $length = strlen($this->_chars);

        $code = "";
        while ($id > $length - 1) {
            $code = $this->_chars[(int) fmod($id, $length)] . $code;
            $id = floor($id / $length);
        }

        $code = $this->_chars[(int) $id] . $code;

        return $code;
    }

    public function decode($string)
    {
        $length = strlen($this->_chars);
        $size = strlen($string) - 1;
        $string = str_split($string);
        $out = strpos($this->_chars, array_pop($string));

        foreach ($string as $i => $char) {
            $out += strpos($this->_chars, $char) * pow($length, $size - $i);
        }

        return $out;
    }
}
