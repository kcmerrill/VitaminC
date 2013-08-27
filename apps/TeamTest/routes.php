<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

$app->post('/project', function(Request $request) use ($app){
    $post = $request->request->all();
    $created = $app['project']->create($post['name'], $post['basepath']);
    return $app->json(array('status'=>'created'), $created ? 200 : 404);
});

$app->get('/project/all', function() use ($app){
    return $app->json($app['project']->getAll());
});

$app->post('/project/test/{project}', function($project, Request $request) use ($app){
    $file = $request->request->all();
    $saved = $app['project']->addTest($file['test'], $project);
    return $app->json(array('status'=>'added'), $saved ? 200 : 404);
});

$app->delete('/project/test/{project}/{test}', function($project, $test, Request $request) use ($app){
    $removed = $app['project']->removeTest($test, $project);
    return $app->json(array('status'=>'removed'), $removed ? 200 : 404);
});

$app->get('/files/{query}/{project}', function($query, $project) use($app){
    if(!is_dir($app['project']->config('basepath', $project))){
        return $app->json(array(), 500);
    }
    /** forward slash hack */
    $query = str_replace('[[..........]]','/', $query);
    $iterator = new RecursiveDirectoryIterator($app['project']->config('basepath', $project, __DIR__));
    $files = array();
    foreach (new RecursiveIteratorIterator($iterator) as $fileinfo) {
        if(stristr($fileinfo, $query) && !$fileinfo->isDir()){
            $files[] = str_replace($app['project']->config('basepath', $project, ''), '', $fileinfo->getPathname());
        }
    }
    return $app->json($files, 200);
});