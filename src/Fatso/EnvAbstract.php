<?php

namespace Fatso;

abstract class EnvAbstract {
  
  protected $config = array();
  
  protected $name;
  
  public function get() {
    return $this->name;
  }
  
  public function getEnvironments() {
    return array_keys($this->config);
  }
  
  public function setConfig(array $config) {
    $this->config = $config;
  }
}
