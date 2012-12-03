#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';

$app = new Fatso\Console\Application();
$app->register(new Fatso\Provider\FatsoServiceProdiver(), array(
  'config.dir' => __DIR__.'/../config',
  'src.dir' => __DIR__.'/../src',
));

$app['env'] = $app->share(function() {
  return new Fatso\Console\Env();
});

$bootstrap = new Fatso\Console\Bootstrap($app);
$bootstrap->runApplication();
