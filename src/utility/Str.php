<?php

namespace Source\Utility;

class Str {
    private $content;

    public function __construct(){
        $this->content = "";
    }

    public function addLine($line){
        $cleanLine = preg_replace( "/\r|\n/", "", $line );

        $this->content .= $this->wrapLines($cleanLine)."\r\n";
    }

    public function wrapLines($line){
        if ((strlen($line) + 2) <= 75){
            return $line;
        } else {
            $firstLine = substr($line,0,73) . "\r\n";
            $otherLines = $this->wrapLines(substr($line, 73));
            return $firstLine.' '.$otherLines;
        }
    }

    public function addContent($content){
        $this->content .= $content;
    }

    public function getString(){
        return $this->content;
    }
}