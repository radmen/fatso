# What is Fatso?
Silex is great microframework, but it's sometimes just _too_ simple. Fatso is his fat cousin.  
It provides very simple, basic classes and automates few things.

Fatso can:

* detect environment based on host name
* load config files (plain PHP arrays, or YAML) and merge thme with their env variants
* perform simple bootstrap of Silex providers
* load routes from config file

Fatso is ugly.
Yet Fatso can be useful.

# Fast class overview

TL;DR - goto [Demo](#)

## Config
Config loads files from `$app['config.dir']` path.  
It also uses env name to merge basic config with it's env variant.

Config can load PHP, or YML files.

Example:

_config/foo.yml_:

```yaml
foo:
	name: bar
	env: null
```

_config/foo_dev.php_:
```php
<?php
return array(
  'foo' => array(
    'env' => 'dev',
  ),
);
```

```php
$config = $app['config']->get('foo');
/*
$config = array(
  'foo' => array(
    'name' => 'bar',
    'env' => 'dev',
  ),
);
 */
```

## Env
Performs simple environment detection based on host name.  
Before running env variable `$app['env.host']` must be set.

```php
$app['env']->get(); // return current env name or NULL if not detected
$app['env']->getEnvironments(); // returns list of declared environments.
```

Environments are defined in config file named `env`:  
```yaml
dev: /\.local\.com$/
prod: //
```

## Bootstrap
Bootstrap is responsible for env detection, Silex providers registration and route registration.

Routes are defined in config file named `routing`:  
```php
<?php
return array(
  'main' => array(
    'pattern' => '/',
    'controller' => 'App:Main:index', // resolves to: \App\Controller\Main::index
    'method' => 'get', // can be set to: GET, POST, PUT, DELETE, or MATCH. Default is GET
  ),
);
```

To register some providers in config dir must be created folder named `bootstrap` with providers config files.

Sample provider config file:  
```yaml
\Silex\Provider\TwigServiceProvider:
  twig.path: 'view/'
```

# Fatso-skeleton
Wait for it.. :)