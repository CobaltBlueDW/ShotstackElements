<?php

namespace src\lib\framework\behaviors;

trait ObjectingTrait {
    
    public static function fromFile($filePath){
        return new static( self::decodeSelf( \file_get_contents($filePath) ) );
    }
    
    public static function toFile($classObj, $filePath){
        \file_put_contents( $filePath, self::encodeSelf($classObj->toObject()) );
    }
    
    public static function encodeSelf($classObj){
        return \json_encode($classObj->toObject());
    }
    
    public static function decodeSelf($encodedClassObj){
        return \json_decode($encodedClassObj);
    }
    
    public function __construct($valueObj = null){
        if (is_array($valueObj)) {
            $valueObj = (object)$valueObj;
        }
        $valueObj = $this->addDefaults($valueObj);
        $this->setAll($valueObj);
    }
    
    protected function addDefaults($valueObj = null){
        if (empty($valueObj)) {
            $valueObj = new \stdClass();
        }
        if (is_array($valueObj)) {
            $valueObj = (object)$valueObj;
        }
        
        return $valueObj;
    }
    
    public function setAll($valueObj){
        if (is_array($valueObj)) {
            $valueObj = (object)$valueObj;
        }
        
        foreach($valueObj as $key => $value){
            $camel = \str_replace(' ', '', ucwords($key));
            if (\method_exists($this, 'set'.$camel)) {  // call camel case setter
                $this->{'set'.$camel}($value);
            } else if (\method_exists($this, 'set'.$key)) { // call exact match setter
                $this->{'set'.$key}($value);
            } else if (property_exists($this, $key)) {    // manually assign value for all members
                $this->$key = $value;
            } else {
                // skip
            }
        }
    }
    
    public function toObject(){
        $valObj = new \stdClass();
        foreach($this as $key => $value){
            if ($value instanceof \src\lib\framework\behaviors\ObjectingInterface) {
                $valObj->$key = $value->toObject();
            } else {
                $valObj->$key = json_decode(\json_encode($value));
            }
        }
        return $valObj;
    }
    
}
