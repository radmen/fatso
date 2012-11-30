<?php

namespace Fatso\Util;

/**
 * Converts pseudo string callbacks to valid callbacks.
 * 
 * Can be used for function partial application (only for string callbacks)
 * 
 * Example of callbacks:
 *    \\Some\\Path\\Class::method 
 *    \\Some\\Path\\Class::method('argument', 1, false)
 */
class Callback {

  private $generatedCallback;
  
  private $callback;
  
  private $partialArguments = array();
  
  public function __construct($callback, $partialArguments = array()) {
    $this->callback = $callback;
    $this->partialArguments = $partialArguments;
  }

  /**
   * When called defined callback will be invoked.
   * 
   * All arguments passed will be merged with arguments defined in callback.
   * The defined callback arguments will be put as the last ones
   * 
   * @return mixed
   */
  public function __invoke() {
    
    if(null === $this->generatedCallback) {
      $this->generateCallback();
    }
    
    $arguments = func_get_args();
    $callback_arguments = array_merge($this->partialArguments, $arguments);
    
    return call_user_func_array($this->generatedCallback, $callback_arguments);
  }
  
  private function generateCallback() {
    $this->generatedCallback = $this->parseCallback($this->callback);
  }
  
  private function parseCallback($callback) {
    
    if(true === is_array($callback) && true === is_callable($callback)) {
      return $callback;
    }
    else if(true === is_array($callback)) {
      throw new \InvalidArgumentException('Callback is not callable');
    }
    
    if(true === is_object($callback) && true === method_exists($callback, '__invoke')) {
      return $callback;
    }
    
    if(false === is_string($callback)) {
      throw new \InvalidArgumentException('Invalid callback');
    }
    
    $match = array();
    $count_matched = preg_match('/^(.+?)::(\w+)(\(.+\))?$/', $callback, $match);
    
    if(false == $count_matched && true === is_callable($callback)) {
      return $callback;
    }
    else if(false == $count_matched) {
      throw new \InvalidArgumentException('Callback is not callable');
    }
    
    list(, $class, $method) = $match;
    
    if(false === class_exists($class) || false === method_exists($class, $method)) {
      throw new \InvalidArgumentException('Callback is not callable');
    }
    
    $callback = function() use ($class, $method) {
      $arguments = func_get_args();
      $obj = new $class();
      
      return call_user_func_array(array($obj, $method), $arguments);
    };
    
    if(false === empty($match[3])) {
      $this->partialArguments = $this->parseCallbackArguments(sprintf('<?php token%s', $match[3]));
    }
    
    return $callback;
  }

  /**
   * Tries to match all arguments passed to callback.
   * 
   * It can parse only primitive types passed to function (booleans, string, integers)
   * 
   * @param string $callbackSrc PHP source code for calling callback
   * @return array list of parsed arguments
   */
  private function parseCallbackArguments($callbackSrc) {
    $tokens = token_get_all($callbackSrc);
    $params = array();
    $listen = false;
    $primitives_map = array(
      'null' => null,
      'false' => false,
      'true' => true,
    );

    foreach($tokens as $item) {

      if('(' === $item) {
        $listen = true;
        continue;
      }

      if(')' === $item) {
        break;
      }

      if(false === $listen || true === is_string($item)) {
        continue;
      }

      switch ($item[0]) {

        case T_CONSTANT_ENCAPSED_STRING:
          $params[] = trim($item[1], '"\'');
          break;

        case T_STRING:
          $lower = strtolower($item[1]);
          $value = $item[1];

          if(true === array_key_exists($lower, $primitives_map)) {
            $value = $primitives_map[$lower];
          }

          $params[] = $value;
          break;

        case T_LNUMBER:
          $params[] = $item[1];
          break;
      }

    }

    return $params;
  }
  
  /**
   * Creates partial application of given callback
   * 
   * @return Callback
   * @throws \BadMethodCallException when no arguments are passed
   */
  public function partial() {
    $args = func_get_args();
    
    if(true === empty($args)) {
      throw new \BadMethodCallException('No arguments provided');
    }
    
    if(null === $this->generatedCallback) {
      $this->generateCallback();
    }
    
    return new self($this->generatedCallback, array_merge($this->partialArguments, $args));
  }
  
}
