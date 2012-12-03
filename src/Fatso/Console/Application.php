<?php

namespace Fatso\Console;

use Symfony\Component\Console;

class Application extends \Silex\Application {

  public function __construct() {
    $app = $this;
    
    $app['src.dir'] = function() {
      throw new \RuntimeException('Set src dir first');
    };

    $app['console_factory'] = function() {
      return new Console\Application('Chubby', '0.1-DEV');
    };
    
    $app['input'] = $app->share(function() {
      return new Console\Input\ArgvInput();
    });
    
    $app['console'] = $app->share(function() use ($app) {
      $console = $app['console_factory'];
      $definition = $console->getDefinition();
      $definition->addOption(new Console\Input\InputOption(
        'env', 
        '-e', 
        Console\Input\InputOption::VALUE_OPTIONAL,
        'Environment name', 
        'dev'
      ));
      
      return $console;
    });
  }
  
  public function run() {
    $this->boot();
    $this['console']->run($app['input']);
  }
}
