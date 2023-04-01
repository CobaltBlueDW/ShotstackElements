<?php

namespace src\lib\transforms\units;

class Conversion implements \src\lib\framework\behaviors\ObjectingInterface {
    use \src\lib\framework\behaviors\ObjectingTrait {
        \src\lib\framework\behaviors\ObjectingTrait::__construct as ObjectingConstruct;
        \src\lib\framework\behaviors\ObjectingTrait::setAll as ObjectingSetAll;
    }

    const DEFAULT_GRID_X = 1600;
    const DEFAULT_GRID_Y = 900;

    public $gridRes = [
        "x" => self::DEFAULT_GRID_X,
        "y" => self::DEFAULT_GRID_Y,
    ];
    public $outRes = [
        "x" => 1920,
        "y" => 1080,
    ];

    public function PXtoR(float $value) {
        return $value / $this->outRes['x'];
    }

    public function PYtoR(float $value) {
        return $value / $this->outRes['y'];
    }

    public function RXtoP(float $value) {
        return $value * $this->outRes['x'];
    }

    public function RYtoP(float $value) {
        return $value * $this->outRes['y'];
    }

    public function GXtoR(float $value) {
        return $value / $this->gridRes['x'];
    }

    public function GYtoR(float $value) {
        return $value / $this->gridRes['y'];
    }

    public function RXtoG(float $value) {
        return $value * $this->gridRes['x'];
    }

    public function RYtoG(float $value) {
        return $value * $this->gridRes['y'];
    }

    public function PXtoG(float $value) {
        return $this->RXtoG($this->PXtoR($value));
    }

    public function PYtoG(float $value) {
        return $this->RYtoG($this->PYtoR($value));
    }

}