<?php
/**
 * sfDoctrineEditableComponent base actions
 *
 * @package    sfDoctrineEditableComponent
 * @subpackage action
 * @author     nperriault@gmail.com
 */
class BasesfEditableComponentActions extends sfActions
{
  public function preExecute()
  {
    $assetsConfig = sfConfig::get('app_sfDoctrineEditableComponentPlugin_assets', array());
    $this->pluginWebRoot = isset($assetsConfig['web_root']) ? $assetsConfig['web_root'] : '';
    $this->componentCssClassName = sfConfig::get('app_sfDoctrineEditableComponentPlugin_component_css_class_name', 'sfEditableComponent');
    $this->defaultContent = sfConfig::get('app_sfDoctrineEditableComponentPlugin_default_content', 'Edit me');
  }

  public function executeCss(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin')));
  }

  public function executeJs(sfWebRequest $request)
  {
    $this->useRichEditor = sfConfig::get('app_sfDoctrineEditableComponentPlugin_use_rich_editor', false);
    $this->forward404Unless($this->getUser()->hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin')));
  }

  /**
   * Retrieves a component content
   *
   * @param  sfWebRequest  $request
   */
  public function executeGet(sfWebRequest $request)
  {
    $result = $error = '';

    try
    {
      $component = sfEditableComponentTable::getComponent($this->name, $this->type);
    }
    catch (Exception $e)
    {
      $error = $e->getMessage();
    }

    return $this->renderText(json_encode(array(
      'error'  => $error,
      'result' => isset($component) ? $component->getContent() : '',
    )));
  }

  /**
   * Update an editable component
   *
   * @param sfRequest $request A request object
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $result = $error = '';

    if (!$this->getUser()->hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin')))
    {
      $this->getResponse()->setStatusCode(403);

      $error = 'Forbidden';
    }

    if (!$request->hasParameter('id') || !$request->hasParameter('value'))
    {
      $error = 'Missing parameters';
    }
    
    $result = $this->dispatcher->filter(new sfEvent($this, 'editable_component.filter_contents', array(
      'name' => $name = $request->getParameter('id'),
      'type' => $type = $request->getParameter('type', PluginsfEditableComponentTable::DEFAULT_TYPE),
    )), $request->getParameter('value'))->getReturnValue();

    try
    {
      $component = sfEditableComponentTable::updateComponent($name, $result, $type);
    }
    catch (Doctrine_Exception $e)
    {
      $error = sprintf('Unable to update component "%s": %s', $name, $e->getMessage());
    }
    
    $this->dispatcher->notify(new sfEvent($component, 'editable_component.updated', array(
      'view_cache' => $this->context->getViewCacheManager(),
      'culture'    => $this->getUser()->getCulture(),
    )));

    return $this->renderText(json_encode(array(
      'error'  => $error,
      'result' => $result,
    )));
  }
}