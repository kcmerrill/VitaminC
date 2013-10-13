<?php

/** Include our Projects */
$app['project'] = $app->share(function(){
    return new blackbox\models\project;
});

$app['files'] = $app->share(function(){
    return new blackbox\models\files;
});

$app['runner'] = $app->share(function(){
    return new blackbox\models\runner;
});