<?php

namespace Fatso\Config;

use Symfony\Component\Yaml\Yaml;

class FileResource {
  
  private $path;
  
  public function __construct($path) {
    $this->path = $path;
  }
  
  /**
   * Tries to load file and parse it to array
   * 
   * @return array
   */
  public function load() {
    
    if(false === is_file($this->path)) {
      return array();
    }
    
    $ext = pathinfo($this->path, PATHINFO_EXTENSION);
    
    switch ($ext) {
      
      case 'yml':
      case 'yaml':
        return Yaml::parse($this->path);
        break;
      
      case 'php':
        return include($this->path);
        break;
    }
    
    return array();
  }
  
}
