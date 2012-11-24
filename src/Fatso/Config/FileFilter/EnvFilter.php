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
    $current_env = $this->env->get();
    $environments = $this->env->getEnvironments();
    
    if(null === $current_env) {
      
      return function() {
        return true;
      };
    }
    
    return function(\SplFileInfo $file) use ($current_env, $environments) {
      $pattern = sprintf('/.+_(%s)\.\w+$/', join('|', $environments));
      $match = array();
      
      if(false == preg_match($pattern, $file->getBasename(), $match)) {
        return true;
      }
      
      return $match[1] == $current_env;
    };
  }
}
