<?php
/**
 * Testing purpose only, do not use
 *
 */
class sfEditableComponentTestUtilityActions extends sfActions
{
  public function executeAuth(sfWebRequest $request)
  {
    if ('test' !== $env = sfConfig::get('sf_environment'))
    {
      throw new Exception(sprintf('Test env only ("%s" provided)', $env));
    }
    
    if ($request->hasParameter('authenticate'))
    {
      $this->getUser()->setAuthenticated(true);
    }
    
    if ($request->hasParameter('unauthenticate'))
    {
      $this->getUser()->setAuthenticated(false);
    }
    
    if ($request->hasParameter('credential'))
    {
      $this->getUser()->clearCredentials();
      $this->getUser()->addCredential($request->getParameter('credential'));
    }
    
//    return $this->renderText(sprintf('%s, %s', $this->getUser()->isAuthenticated() ? 'true' : 'false', implode(', ', $this->getUser()->listCredentials())));
    $this->redirect('@editable_component_service');
  }
}