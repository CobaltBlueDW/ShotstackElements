<?php

namespace src\lib\transforms\directives;

use NXP\MathExecutor;

class Calc extends Base {

    public $context = null;

    public $slug = 'calc:';

    static protected $mathExec = null;
    static protected $mustache = null;

    public function apply(string &$value, array $path, array $template) {
        $value = substr($value, strlen($this->slug));
        $value = trim($value);
        $value = $this->evaluate($value);
    }

    public function evaluate(string $value) {
        if (self::$mathExec === null) {
            self::$mathExec = new MathExecutor();
        }
        if (self::$mustache === null) {
            self::$mustache = new \Mustache_Engine(['entity_flags' => \ENT_QUOTES]);
        }
        
        $value = self::$mustache->render($value, $this->context);

        $value = self::$mathExec->execute($value);

        if ($value !== '' && \is_string($value)){
            $value = \json_decode($value, true, 512, \JSON_THROW_ON_ERROR);
        }

        return $value;
    }

}