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
    if ($request->isMethod('post') && $request->hasParameter('value'))
    {
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
    
    $this->forward404('Only POST method is allowed');
  }
}
