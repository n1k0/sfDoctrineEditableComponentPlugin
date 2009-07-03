<?php

require_once dirname(__FILE__).'/../../../bootstrap.php';

$t = new lime_test(12, new lime_output_color());

// removes old test records
Doctrine::getTable('sfEditableComponent')->createQuery('e')->delete()->execute();

// getComponent()
$t->diag('getComponent()');
$c = PluginsfEditableComponentTable::getComponent('bar', 'html', 'foo');
$t->isa_ok($c, 'sfEditableComponent', 'getComponent() returns a sfEditableComponent when createAndSave=true');
$t->is($c->getName(), 'bar', 'getComponent() saves the name when createAndSave=true');
$t->is($c->getNamespace(), 'foo', 'getComponent() saves the namespace when createAndSave=true');
$t->is($c->getType(), 'html', 'getComponent() saves the type when createAndSave=true');
$t->is($c->exists(), true, 'getComponent() saved the object when createAndSave=true');

$c2 = PluginsfEditableComponentTable::getComponent('bar', 'html', 'foo');
$t->is($c2->getId(), $c->getId(), 'getComponent() do not create duplicate components');

$c = PluginsfEditableComponentTable::getComponent('bar2', 'html', 'foo', false);
$t->isa_ok($c, 'sfEditableComponent', 'getComponent() returns a sfEditableComponent when createAndSave=false');
$t->is($c->getName(), 'bar2', 'getComponent() saves the name when createAndSave=false');
$t->is($c->getNamespace(), 'foo', 'getComponent() saves the namespace when createAndSave=false');
$t->is($c->getType(), 'html', 'getComponent() saves the type when createAndSave=false');
$t->is($c->exists(), false, 'getComponent() did not save the object when createAndSave=false');

// updateComponent()
$t->diag('updateComponent()');
PluginsfEditableComponentTable::updateComponent('bar2', 'my beautiful content', 'html', 'foo');
$c = PluginsfEditableComponentTable::getComponent('bar2', 'html', 'foo', false);
$t->is($c->getContent(), 'my beautiful content', 'updateComponent() updates component content');