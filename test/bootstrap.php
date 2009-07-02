<?php
if (!isset($app))
{
  $app = null;
  
  foreach (array_reverse(glob(dirname(__FILE__).'/../../../apps/*', GLOB_ONLYDIR)) as $dir)
  {
    if (preg_match('/([a-z0-9_-])/i', $app = array_pop(explode(DIRECTORY_SEPARATOR, $dir))))
    {
      break;
    }
  }
  
  if (!$app)
  {
    throw new Exception('Testing this plugin implies to have at least one available application in your project');
  }
}

require_once dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

require $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));