<?php

namespace src\lib\framework\di;

class DIGlobal implements \src\lib\framework\behaviors\ObjectingInterface {
    use \src\lib\framework\behaviors\ObjectingTrait {
        \src\lib\framework\behaviors\ObjectingTrait::__construct as ObjectingTrait_construct;
    }
    
    public $constructors = [];
    public $DIs = [];
    
    public function addDefinition(string $name, callable $constructor){
        $this->constructors[$name] = $constructor;
    }
    
    public function __construct($valueObj = null) {
        $this->ObjectingTrait_construct($valueObj);
    }
    
    public function __get($name){
        if (!isset($this->DIs[$name])) {
            //make one
            if (!isset($this->constructors[$name])) {
                throw new \Exception("No DI definition found for '$name'.");
            }
            
            $this->DIs[$name] = $this->constructors[$name]();
        }
        
        if (!isset($this->DIs[$name])) {
            throw new \Exception("Unable to create DI for '$name'.");
        }
        
        return $this->DIs[$name];
    }
    
    public function __isset($name){
        return isset($this->DIs[$name]);
    }
    
}
