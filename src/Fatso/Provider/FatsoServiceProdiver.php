<?php

namespace Fatso\Provider;

use Silex\Application as Application;
use Fatso\Config;
use Fatso\Env;
use Fatso\Bootstrap;

class FatsoServiceProdiver implements \Silex\ServiceProviderInterface {

  public function register(Application $app) {
    $app['config.dir'] = function() {
      throw new \RuntimeException('Set config.dir first');
    };
    
    $app['config'] = function() use ($app) {
      $env = null;
      
      if(true === $app->offsetExists('env')) {
        $env = $app['env']->get();
      }
      
      return new Config($app['config.dir'], $env);
    };
    
    $app['env'] = $app->share(function() {
      return new Env();
    });
    
    $app['bootstrap'] = $app->share(function() use ($app) {
      return new Bootstrap($app);
    });
  }

  public function boot(Application $app) {
    
  }
}
