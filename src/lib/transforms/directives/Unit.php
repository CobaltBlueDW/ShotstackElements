<?php

namespace src\lib\transforms\directives;

class Unit extends Base {

    public $units = null;
    public $slug = 'unit:';
    public $contextLookUp = [
        "width" => ["unit" => "P", "plane" => "X"],
        "height" => ["unit" => "P", "plane" => "Y"],
        "scale" => ["unit" => "R", "plane" => "X"],
        "crop_top" => ["unit" => "R", "plane" => "Y"],
        "crop_bottom" => ["unit" => "R", "plane" => "Y"],
        "crop_left" => ["unit" => "R", "plane" => "X"],
        "crop_right" => ["unit" => "R", "plane" => "X"],
        "offset_x" => ["unit" => "R", "plane" => "X"],
        "offset_y" => ["unit" => "R", "plane" => "Y"],
        "skew_x" => ["unit" => "R", "plane" => "X"],
        "skew_y" => ["unit" => "R", "plane" => "Y"],
    ];

    public function __construct($valueObj = null) {
        $this->setUnits();
        $this->ObjectingConstruct($valueObj);
    }

    public function setUnits($valueObj = null) {
        $this->units = new \src\lib\transforms\units\Conversion($valueObj);
    }

    public function apply(string &$value, array $path, array $template) {
        $context = $this->getImplicitContext($path);
        $value = substr($value, strlen($this->slug));
        $value = trim($value);
        $unit = strtoupper(substr($value, -1));
        $value = (float)substr($value, 0, -1);

        // incase people write 'px' instead of 'p'
        if ($unit === 'X') {
            $unit = 'P';
        }

        $value = $this->convertUnits($value, $unit, $context['unit'], $context['plane']);
    }

    public function getImplicitContext(array $path) {
        $last = strtolower(end($path));
        if (isset($this->contextLookUp[$last])) {
            return $this->contextLookUp[$last];
        }

        $parent = strtolower(prev($path));
        $fingerprint = "{$parent}_{$last}";
        if (isset($this->contextLookUp[$fingerprint])) {
            return $this->contextLookUp[$fingerprint];
        }

        throw new \Exception('No implicit context found for {$fingerprint}');
    }

    public function convertUnits(float $value, string $from, string $to, string $plane) {
        if ($from === $to) {
            return $value;
        }

        $converter = "{$from}{$plane}to{$to}";
        if (method_exists($this->units, $converter)) {
            return $this->units->$converter($value);
        }

        throw new \Exception("Cannot convert {$from} to {$to} for plane {$plane}");
    }

}