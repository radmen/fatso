<?php

namespace Fatso;

class Env extends EnvAbstract {
  
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
  
}
