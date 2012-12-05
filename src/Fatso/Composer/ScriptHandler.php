<?php

namespace Fatso\Composer;

use Composer\Script\Event;

class ScriptHandler {
  
  public static function install(Event $e) {
    $bin_dir = $e->getComposer()->getConfig()->get('bin-dir');
    
    if(true === empty($bin_dir)) {
      return;
    }
    
    $install_dir = getcwd().'/'.$bin_dir;
    $file = $install_dir.'/chubby';
    
    if(true === file_exists($file)) {
      return;
    }
    
    if(false === is_dir($install_dir)) {
      mkdir($install_dir);
    }
    
    $src_path = __DIR__.'/../../../bin/chubby';
    
    copy($src_path, $file);
  }
  
  public static function remove(Event $e) {
    $bin_dir = $e->getComposer()->getConfig()->get('bin-dir');
    
    if(true === empty($bin_dir)) {
      return;
    }
    
    $install_dir = getcwd().'/'.$bin_dir;
    $file = $install_dir.'/chubby';
    
    if(true === file_exists($file)) {
      unlink($file);
    }
  }
  
}
