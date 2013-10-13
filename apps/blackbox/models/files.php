<?php

namespace blackbox\models;

class files{
    function query($basepath, $params, $callback, $global_ignore = array(), $max = 0){
        if(!is_dir($basepath)){
            return false;
        }
        $iterator = new \RecursiveDirectoryIterator($basepath);
        $files = array();
        $global_ignore = is_array($global_ignore) ? $global_ignore : array();
        foreach (new \RecursiveIteratorIterator($iterator) as $fileinfo) {
            if($callback($fileinfo, $params)){
                $ignored = false;
                foreach($global_ignore as $ignore){
                    if(stristr($fileinfo->getPathname(), $ignore) !== false){
                        $ignored = true;
                        break; // This file is ignored, no need to keep checking
                    }
                }
                if(!$ignored){
                    $files[] = $fileinfo->getPathname();
                }
            }
            if($max && count($files) > $max){
                break;
            }
        }
        return $files;
    }
}

