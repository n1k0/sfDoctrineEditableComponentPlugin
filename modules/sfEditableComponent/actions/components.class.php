<?php
/**
 * sfDoctrineEditableComponentPlugin components
 *
 * @package    sfDoctrineEditableComponentPlugin
 * @subpackage component
 * @author     nperriault@gmail.com
 */
class sfEditableComponentComponents extends sfComponents
{
  /**
   * Shows a component
   *
   */
  public function executeShow()
  {
    $this->component = sfEditableComponentTable::getEditableComponent($this->name, $this->type, $this->namespace);
  }
}