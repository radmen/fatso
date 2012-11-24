<?php

namespace Fatso\Tests;

use Fatso\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {
  
  public function testGetIfNoFileFound() {
    $config = $this->configFactory();
    $data = $config->get('foo');
    
    $this->assertTrue(is_array($data));
    $this->assertTrue(empty($data));
  }
  
  public function testGetConfig() {
    $config = $this->configFactory();
    $expected = array(
      'env' => '',
    );
    
    $this->assertEquals($expected, $config->get('simple'));
  }
  
  public function testGetManyConfigFiles() {
    $config = $this->configFactory();
    $expected = array(
      'bar' => array(
        'is_foo' => false,
        'is_bar' => true,
      ),
      'foo' => array(
        'is_foo' => true,
        'is_bar' => false,
      ),
    );
    
    $this->assertEquals($expected, $config->get('list/*'));
  }
  
  public function testWithFileFilter() {
    $config = $this->configFactory();
    $config->setFilter(new TestFilter());
    $expected = array(
      'env' => '',
    );
    
    $this->assertEquals($expected, $config->get('*'));
  }
  
  public function testWithWrondDir() {
    $config = $this->configFactory();
    
    $this->assertEquals(array(), $config->get('nothere/*'));
  }

  /**
   * @return \Fatso\Config
   */
  private function configFactory() {
    return new Config(__DIR__.'/Fixtures/config/');
  }
}

class TestFilter implements Config\FileFilterInterface {
  
  public function getFilter() {
    
    return function(\SplFileInfo $file) {
      
      if(true == preg_match('/yaml/', $file->getBasename())) {
        return false;
      }
      
      return true;
    };
  }
}
