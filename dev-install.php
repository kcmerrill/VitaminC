<?php
/** A completely hacky installer, needed just until I get more focus on the app installer in kiss. */
$kiss = file_get_contents('https://github.com/kcmerrill/kiss/archive/master.zip');
file_put_contents('kiss.zip', $kiss);
`unzip kiss.zip -d .;cp -rf kiss-master/* .;rm -rf kiss.zip;rm -rf kiss-master;`;
`php -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"`;
`./composer.phar install`;
$www_index = file_get_contents(__DIR__ . '/www/index.php');
$www_index = file_put_contents(__DIR__ . '/www/index.php', str_replace('localhost','TeamTest', $www_index));