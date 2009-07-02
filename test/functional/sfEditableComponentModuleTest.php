<?php

require_once dirname(__FILE__).'/../bootstrap.php';

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  info(sprintf('Testing from app "%s"', $app))->
  info('Testing service in unauthenticated mode')->

  get('/')->
  with('user')->begin()->
    hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin'), false)->
  end()->
  
  get('/editable_component_service')->
  with('response')->begin()->
    isStatusCode(403)->
  end()->

  post('/editable_component_service')->
  with('response')->begin()->
    isStatusCode(403)->
  end()->
  
  post('/editable_component_service', array('id' => 'foo'))->
  with('response')->begin()->
    isStatusCode(403)->
  end()->
  
  post('/editable_component_service', array('id' => 'foo', 'value' => 'bar'))->
  with('response')->begin()->
    isStatusCode(403)->
  end()->
  
  info('Testing service in authenticated mode');

$browser->getUser()->addCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin'));

$browser->
  with('user')->begin()->
    hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin'))->
  end()->
  
  get('/editable_component_service')->
  with('response')->begin()->
    isStatusCode(404)->
  end()->

  post('/editable_component_service')->
  with('response')->begin()->
    isStatusCode(404)->
  end()->
  
  post('/editable_component_service', array('id' => 'foo'))->
  with('response')->begin()->
    isStatusCode(404)->
  end()->
  
  post('/editable_component_service', array('id' => 'foo', 'value' => 'bar'))->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  
  shutdown()
;