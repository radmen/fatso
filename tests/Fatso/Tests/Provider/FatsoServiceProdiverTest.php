<?php

namespace Fatso\Tests\Provider\FatsoServiceProdiver;

use Fatso\Provider\FatsoServiceProdiver;
use Silex\Application;

class FatsoServiceProdiverTest extends \PHPUnit_Framework_TestCase {
  
  public function testRegister() {
    $app = new Application();
    $app->register(new FatsoServiceProdiver(), array(
      'config.dir' => __DIR__.'/../Fixtures/config',
    ));
    
    $this->assertTrue($app['config'] instanceof \Fatso\Config);
    $this->assertTrue($app['env'] instanceof \Fatso\Env);
  }
  
  public function testRegisterWithoutConfigDir() {
    $app = new Application();
    $app->register(new FatsoServiceProdiver());
    
    $this->setExpectedException('\RuntimeException');
    $app['config.dir'];
  }
  
}
