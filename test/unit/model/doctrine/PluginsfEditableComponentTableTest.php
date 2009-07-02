<?php

require_once dirname(__FILE__).'/../../../bootstrap.php';

$t = new lime_test(12, new lime_output_color());

// removes old test records
PluginsfEditableComponentTable::getEditableComponent('bar', 'html', 'foo')->delete();
PluginsfEditableComponentTable::getEditableComponent('bar2', 'html', 'foo')->delete();

// getEditableComponent()
$t->diag('getEditableComponent()');
$c = PluginsfEditableComponentTable::getEditableComponent('bar', 'html', 'foo');
$t->isa_ok($c, 'sfEditableComponent', 'getEditableComponent() returns a sfEditableComponent when createAndSave=true');
$t->is($c->getName(), 'bar', 'getEditableComponent() saves the name when createAndSave=true');
$t->is($c->getNamespace(), 'foo', 'getEditableComponent() saves the namespace when createAndSave=true');
$t->is($c->getType(), 'html', 'getEditableComponent() saves the type when createAndSave=true');
$t->is($c->exists(), true, 'getEditableComponent() saved the object when createAndSave=true');

$c2 = PluginsfEditableComponentTable::getEditableComponent('bar', 'html', 'foo');
$t->is($c2->getId(), $c->getId(), 'getEditableComponent() do not create duplicate components');

$c = PluginsfEditableComponentTable::getEditableComponent('bar2', 'html', 'foo', false);
$t->isa_ok($c, 'sfEditableComponent', 'getEditableComponent() returns a sfEditableComponent when createAndSave=false');
$t->is($c->getName(), 'bar2', 'getEditableComponent() saves the name when createAndSave=false');
$t->is($c->getNamespace(), 'foo', 'getEditableComponent() saves the namespace when createAndSave=false');
$t->is($c->getType(), 'html', 'getEditableComponent() saves the type when createAndSave=false');
$t->is($c->exists(), false, 'getEditableComponent() did not save the object when createAndSave=false');

// updateComponent()
$t->diag('updateComponent()');
PluginsfEditableComponentTable::updateComponent('bar2', 'my beautiful content', 'html', 'foo');
$c = PluginsfEditableComponentTable::getEditableComponent('bar2', 'html', 'foo', false);
$t->is($c->getContent(), 'my beautiful content', 'updateComponent() updates component content');