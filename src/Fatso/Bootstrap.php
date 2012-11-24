<?php

namespace Fatso;

use Silex\Application;

class Bootstrap {
  
  private $app;
  
  private $envDected = false;
  
  public function __construct(Application $app) {
    $this->app = $app;
  }
  
  public function runApplication() {
    $this->detectEnv()
      ->setupApplication()
      ->registerProviders()
      ->registerRoutes();
    
    return $this->app->run();
  }
  
  /**
   * @return \Fatso\Bootstrap
   */
  private function setupApplication() {
    
    foreach($this->app['config']->get('app') as $name => $value) {
      $this->app[$name] = $value;
    }
    
    return $this;
  }
  
  /**
   * Detects env based on page host
   * 
   * @return \Fatso\Bootstrap
   */
  private function detectEnv() {
    $this->app['env']->setConfig($this->app['config']->get('env'));
    $this->app['env']->detect($this->app['env.host'] ?: $_SERVER['HTTP_HOST']);
    
    $this->envDected = true;
    
    return $this;
  }
  
  /**
   * Registers providers defined in config/bootstrap
   * 
   * @throws \BadMethodCallException
   * @return \Fatso\Bootstrap
   */
  private function registerProviders() {
    
    if(false === $this->envDected) {
      throw new \BadMethodCallException('Detect env first');
    }
    
    foreach($this->app['config']->get('bootstrap/*') as $class => $options) {
      $this->app->register(new $class(), $options ?: array());
    }
    
    return $this;
  }
  
  /**
   * @return \Fatso\Bootstrap
   * @throws \RuntimeException
   */
  private function registerRoutes() {
    $allowed_methods = array('get', 'post', 'put', 'delete', 'match');
    
    foreach($this->app['config']->get('routing') as $name => $config) {
      
      if(true === empty($config['controller'])) {
        throw new \RuntimeException('No controller provided');
      }
      
      if(true === empty($config['pattern'])) {
        throw new \RuntimeException('No pattern provided');
      }
      
      $method = (isset($config['method'])) ? strtolower($config['method']) : 'get';
      
      if(false === in_array($method, $allowed_methods)) {
        throw new \RuntimeException('Bad method name');
      }
      
      list($ns, $controller, $action) = explode(':', $config['controller']);
      $to = sprintf('\\%s\\Controller\\%s::%s', $ns, $controller, $action);
      
      $this->app->$method($config['pattern'], $to)
        ->bind($name);
    }
    
    return $this;
  }
  
}
