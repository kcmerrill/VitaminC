<?php

namespace kcmerrill\vitaminc;

class runner{
    var $file;
    var $path_info;
    var $runner = false;
    var $runners_dir;

    function __construct($file, $runners_dir){
        $this->file = $file;
        $this->path_info = pathinfo($file);
        $this->path_info['full_filename'] = $file;
        $this->runner_dir = $runners_dir;
    }

    function load_runner($extension){
        $file_contents = file_get_contents($this->runner_dir . $extension . '.json');
        $this->runner = json_decode($file_contents, TRUE);
        return count($this->runner) && isset($this->runner['cmd']) ? true : false;
    }

    function customize($input){
        return $input;
    }

    function text_that_matched($expression, $text){
        return preg_match_all ('/'.$expression.'/is', $text, $matches) ? trim($matches[1][0]) : false;
    }

    function text_matched($expression, $text){
        return preg_match_all ('/'.$expression.'/is', $text, $matches) ? true : false;
    }

    function numeric_matched($expression, $text){
        preg_match_all ('/'.$expression.'/is', $text, $matches);
        return isset($matches[1][0]) && is_numeric($matches[1][0]) ? (int) $matches[1][0] : 0;
    }

    function test(){
        $results = array(
            'cmd'=>false,
            'raw_output'=>false,
            'error_message'=>'A valid test runner was not found for ' . $this->file,
            'pass'=>false,
            'fail'=>false,
            'stats'=>array(
                'test_count'=>0,
                'assertion_count'=>0
            )
        );

        if(isset($this->path_info['extension']) && $this->load_runner($this->path_info['extension'])){
            $results['raw_output'] = shell_exec(strtr($this->runner['cmd'], $this->path_info));
            $results['cmd'] = strtr($this->runner['cmd'], $this->path_info);
            $results['pass'] = $this->text_matched($this->runner['pass'], $results['raw_output']);
            $results['fail'] = $this->text_matched($this->runner['fail'], $results['raw_output']);
            $results['error_message'] = $this->text_that_matched($this->runner['error_message'], $results['raw_output']);
            $results['stats']['test_count'] = $this->numeric_matched($this->runner['stats']['test_count'], $results['raw_output']);
            $results['stats']['assertion_count'] = $this->numeric_matched($this->runner['stats']['assertion_count'], $results['raw_output']);
        }

        return $results;
    }
}
