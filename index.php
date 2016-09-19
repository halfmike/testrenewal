<?php
$f3 = require('lib/base.php');
$f3->config('config.ini');
if ((float)PCRE_VERSION<7.9) trigger_error('PCRE version is out of date');

$f3->route('GET /', 'Controller->index');
$f3->route('GET /@controller', '@controller->index');
$f3->route('GET /@controller/@action', '@controller->@action');
$f3->route('GET /@controller/@action/@param', '@controller->@action');

$f3->route('POST /@controller/@action', '@controller->@action');
$f3->route('POST /@controller/@action/@param', '@controller->@action');

$f3->run();