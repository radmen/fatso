<?php

namespace Fatso\Config;

interface FileFilterInterface {
  
  function getFilter();
  
  function getFilePattern($name);
  
}
