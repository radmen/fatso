<?php

namespace Fatso\Provider;

use Silex\Application as Application;
use Fatso\Config;
use Fatso\Env;

class FatsoServiceProdiver implements \Silex\ServiceProviderInterface {

  public function register(Application $app) {
    $app['config.dir'] = function() {
      throw new \RuntimeException('Set config.dir first');
    };
    
    $app['config'] = function() use ($app) {
      $config = new Config($app['config.dir']);
      
      if(true === $app->offsetExists('env')) {
        $filter = new \Fatso\Config\FileFilter\EnvFilter($app['env']);
        $config->setFilter($filter);
      }
      
      return $config;
    };
    
    $app['env'] = $app->share(function() {
      return new Env();
    });
  }

  public function boot(Application $app) {
    
  }
}
