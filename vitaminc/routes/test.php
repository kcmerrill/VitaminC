<?php
use Symfony\Component\HttpFoundation\Request;
use kcmerrill\vitaminc\runner;

$vitaminc->get('test', function(Request $request) use ($vitaminc) {
    $file_to_test = $request->get('file');
    $runner = new runner($file_to_test, RUNNERS_DIR);
    return $vitaminc->json($runner->test(), is_null($file_to_test) ? 404 : 200);
});
