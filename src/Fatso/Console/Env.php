<?php

namespace Fatso\Console;

class Env extends \Fatso\EnvAbstract {

  public function setEnv($name) {
    
    if(false === in_array($name, $this->getEnvironments())) {
      throw new \RuntimeException('Unknown env');
    }
    
    if(null !== $this->name) {
      throw new \BadMethodCallException('Env is set');
    }
    
    $this->name = $name;
  }
  
}
