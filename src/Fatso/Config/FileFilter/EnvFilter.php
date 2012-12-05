<?php

namespace Fatso\Config\FileFilter;

use Fatso\EnvAbstract;

class EnvFilter implements \Fatso\Config\FileFilterInterface {

  /**
   * @var EnvAbstract
   */
  private $env;
  
  public function __construct(EnvAbstract $env) {
    $this->env = $env;
  }
  
  public function getFilter() {
    return null;
  }

  public function getFilePattern($name) {
    $current_env = $this->env->get();
    
    return "/{$name}_{$current_env}\.\w+$/";
  }
}
