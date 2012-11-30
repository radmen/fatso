<?php

namespace Fatso\Tests\Util;

use Fatso\Util\Callback;

class CallbackTest extends \PHPUnit_Framework_TestCase {
  
  public function testArrayCallback() {
    $c = new CallbackContainer();
    $callback = new Callback(array($c, 'sum'));
    
    $this->assertEquals(6, $callback(1, 2, 3));
  }
  
  public function testStringCallback() {
    $callback = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::sum');
    $this->assertEquals(6, $callback(1, 2, 3));
    
    $simple = new Callback('\\Fatso\\Tests\\Util\\simple_sum');
    $this->assertEquals(6, $simple(1, 2, 3));
  }
  
  public function testFullPartialApplicationFromString() {
    $callback = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::concat(1, 2)');
    
    $this->assertEquals('12', $callback());
  }
  
  public function testPartialApplicationFromString() {
    $callback = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::concat(45)');
    
    $this->assertEquals('453', $callback(3));
  }
  
  public function testPartialApplication() {
    $callback = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::concat');
    $new = $callback->partial('abcd');
    
    $this->assertEquals('abcdEF', $new('EF'));
    $this->assertNotEquals(spl_object_hash($callback), spl_object_hash($new));
  }
  
  public function testTwicePartialApplication() {
    $callback = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::concat');
    $new = $callback->partial('abcd')
      ->partial('EF');
    
    $this->assertEquals('abcdEF', $new());
  }
  
  public function testIfEmptyArgumentsForPartialThrowException() {
    $this->setExpectedException('\BadMethodCallException');
    $callback = new Callback('str_repeat');
    $callback->partial();
  }
  
  public function testPartialApplicationWithStringArguments() {
    $callback = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::concat("foo")');
    $this->assertEquals('fooBar', $callback('Bar'));
    
    $unescaped = new Callback('\\Fatso\\Tests\\Util\\CallbackContainer::concat(foo)');
    $this->assertEquals('fooBar', $unescaped('Bar'));
  }
  
  public function testObjectCallback() {
    $callback = new Callback(function() {
      return 'callback';
    });
    
    $this->assertEquals('callback', $callback());
  }
  
  /**
   * @dataProvider exceptionsProvider
   */
  public function testExceptions($callback) {
    $callback = new Callback($callback);
    
    $this->setExpectedException('\InvalidArgumentException');
    $callback();
  }
  
  public function exceptionsProvider() {
    return array(
      array(array(new CallbackContainer, 'foo')),
      array(new CallbackContainer()),
      array('invalidfunction'),
      array('\\Foo::bar'),
    );
  }
  
  /**
   * @dataProvider stringCallbackWithPrimitivesProvider
   */
  public function testStringCallbackWithPrimitives($method, $value) {
    $callback = new Callback(sprintf('\\Fatso\\Tests\\Util\\CallbackContainer::%s(%s)', $method, $value));
    $this->assertTrue($callback());
  }
  
  public function stringCallbackWithPrimitivesProvider() {
    return array(
      array('checkBool', 'true'),
      array('checkBool', 'TRUE'),
      array('checkBool', 'false'),
      array('checkBool', 'FALSE'),
      array('checkNull', 'null'),
      array('checkNull', 'NULL'),
    );
  }
  
}

class CallbackContainer {
  
  public function sum($a, $b, $c) {
    return simple_sum($a, $b, $c);
  }
  
  public function concat($a, $b) {
    return simple_concat($a, $b);
  }
  
  public function checkBool($var) {
    return true === is_bool($var);
  }
  
  public function checkNull($var) {
    return null === $var;
  }
  
}

function simple_sum($a, $b, $c) {
  return $a + $b + $c;
}

function simple_concat($a, $b) {
  return sprintf('%s%s', $a, $b);
}