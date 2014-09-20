<?php
use kcmerrill\vitaminc\projects;

$vitaminc = new Silex\Application();
$vitaminc['debug'] = true;

/* @TODO: Put this in the .ini ... */
date_default_timezone_set('America/Denver');

define('APP_DIR', __DIR__ . '/');
define('PROJECTS_DIR', APP_DIR . 'projects/');
define('RUNNERS_DIR', APP_DIR . 'runners/');
define('WWW_DIR', dirname(APP_DIR)  . '/www/');

$vitaminc['projects'] = $vitaminc->share(function(){
    return new projects(PROJECTS_DIR);
});

include __DIR__ . '/routes/index.php';
include __DIR__ . '/routes/projects.php';
include __DIR__ . '/routes/test.php';
