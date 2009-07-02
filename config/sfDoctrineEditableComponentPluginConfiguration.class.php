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
    $this->dispatcher->connect('context.load_factories', array($this, 'listendForContextLoadFactories'));
  }
  
  public function listendForContextLoadFactories()
  {
    sfConfig::set('sf_enabled_modules', array_merge(sfConfig::get('sf_enabled_modules', array()), array('sfEditableComponent')));
  }
}