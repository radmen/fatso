<?php

namespace Fatso\Tests\Config\FileFilter;

use Fatso\Config\FileFilter\EnvFilter;
use Fatso\Env;

class EnvFilterTest extends \PHPUnit_Framework_TestCase {
  
  public function testFilter() {
    $paths = array(
      $this->fileInfoFactory('a.php'),
      $this->fileInfoFactory('a_dev.php'),
      $this->fileInfoFactory('a_prod.php'),
      $this->fileInfoFactory('b_prod.php'),
      $this->fileInfoFactory('c_test.php'),
      $this->fileInfoFactory('d.php'),
      $this->fileInfoFactory('metoo.php'),
    );
    
    $env = $this->envMockFactory('prod', array('dev', 'test', 'prod'));
    $file_filter = new EnvFilter($env);
    
    $filtered = array_filter($paths, $file_filter->getFilter());
    $expected = array(
      'a.php',
      'a_prod.php',
      'b_prod.php',
      'd.php',
      'metoo.php',
    );
    
    $mapper = function(\SplFileInfo $file) {
      return $file->getBasename();
    };
    
    $this->assertEquals($expected, array_map($mapper, array_values($filtered)));
  }
  
  public function testFilterIfEnvEmpty() {
    $env = $this->envMockFactory(null, array());
    $file_filter = new EnvFilter($env);
    
    $callback = $file_filter->getFilter();
    
    $this->assertTrue($callback());
  }
  
  private function fileInfoFactory($basename) {
    return new \SplFileInfo($basename);
  }
  
  private function envMockFactory($currentEnv, $environments) {
    $mock = $this->getMock('\Fatso\Env', array('get', 'getEnvironments'));
    $mock->expects($this->atLeastOnce())
      ->method('get')
      ->will($this->returnValue($currentEnv));
    
    $mock->expects($this->atLeastOnce())
      ->method('getEnvironments')
      ->will($this->returnValue($environments));
    
    return $mock;
  }
  
}
