<?php

namespace kcmerrill\vitaminc;

class projects{
    var $working_dir;
    var $extension;
    var $projects;

    function __construct($working_dir, $extension = '.project'){
       $this->extension = $extension;
       $this->working_dir = rtrim($working_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
       if(!is_dir($working_dir)){
            throw new LogicException('Expecting ' . $working_dir . ' to be a valid directory. WTF? Get it together dude!');
       }
       $this->fetchAll();
    }

    function exists($project){
        return isset($this->projects[$project]);
    }

    function fetch($project, $force_refresh = false) {
        if($force_refresh){
            $this->fetchAll();
        }
        return $this->exists($project) ? $this->projects[$project] : false;
    }

    function project_file_name($project_name){
        return str_replace(array(' ','-','#','@','!','*','(',')','+'), '_',$project_name) . '.project';
    }

    function update($project_name, $updated_project){
        if(!is_array($updated_project)){
            echo "not an array :(";
            return false;
        }

        $project = $this->fetch($project_name);
        if(!$project){
            $project = array(
                'project'=>$project_name,
                'project_file'=>$this->project_file_name($project_name)
            );
        }

        $project['project'] = isset($updated_project['project']) ? $updated_project['project'] : $project['project'];
        $project['ignore_files'] = isset($updated_project['ignore_files']) ? $updated_project['ignore_files'] : $project['ignore_files'];
        $project['ignore_files'] = is_string($project['ignore_files']) ? explode(',', $project['ignore_files']) : $project['ignore_files'];
        $project['ignore_files'] = is_array($project['ignore_files']) ? $project['ignore_files'] : array();
        $project['ignore_files'] = array_map('trim', $project['ignore_files']);
        $project['base_path'] = isset($updated_project['base_path']) ? $updated_project['base_path'] : $project['base_path'];
        $project['tests'] = isset($updated_project['tests']) ? $this->tests($updated_project['tests']) : $project['tests'];
        return $this->save($this->working_dir . $project['project_file'], $project);
    }

    function save($file, $project){
        $results = file_put_contents($file, json_encode($project, JSON_PRETTY_PRINT));
        return $this->fetchAll();
    }

    function tests($tests){
        $t = array();
        $tests = is_array($tests) ? $tests : array();
        foreach($tests as $test){
            if(isset($test['full_path'])){
                $t[] = $test['full_path'];
            }
        }
        return $t;
    }

    function fetchAll(){
        $this->projects = array();
        $files = files::inDir($this->working_dir);
        foreach($files as $file){
           if(strpos($file, $this->extension) >= 1){
                $contents = file_get_contents($this->working_dir . $file);
                $contents = json_decode($contents, TRUE);
                $contents = is_array($contents) ? $contents : array();
                $contents['valid_base_path'] = is_dir($contents['base_path']);
                $contents['tests'] = files::moreInfo(array_unique(is_array($contents['tests']) ? $contents['tests'] : array()), $contents['base_path']);
                $contents['project_file'] = $file;
                $this->projects[$contents['project']] = $contents;
           }
        }
        return is_array($this->projects) ? $this->projects : array();
    }
}
