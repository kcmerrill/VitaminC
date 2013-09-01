<?php
namespace teamtest\models;

class runner{
    var $runners = array();
    function __construct(){
        $this->runners = $this->getRunners(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runners');
    }

    function getRunners($dir){
        $runners = array();
        foreach (glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.json') as $runner) {
            $runner_json = json_decode(file_get_contents($runner), true);
            if($this->validRunner($runner_json)){
                $runner_json['filetypes'] = is_array($runner_json['filetypes']) ? $runner_json['filetypes'] : array($runner_json['filetypes']);
                $runners[$runner] = $runner_json;
            }
        }
        return $runners;
    }

    function validRunner($runner){
        return isset($runner['test']['cmd']) && isset($runner['test']['pass']) && isset($runner['filetypes']);
    }

    function getRunner($info){
        foreach($this->runners as $runner){
            if(in_array($info['extension'], $runner['filetypes'])){
                return $runner;
            }
        }
        return false;
    }

    function execute($cmd){
        return `$cmd`;
    }

    function match($expression, $text, $new, $old){
        return preg_match_all ('/'.$expression.'/is', $text, $matches) ? $new : $old;
    }

    function test($file = ''){
        $results = array(
            'status'=>'unknown',
            'raw'=>'',
            'cmd'=>''
        );
        if(!is_file($file)){
            return $results;
        }
        $runner = $this->getRunner(pathinfo($file));
        if($runner){
            $results['cmd'] = $runner['test']['cmd'] . ' ' . $file;
            $results['raw'] = $this->execute($results['cmd']);
            /** The results? **/
            $results['status'] = $this->match($runner['test']['pass'], $results['raw'], 'pass', $results['status']);
            $results['status'] = $this->match($runner['test']['fail'], $results['raw'], 'fail', $results['status']);
        }
        return $results;
    }
}