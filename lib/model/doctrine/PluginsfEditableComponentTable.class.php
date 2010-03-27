<?php
/**
 * Editable component Doctrine table class
 * 
 * @package    sfDoctrineEditableComponentPlugin
 * @subpackage model
 * @author     nperriault@gmail.com
 */
class PluginsfEditableComponentTable extends Doctrine_Table
{
  const DEFAULT_TYPE = 'html';
  
  /**
   * Updates a component
   *
   * @param  string       $name       The component name
   * @param  string       $content    The component content
   * @param  string|null  $type       The component type (html, plain) (optional)
   *
   * @return sfEditableComponent
   *
   * @throws Doctrine_Exception
   */
  static function updateComponent($name, $content, $type = self::DEFAULT_TYPE)
  {
    $component = self::getComponent($name, $type);
    
    $component->setContent($content);
    
    $component->save();
    
    return $component;
  }
  
  /**
   * Retrieves or creates a component
   *
   * @param  string       $name           The component name
   * @param  string|null  $type           The component type (html, plain) (optional)
   * @param  Boolean      $createAndSave  Create a new record if component not found?
   *
   * @return sfEditableComponent
   *
   * @throws Doctrine_Exception
   */
  static public function getComponent($name, $type = self::DEFAULT_TYPE, $createAndSave = true)
  {
    $table = Doctrine::getTable('sfEditableComponent');
    
    $component = $table->createQuery('c')
      ->leftJoin('c.Translation ct INDEXBY ct.lang')
      ->where('c.name = ? and c.type = ?', array($name, $type))
      ->fetchOne()
    ;
    
    if (!$component instanceof sfEditableComponent)
    {
      $component = $table->create(array(
        'name'      => $name,
        'type'      => $type,
        'content'   => sfConfig::get(sprintf('app_sfDoctrineEditableComponentPlugin_default_content'), ''),
      ));
    }
    
    if (true === $createAndSave)
    {
      $component->save();
    }
    
    return $component;
  }
}