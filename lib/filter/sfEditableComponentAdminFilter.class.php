<?php
/**
 * Checks for admin credential and loads appropriate javascript/css files to allow frontend admin
 *
 * @package    sfDoctrineEditableContent
 * @subpackage filter
 * @author     nperriault@gmail.com
 */
class sfEditableComponentAdminFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    if ($this->isFirstCall() && $this->context->getUser()->hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin')))
    {
      $this->addPluginAssets(sfConfig::get('app_sfDoctrineEditableComponentPlugin_assets', array()));
    }
    
    $filterChain->execute();
  }
  
  /**
   * Adds required or configured assets files to current response object, plus the admin 
   * javascript file (mandatory)
   *
   * @param  array   $configuration
   */
  protected function addPluginAssets(array $configuration = array())
  {
    $response = $this->context->getResponse();
    
    $pluginWebRoot = isset($configuration['web_root']) ? $configuration['web_root'] : '';
    
    if (isset($configuration['javascripts']) && is_array($configuration['javascripts']))
    {
      foreach ($configuration['javascripts'] as $name => $javascript)
      {
        $response->addJavascript(sprintf('%s%s', $pluginWebRoot, $javascript), 'last');
      }
    }
    
    if (isset($configuration['stylesheets']) && is_array($configuration['stylesheets']))
    {
      foreach ($configuration['stylesheets'] as $name => $stylesheet)
      {
        $response->addStylesheet(sprintf('%s%s', $pluginWebRoot, $stylesheet), 'last');
      }
    }
    
    // The admin javascript file is handled by symfony
    $response->addJavascript($this->context->getController()->genUrl('@editable_component_admin_js'), 'last');
    
    // The admin css file is handled by symfony
    $response->addStylesheet($this->context->getController()->genUrl('@editable_component_admin_css'), 'last');
  }
}