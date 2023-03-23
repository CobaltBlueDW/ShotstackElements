<?php

namespace src\lib\framework\behaviors;

interface ObjectingInterface {
    
    public static function fromFile($filePath);
    
    public static function toFile($classObj, $filePath);
    
    public function __construct($valueObj = null);
    
    public function setAll($valueObj);
    
    public function toObject();
    
}
