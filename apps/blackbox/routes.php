<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

$app->post('/project', function(Request $request) use ($app){
    $post = $request->request->all();
    $created = $app['project']->create($post['name'], $post['basepath'], explode(',', $post['ignored_files']));
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

$app->get('/files/modified/{query}/{project}', function($query, $project) use($app){
    $files = $app['files']->query(
        $app['project']->config('basepath',$project,__DIR__),
        $query,
        function($fileinfo, $query){
            return $fileinfo->getMTime() >= $query && !$fileinfo->isDir();
        },
        $app['project']->config('ignored_files',$project,array()),
        1
    );
    return $app->json(array('time'=>time(),'query'=>$query, 'files'=>$files, 'basepath'=>$app['project']->config('basepath', $project, __DIR__),'modified'=>count($files) ? true : false, 'ignored'=> $app['project']->config('ignored_files',$project, array()) ), 200);
});

$app->post('test', function(Request $request) use ($app){
    $post = $request->request->all();
    $results = $app['runner']->test($post['file']);
    return $app->json($results, 200);
});

$app->get('/files/{query}/{project}', function($query, $project) use($app){
    $files = $app['files']->query(
        $app['project']->config(
            'basepath',
            $project,
            __DIR__
        ),
        $query,
        function($fileinfo, $query){
            return stristr($fileinfo, $query) && !$fileinfo->isDir();
        },
        $app['project']->config(
            'ignored_files',
            $project,
            array()
        )
    );
    return $app->json(is_array($files) ? $files : array(), is_array($files) ? 200 : 404);
});
