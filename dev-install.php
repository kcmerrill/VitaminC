<?php
$kiss = file_get_contents('https://github.com/kcmerrill/kiss/archive/master.zip');
file_put_contents('kiss.zip', $kiss);
`unzip kiss.zip -d .;cp -rf kiss-master/* .;rm -rf kiss.zip;rm -rf kiss-master;`;
`php -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"`;
`./composer.phar install`;