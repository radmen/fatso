<?php

namespace Fatso\Console;

use Symfony\Component\Finder\Finder;

class Bootstrap extends \Fatso\Bootstrap {
  
  public function runApplication() {
    $this->detectEnv()
      ->setupApplication()
      ->registerProviders()
      ->registerCommands();
    
    return $this->app->run();
  }

  protected function detectEnv() {
    $env = $this->app['input']->getParameterOption(array('--env', '-e'), 'dev');
    
    $this->app['env']->setConfig($this->app['config']->get('env'));
    $this->app['env']->setEnv($env);
    
    $this->envDected = true;
    
    return $this;
  }
  
  protected function registerCommands() {
    $finder = Finder::create()
      ->path('*/Command/*.php')
      ->files()
      ->depth(2)
      ->in($this->app['src.dir']);
    
    foreach($finder as $file) {
      $name = preg_replace('/\.php$/', '', $file->getRelativePathname());
      $class_name = '\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $name);
      $command = new $class_name;
      
      if($command instanceof Command\Command) {
        $command->setContainer($this->app);
      }
      
      $this->app['console']->add($command);
    }
    
    return $this;
  }
  
  protected function registerRoutes() {
    throw new \BadMethodCallException('Can\'t register routes in CLI mode');
  }
  
}
