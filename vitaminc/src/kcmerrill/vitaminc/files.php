<?php

namespace kcmerrill\vitaminc;

class files{
    static function inDir($working_directory){
        return scandir($working_directory);
    }

    static function moreInfo($files, $project_path = ''){
        $results = array();
        foreach($files as $file){
            $pathinfo = pathinfo($file);
            $pathinfo['relative_path'] = str_replace($project_path, '', $file);
            $pathinfo['full_path'] = $file;
            $results[] = $pathinfo;
        }
        return $results;
    }

    static function search($basepath, $query, $global_ignore = array(), $max = 10){
        if(!is_dir($basepath)){
            return false;
        }
        $iterator = new \RecursiveDirectoryIterator($basepath);
        $files = array();
        $global_ignore = is_array($global_ignore) ? $global_ignore : array();
        foreach (new \RecursiveIteratorIterator($iterator) as $fileinfo) {
            $ignored = false;
            foreach($global_ignore as $ignore){
                if(stristr($fileinfo->getPathname(), $ignore) !== false){
                    $ignored = true;
                    break; // This file is ignored, no need to keep checking
                }
            }
           if(!$ignored && stristr($fileinfo->getPathname(), $query) && !$fileinfo->isDir()){
                $files[] =
                    array(
                        'full_path'=>$fileinfo->getPathname(),
                        'relative_path'=>str_replace($basepath, '', $fileinfo->getPathname())
                    );
            }
            if($max && count($files) > $max){
                break;
            }
        }
        return $files;
    }



    static function modified($basepath, $since, $global_ignore = array(), $max = 10){
        if(!is_dir($basepath)){
            return false;
        }
        $iterator = new \RecursiveDirectoryIterator($basepath);
        $files = array();
        $global_ignore = is_array($global_ignore) ? $global_ignore : array();
        foreach (new \RecursiveIteratorIterator($iterator) as $fileinfo) {
            $ignored = false;
            foreach($global_ignore as $ignore){
                if(stristr($fileinfo->getPathname(), $ignore) !== false){
                    $ignored = true;
                    break; // This file is ignored, no need to keep checking
                }
            }
           if(!$ignored && $fileinfo->getMTime() >= $since && !$fileinfo->isDir()){
                $files[] = $fileinfo->getPathname();
            }
            if($max && count($files) > $max){
                break;
            }
        }
        return $files;
    }

}
