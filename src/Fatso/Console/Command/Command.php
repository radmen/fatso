<?php

namespace Fatso\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;

class Command extends BaseCommand {
  
  /**
   * @var \Pimple
   */
  protected $container;
  
  public final function setContainer(\Pimple $container) {
    $this->container = $container;
  }
  
}
