<?php

require __DIR__ . '/../../../vendor/autoload.php';

\spl_autoload_register(
    function ($class){
        $path = BASE_PATH . \str_replace('\\',DIRECTORY_SEPARATOR,$class) . '.php';
        if (\file_exists($path)) {
            require_once $path; 
        } else {
            //The file might be from a vendor lib, handled by another auto-loader, so just skip
            //#TODO: Add debugging output
        }
    }
, true, true);