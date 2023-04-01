<?php

namespace src\lib\transforms\directives;

class Json extends Base {

    public $slug = 'json:';

    public function apply(string &$value, array $path, array $template) {
        $value = substr($value, strlen($this->slug));
        $value = trim($value);

        if (substr($value, 0, 1) === '.') {
            $value = \BASE_PATH.$value;
        }

        $value = \json_decode( \file_get_contents($value, true), true, 512, \JSON_THROW_ON_ERROR );
    }

}