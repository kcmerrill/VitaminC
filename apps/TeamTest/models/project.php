<?php

namespace teamtest\models;

class project{

    var $projects = array();


    function __construct(){
        $this->projects = $this->getAll();
    }

    /**
     * Grabs all of the projects in a given folder
     * @return array
     */
    function getAll(){
        $projects = array();
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . '*.json') as $project) {
            $project_json = json_decode(file_get_contents($project), true);
            $projects[$project_json['file']] = $project_json;
        }
        return $projects;
    }

    function create($name, $basepath){
        $project = array(
            'basepath'=>$basepath,
            'name'=>$name,
            'file'=>$this->filename($name) . '.json'
        );
        $this->projects[$project['name']] = $project;
        return $this->save($project['name']);
    }

    function filename($name){
        return str_replace(array(' ','.','!','?','#'), '-',strtolower($name));
    }

    function config($config, $project, $default = false){
        return isset($this->projects[$project][$config]) ? $this->projects[$project][$config] : $default;
    }

    function save($project){
        $file_to_save = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . $this->config('file', $project);
        if(isset($this->projects[$project])){
            if(!is_dir(dirname($file_to_save))){
                mkdir(dirname($file_to_save), 0777, TRUE);
            }
            file_put_contents(
                $file_to_save,
                json_encode($this->projects[$project], JSON_PRETTY_PRINT));
            $this->getAll();
            return true;
        }
        return false;
    }

    function addTest($what, $project){
        return $this->add(array(
            'info'=>pathinfo($what),
            '_id'=>uniqid(),
            'state'=>'unknown',
            'path'=>$what
        ), 'tests', $project);
    }

    function removeTest($id, $project){
        if(isset($this->projects[$project])){
            $tests = $this->config('tests', $project, array());
            foreach($tests as $idx=>$test){
                if($test['_id'] == $id){
                    unset($tests[$idx]);
                }
            }
            return $this->set($tests, 'tests', $project);
        }
        else{
            return false;
        }
    }

    function set($what, $where, $project){
        if(isset($this->projects[$project])){
            $this->projects[$project][$where] = $what;
            $saved = $this->save($project);
            $this->projects = $this->getAll();
            return $saved;
        } else {
            return false;
        }
    }

    function add($what, $where, $project){
        if(isset($this->projects[$project])){
            $this->projects[$project][$where] = isset($this->projects[$project][$where]) ? $this->projects[$project][$where] : array();
            $this->projects[$project][$where][] = $what;
            $this->projects[$project][$where] = array_map("unserialize", array_unique(array_map("serialize", $this->projects[$project][$where])));
            $saved = $this->save($project);
            $this->projects = $this->getAll();
            return $saved;
        } else {
            return false;
        }
    }
}