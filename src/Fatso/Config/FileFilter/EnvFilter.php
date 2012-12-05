<?php

namespace Fatso\Config\FileFilter;

use Fatso\Env;

class EnvFilter implements \Fatso\Config\FileFilterInterface {

  /**
   * @var Env
   */
  private $env;
  
  public function __construct(Env $env) {
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
