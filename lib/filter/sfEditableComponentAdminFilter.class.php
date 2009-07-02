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
      $response = $this->context->getResponse();
      
      # JavaScripts
      $response->addJavascript('/sfDoctrineEditableComponentPlugin/js/jquery-1.3.2.min.js');
      $response->addJavascript('/sfDoctrineEditableComponentPlugin/js/jquery.wysiwyg.js');
      $response->addJavascript('/sfDoctrineEditableComponentPlugin/js/jquery.jeditable.min.js');
      $response->addJavascript('/sfDoctrineEditableComponentPlugin/js/jquery.jeditable.wysiwyg.js');

      # StyleSheets
      $response->addStylesheet('/sfDoctrineEditableComponentPlugin/css/jquery.wysiwyg.css');
      $response->addStylesheet('/sfDoctrineEditableComponentPlugin/css/sfDoctrineEditableComponentPlugin.css');
       
      # Append service link to body
      $this->context->getEventDispatcher()->connect('response.filter_content', array($this, 'filterResponseContent'));
    }
    
    $filterChain->execute();
  }
  
  /**
   * Appends a link containing the service url to the current DOM document
   * 
   * @param  sfEvent  $event    An event
   * @param  string   $content  The current response content string
   *
   * @return string
   */
  public function filterResponseContent(sfEvent $event, $content)
  {
    $serviceUrl = $this->context->getController()->genUrl('@editable_component_service');
    
    return str_ireplace('</body>', sprintf('<a href="%s" id="sfEditableComponentService"></a></body>', $serviceUrl), $content);
  }
}