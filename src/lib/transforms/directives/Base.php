<?php

namespace src\lib\transforms\directives;

use src\lib\framework\behaviors\ObjectingInterface;
use src\lib\framework\behaviors\ObjectingTrait;

abstract class Base implements ObjectingInterface {
    use ObjectingTrait {
        ObjectingTrait::__construct as ObjectingConstruct;
        ObjectingTrait::setAll as ObjectingSetAll;
    }

    public $slug = null;
    public $context = null;

    public function found(string $value): bool {
        return substr($value, 0, strlen($this->slug)) === $this->slug;
    }

    public abstract function apply(string &$value, array $path, array $template);

}