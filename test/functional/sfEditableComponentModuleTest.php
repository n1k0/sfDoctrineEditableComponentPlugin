<?php
$app = 'backend';
require_once dirname(__FILE__).'/../bootstrap.php';

$adminCredential = sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin');

$browser = new sfTestFunctional(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

Doctrine::getTable('sfEditableComponent')->createQuery('e')->delete()->execute();

# Init session
$browser->info(sprintf('Testing from app "%s"', $app))->get('/');

if (class_exists('sfGuardSecurityUser', true) && $browser->getUser() instanceof sfGuardSecurityUser)
{
  $browser->test()->comment(sprintf('This plugin cannot be tested using the "%s" app configuration', $app));
  $browser->test()->comment('because it uses the sfGuardSecurityUser as the user class');
  $browser->test()->comment('Please try to use another app');
  $browser->shutdown();
  exit(0);
}

$browser->
  info('Testing service in unauthenticated mode')->
  
    get('/editable_component_service')->
    with('response')->begin()->
      isStatusCode(403)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->

    post('/editable_component_service')->
    with('response')->begin()->
      isStatusCode(403)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->
  
    post('/editable_component_service', array('id' => 'foo'))->
    with('response')->begin()->
      isStatusCode(403)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->
  
    post('/editable_component_service', array('id' => 'foo', 'value' => 'bar'))->
    with('response')->begin()->
      isStatusCode(403)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->
  
  info('Testing service in authenticated mode')->
  
    get('/editable_component_auth_test_service?authenticate&credential='.$adminCredential)->
    with('user')->hasCredential($adminCredential)->
  
    get('/editable_component_service')->
    with('response')->begin()->
      isStatusCode(404)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->

    post('/editable_component_service')->
    with('response')->begin()->
      isStatusCode(404)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->
  
    post('/editable_component_service', array('id' => 'foo'))->
    with('response')->begin()->
      isStatusCode(404)->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array(), 0)->
    end()->
  
    post('/editable_component_service', array('id' => 'foo', 'value' => 'bar'))->
    with('response')->begin()->
      isStatusCode(200)->
      contains('bar')->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array('name' => 'foo', 'namespace' => 'default', 'type' => 'html'), 1)->
    end()->
    
    post('/editable_component_service', array('id' => 'foo', 'value' => 'baz'))->
    with('response')->begin()->
      isStatusCode(200)->
      contains('baz')->
    end()->
    with('doctrine')->begin()->
      check('sfEditableComponent', array('name' => 'foo', 'namespace' => 'default', 'type' => 'html', 'content' => 'bar'), 0)->
      check('sfEditableComponent', array('name' => 'foo', 'namespace' => 'default', 'type' => 'html'), 1)->
    end()->
  
  shutdown()
;