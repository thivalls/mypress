<?php

    spl_autoload_register(function($class){
        $prefix = "src\\";
        $baseDir = __DIR__ . "/";
        $len = strlen($prefix);

        if(strncmp($prefix, $class, $len) !== 0){
            return;
        };

        $relativeClass = substr($class, $len);

        $file = str_replace("\\", "/", $baseDir) . str_replace("\\", "/", $relativeClass) . '.php';

        if(file_exists($file)) {
            require $file;
        }
    });
