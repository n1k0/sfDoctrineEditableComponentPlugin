<?php
/**
 * Retrieves an editable component html code (convient proxy for a direct call to 
 * get_component('sfEditableComponent', 'show', array(...options...)))
 *
 * @param  string  $name  The editable component name
 * @param  string  $type  The type of component
 * @param  string  $tag   The tag name to embed the component contents in
 *
 * @return string
 */
function editable_component($name, $type = 'html', $tag = 'div')
{
  return get_component('sfEditableComponent', 'show', array(
    'name' => $name,
    'type' => $type,
    'tag'  => $tag,
  ));
}

/**
 * Echo an editable component contents (convient proxy for a direct call to 
 * include_component('sfEditableComponent', 'show', array(...options...)))
 *
 * @param  string  $name  The editable component name
 * @param  string  $type  The type of component
 * @param  string  $tag   The tag name to embed the component contents in
 */
function include_editable_component($name, $type = 'html', $tag = 'div')
{
  include_component('sfEditableComponent', 'show', array(
    'name' => $name,
    'type' => $type,
    'tag'  => $tag,
  ));
}