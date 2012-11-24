<?php

namespace Fatso\Tests\Config;

use Fatso\Config\FileResource;

class FileResourceTest extends \PHPUnit_Framework_TestCase {
  
  /**
   * @dataProvider fileFormatsProvider
   */
  public function testFileFormats($path, $expected) {
    $file = new FileResource($path);
    
    $this->assertEquals($expected, $file->load());
  }
  
  public function testIfFileNotFound() {
    $file = new FileResource(__DIR__.'/../somefile.php');
    $data = $file->load();
    
    $this->assertTrue(is_array($data));
    $this->assertTrue(empty($data));
  }
  
  public function testIfUnknownFormat() {
    $file = new FileResource(__DIR__.'/../Fixtures/config/somefile.xml');
    $data = $file->load();
    
    $this->assertTrue(is_array($data));
    $this->assertTrue(empty($data));
  }
  
  public function fileFormatsProvider() {
    return array(
      array(__DIR__.'/../Fixtures/config/yaml_1.yml', array(
        'numbers' => array(1, 2, 3, 4, 5),
      )),
      array(__DIR__.'/../Fixtures/config/yaml_2.yaml', array(
        'numbers' => array(5, 4, 3, 2, 1),
      )),
    );
  }
  
}
