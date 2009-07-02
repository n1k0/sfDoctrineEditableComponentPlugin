<?php
/**
 * sfDoctrineEditableComponent actions
 *
 * @package    sfDoctrineEditableComponent
 * @subpackage action
 * @author     nperriault@gmail.com
 */
class sfEditableComponentActions extends sfActions
{
 /**
  * Update an editable component
  *
  * @param sfRequest $request A request object
  */
  public function executeUpdate(sfWebRequest $request)
  {
    if (!$this->getUser()->hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin')))
    {
      $this->getResponse()->setStatusCode(403);
      
      return $this->renderText('Forbidden');
    }
    
    $this->forward404Unless($request->isMethod('post') && $request->hasParameter('id') && $request->hasParameter('value'), 'No POST or missing parameters');
    
    try
    {
      sfEditableComponentTable::updateComponent($request->getParameter('id'), $html = $request->getParameter('value'));
    }
    catch (Doctrine_Exception $e)
    {
      $html = sfConfig::get('app_sfDoctrineEditableComponent_update_error', '<p>Error.</p>');
    }

    return $this->renderText($html);
  }
}
