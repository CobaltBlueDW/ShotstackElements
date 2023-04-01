<?php

namespace src\lib\framework\settings;

class Config implements \src\lib\framework\behaviors\ObjectingInterface {
    use \src\lib\framework\behaviors\ObjectingTrait {
        \src\lib\framework\behaviors\ObjectingTrait::__construct as ObjectingTrait_construct;
    }
    
    public $data = [];
    
    public function __construct($valueObj = null) {
        $this->ObjectingTrait_construct($valueObj);
    }

    public function setAll($valueObj){
        if (is_array($valueObj)) {
            $valueObj = (object)$valueObj;
        }
        
        foreach($valueObj as $key => $value){
            $this->data[$key] = $value;
        }
    }
    
    public function __isset($name) {
        return isset($this->data[$name]);
    }
    
    public function __get($name) {
        if (!isset($this->data[$name])) {
            return null;
        }
        
        return $this->data[$name];
    }
    
    public function __set($name, $value) {
        return $this->data[$name] = $value;
    }
    
}
