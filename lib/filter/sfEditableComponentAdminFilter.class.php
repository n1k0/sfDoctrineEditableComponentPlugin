<?php
/**
 * Checks for admin credential and loads appropriate javascript/css files to allow editable 
 * components admin from a frontend context
 *
 * @package    sfDoctrineEditableContent
 * @subpackage filter
 * @author     Nicolas Perriault <nperriault@gmail.com>
 */
class sfEditableComponentAdminFilter extends sfFilter
{
  /**
   * This filter will check if the current connected user has the configured credential in
   * order to load the admin javascript and css files
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    if ($this->isFirstCall())
    {
      // load plugin assets if
      //   1) The credential was specified and the user has it
      //   2) No credential was specified, but the user is at least authenticated
      $credential = sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin');
      if (
        ($credential && $this->context->getUser()->hasCredential($credential))
        || ($credential === false && $this->context->getUser()->isAuthenticated()))
      {
        $this->addPluginAssets();
      }
    }
    
    $filterChain->execute();
  }
  
  /**
   * Adds required or configured assets files to current response object, plus the admin 
   * javascript and stylesheet files (mandatory)
   *
   */
  protected function addPluginAssets()
  {
    $response = $this->context->getResponse();
    
    $pluginWebRoot = sfConfig::get('app_sfDoctrineEditableComponentPlugin_assets_web_root', '');

    // JQuery
    if (true === sfConfig::get('app_sfDoctrineEditableComponentPlugin_load_jquery'))
    {
      $response->addJavascript(sprintf('%s/js/jquery-1.4.2.min.js', $pluginWebRoot), 'last');
    }
    
    // Facebox
    if (true === sfConfig::get('app_sfDoctrineEditableComponentPlugin_load_facebox'))
    {
      $response->addJavascript(sprintf('%s/facebox/facebox.js', $pluginWebRoot), 'last');
      $response->addStylesheet(sprintf('%s/facebox/facebox.css', $pluginWebRoot), 'last');
    }
    
    // CKEditor
    if (true === sfConfig::get('app_sfDoctrineEditableComponentPlugin_load_ckeditor'))
    {
      $response->addJavascript(sprintf('%s/ckeditor/ckeditor.js', $pluginWebRoot), 'last');
    }
    
    // The admin javascript file is handled by symfony
    $response->addJavascript($this->context->getController()->genUrl('@editable_component_admin_js'), 'last');
    
    // The admin css file is handled by symfony
    $response->addStylesheet($this->context->getController()->genUrl('@editable_component_admin_css'), 'first');
  }
}
