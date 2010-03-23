<?php
/**
 * Plugin configuration class.
 * 
 * @package    sfDoctrineEditableComponentPlugin
 * @subpackage config
 * @author     nperriault@gmail.com
 */
class sfDoctrineEditableComponentPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function configure()
  {
    $this->dispatcher->connect('context.load_factories', array($this, 'listenForContextLoadFactories'));
  }
  
  public function listenForContextLoadFactories(sfEvent $event)
  {
    $pluginModules = array('sfEditableComponent');
    
    // Enables the auth testing service utility only in 'test' env
    if ('test' === sfConfig::get('sf_environment'))
    {
      $pluginModules[] = 'sfEditableComponentTestUtility';
    }
    
    sfConfig::set('sf_enabled_modules', array_merge(sfConfig::get('sf_enabled_modules', array()), $pluginModules));
    
    // Helper loading
    $event->getSubject()->getConfiguration()->loadHelpers(array('sfEditable'));
  }
}