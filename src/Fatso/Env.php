<?php

namespace Fatso;

class Env {
  
  private $config = array();
  
  protected $name;
  
  public function setConfig(array $config) {
    $this->config = $config;
  }
  
  public function detect($host) {
    
    if(null !== $this->name) {
      throw new \BadMethodCallException('Env is set');
    }
    
    foreach($this->config as $name => $pattern) {
      
      if(true == preg_match($pattern, $host)) {
        $this->name = $name;
        
        return true;
      }
    }
    
    return false;
  }
  
  public function get() {
    return $this->name;
  }
  
  public function getEnvironments() {
    return array_keys($this->config);
  }
}
