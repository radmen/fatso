<?php

namespace Fatso\Console;

class Bootstrap extends \Fatso\Bootstrap {
  
  public function runApplication() {
    $this->detectEnv()
      ->setupApplication()
      ->registerProviders();
    
    return $this->app->run();
  }

  protected function detectEnv() {
    $env = $this->app['input']->getParameterOption(array('--env', '-e'), 'dev');
    
    $this->app['env']->setConfig($this->app['config']->get('env'));
    $this->app['env']->setEnv($env);
    
    $this->envDected = true;
    
    return $this;
  }
  
  protected function registerRoutes() {
    throw new \BadMethodCallException('Can\'t register routes in CLI mode');
  }
  
}
