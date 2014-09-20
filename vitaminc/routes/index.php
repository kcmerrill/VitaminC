<?php

$vitaminc->get('/', function() use($vitaminc){
   return file_get_contents(WWW_DIR . 'partials/index.html');
});
