<?php
if (!is_file($autoloadFile = __DIR__ . '/../vendor/autoload.php')) {
    throw new \RuntimeException('Did not find vendor/autoload.php.');
}

$loader = require($autoloadFile);
$loader->add('ObjectMapper',__DIR__.'/../src/');