<?php
use Symfony\Component\HttpFoundation\Request;
use kcmerrill\vitaminc\files;

$vitaminc->get('projects/', function() use($vitaminc){
    $projects = $vitaminc['projects']->fetchAll();
    return $vitaminc->json(array_values($projects), 200);
});

$vitaminc->post('projects/{project}', function(Request $request, $project) use($vitaminc){
    $updated_project = json_decode($request->getContent(), TRUE);
    $updated_project_results = $vitaminc['projects']->update($project, $updated_project);
    return $vitaminc->json($updated_project_results, $updated_project_results ? 200 : 400);
});

$vitaminc->get('projects/{project}', function($project) use ($vitaminc) {
    $project = $vitaminc['projects']->fetch($project);
    return $vitaminc->json($project, $project ? 200 : 404);
});

$vitaminc->get('projects/{project}/files/search', function(Request $request, $project) use ($vitaminc) {
    $query = $request->get('query');
    $project = $vitaminc['projects']->fetch($project);
    if($project){
        $files = files::search($project['base_path'], $query);
        return $vitaminc->json($files, $files === false ? 404 : 200);
    } else {
        return $vitaminc->json(array(), 404);
    }
});

$vitaminc->get('projects/{project}/files/modified/{seconds}secondsago', function($project, $seconds) use ($vitaminc) {
    $project = $vitaminc['projects']->fetch($project);
    if($project){
        $files = files::modified($project['base_path'], strtotime($seconds . ' seconds ago'), $project['ignore_files']);
        return $vitaminc->json($files, $files === false ? 404 : 200);
    } else {
        return $vitaminc->json(array(), 404);
    }
});
