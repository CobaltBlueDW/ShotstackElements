<?php

namespace src\lib\transforms\contexts;

use NXP\MathExecutor;

class TemplateEnvironment extends Base {
    
    public $units = null;

    public function __construct($valueObj = null) {
        $this->setUnits();
        parent::__construct($valueObj);
    }

    public function setUnits($valueObj = null) {
        $this->units = new \src\lib\transforms\units\Conversion($valueObj);
    }

    ///////////////////////////////
    // Mustache lambdas
    ///////////////////////////////

    public function calc():callable {
        return function(string $text, callable $render){
            $exec = new MathExecutor();
            return $exec->execute($render($text));
        };
    }

    public function PXtoR():callable {
        return function(string $text, callable $render){
            return $this->units->PXtoR(floatval($render($text)));
        };
    }

    public function PYtoR():callable {
        return function(string $text, callable $render){
            return $this->units->PYtoR(floatval($render($text)));
        };
    }

    public function RXtoP():callable {
        return function(string $text, callable $render){
            return $this->units->RXtoP(floatval($render($text)));
        };
    }

    public function RYtoP():callable {
        return function(string $text, callable $render){
            return $this->units->RYtoP(floatval($render($text)));
        };
    }

    public function GXtoR():callable {
        return function(string $text, callable $render){
            return $this->units->GXtoR(floatval($render($text)));
        };
    }

    public function GYtoR():callable {
        return function(string $text, callable $render){
            return $this->units->GYtoR(floatval($render($text)));
        };
    }

    public function RXtoG():callable {
        return function(string $text, callable $render){
            return $this->units->RXtoG(floatval($render($text)));
        };
    }

    public function RYtoG():callable {
        return function(string $text, callable $render){
            return $this->units->RYtoG(floatval($render($text)));
        };
    }

    public function PXtoG():callable {
        return function(string $text, callable $render){
            return $this->units->PXtoG(floatval($render($text)));
        };
    }

    public function PYtoG():callable {
        return function(string $text, callable $render){
            return $this->units->PYtoG(floatval($render($text)));
        };
    }

}