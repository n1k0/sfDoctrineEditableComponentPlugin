<?php
/**
 * sfDoctrineEditableComponentPlugin base components
 *
 * @package    sfDoctrineEditableComponentPlugin
 * @subpackage component
 * @author     nperriault@gmail.com
 */

class BasesfEditableComponentComponents extends sfComponents
{
  /**
   * Shows a component
   *
   */
  public function executeShow()
  {
    $this->componentCssClassName = sfConfig::get('app_sfDoctrineEditableComponentPlugin_component_css_class_name', 'sfEditableComponent');
    
    $this->component = sfEditableComponentTable::getComponent($this->name, $this->type);
  }
}