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
    $this->dispatcher->connect('context.load_factories', array($this, 'listenToContextLoadFactories'));
    $this->dispatcher->connect('editable_component.updated', array($this, 'listenToComponentUpdated'));
  }
  
  /**
   * Dynamic component cache invalidation
   *
   * @param  sfEvent  $event
   */
  public function listenToComponentUpdated(sfEvent $event)
  {
    if ($event['view_cache'] instanceof sfViewCacheManager)
    {
      $event['view_cache']->remove(sprintf('@sf_cache_partial?module=sfEditableComponent&action=_show&sf_cache_key=%s', $event->getSubject()->getCacheKey($event['culture'])));
    }
  }
  
  /**
   * Automatic plugin modules and helper loading
   *
   * @param  sfEvent  $event
   */
  public function listenToContextLoadFactories(sfEvent $event)
  {
    // Enable module automatically
    sfConfig::set('sf_enabled_modules', array_merge(sfConfig::get('sf_enabled_modules', array()), array('sfEditableComponent')));
    
    // Load helper as well
    $event->getSubject()->getConfiguration()->loadHelpers(array('sfEditable'));
  }
}