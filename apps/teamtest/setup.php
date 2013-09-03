<?php

/** Include our Projects */
$app['project'] = $app->share(function(){
    return new teamtest\models\project;
});

$app['files'] = $app->share(function(){
    return new teamtest\models\files;
});

$app['runner'] = $app->share(function(){
    return new teamtest\models\runner;
});