<?php

namespace teamtest\models;

class files{
    function query($basepath, $query, $callback, $max = 0){
        if(!is_dir($basepath)){
            return false;
        }

        echo $basepath;die();

        $iterator = new RecursiveDirectoryIterator($basepath);
        $files = array();
        foreach (new RecursiveIteratorIterator($iterator) as $fileinfo) {
            if($callback($fileinfo, $query)){
                $files[] = $fileinfo->getPathname();
            }
            if($max && count($files) > $max){
                break;
            }
        }
        return $files;
    }
}

