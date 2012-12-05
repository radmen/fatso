<?php

namespace Fatso;

use \Symfony\Component\Finder\Finder;

class Config {
  
  private $scanDir;
  
  /**
   * @var Config\FileFilterInterface
   */
  private $fileFilter;
  
  public function __construct($scanDir) {
    $this->scanDir = rtrim($scanDir, '/');
  }

  /**
   * @param Config\FileFilterInterface $filter
   */
  public function setFilter(Config\FileFilterInterface $filter) {
    $this->fileFilter = $filter;
  }

  /**
   * @param string $name
   * @return array
   */
  public function get($name) {
    $config = array();
    $mapper = function($path) {
      return new Config\FileResource($path);
    };
    
    $files = array_map($mapper, $this->findFiles($name));
    
    foreach($files as $loader) {
      $config = array_replace_recursive($config, $loader->load());
    }
    
    return $config;
  }
  
  protected function findFiles($name) {
    $scan_dir = $this->scanDir;
    
    if('.' != ($dir = dirname($name))) {
      $scan_dir .= '/'.$dir;
    }

    $basename = basename($name);
    $pattern = sprintf('/%s\.\w+$/', str_replace('*', '.+', $basename));
    
    try {
      $finder = Finder::create()
        ->files()
        ->name($pattern)
        ->depth(0)
        ->in($scan_dir);
    }
    catch(\InvalidArgumentException $e) {
      return array();
    }
    
    if(null !== $this->fileFilter) {
      
      if(null !== $this->fileFilter->getFilter()) {
        $finder->filter($this->fileFilter->getFilter());
      }
      
      $filter_pattern_name = $this->fileFilter->getFilePattern($basename);
      
      if(false === empty($filter_pattern_name)) {
        $finder->name($filter_pattern_name);
      }
    }
    
    $files = array();
    
    foreach($finder as $file) {
      $files[] = $file->getPathname();
    }
    
    sort($files);
    
    return $files;
  }
  
}
