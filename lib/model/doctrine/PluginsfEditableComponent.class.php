<?php
/**
 * Editable component Doctrine record class
 * 
 * @package    sfDoctrineEditableComponentPlugin
 * @subpackage model
 * @author     Nicolas Perriault <nperriault@gmail.com>
 */
abstract class PluginsfEditableComponent extends BasesfEditableComponent
{
  /**
   * Computes a cache key from current instance properties
   *
   * @param  string  $culture
   *
   * @return string
   */
  public function getCacheKey($culture)
  {
    return sprintf('%s-%s', $this->name, $culture);
  }
}