<?php

namespace Fatso\Tests;

use Fatso\Env;

class EnvTest extends \PHPUnit_Framework_TestCase {
  
  public function testGetEnvironments() {
    $env = $this->envFactory();
    $expected = array('dev', 'test', 'prod');
    
    $this->assertEquals($expected, $env->getEnvironments());
  }
  
  public function testEnvDetection() {
    $env = $this->envFactory();
    $env->detect('test.fatso.com');
    
    $this->assertEquals('test', $env->get());
  }
  
  public function testEnvIfDetectionFailed() {
    $env = $this->envFactory();
    $env->detect('bazinga.com');
    
    $this->assertNull($env->get());
  }
  
  public function testIfDetectThrowsException() {
    $env = $this->envFactory();
    $env->detect('test.fatso.com');
    
    $this->setExpectedException('\BadMethodCallException');
    $env->detect('test.fatso.com');
  }
  
  /**
   * @return Env
   */
  private function envFactory() {
    $env = new Env();
    $env->setConfig(array(
      'dev' => '/\.local\.com$/',
      'test' => '/test\.\w+\.com$/',
      'prod' => '/fatso\.com/',
    ));
    
    return $env;
  }
  
}
