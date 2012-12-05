<?php

namespace Fatso\Tests\Config\FileFilter;

use Fatso\Config\FileFilter\EnvFilter;

class EnvFilterTest extends \PHPUnit_Framework_TestCase {
  
  public function testFilter() {
    $env = $this->envMockFactory('prod', $this->never());
    $file_filter = new EnvFilter($env);
    
    $this->assertNull($file_filter->getFilter());
  }
  
  public function testConfigWithFilterPatterns() {
    $env = $this->envMockFactory('dev');
    $filter = new EnvFilter($env);
    $config = new \Fatso\Config(__DIR__.'/../../Fixtures/config/env');
    $config->setFilter($filter);
    
    $this->assertEquals(array('env' => 'dev'), $config->get('env'));
  }
  
  private function envMockFactory($currentEnv, $expects = null) {
    $mock = $this->getMock('\Fatso\Env', array('get', 'getEnvironments'));
    $mock->expects($expects ?: $this->atLeastOnce())
      ->method('get')
      ->will($this->returnValue($currentEnv));
    
    return $mock;
  }
  
}
