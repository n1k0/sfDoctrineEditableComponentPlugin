<?php
/**
 * Retrieves an editable component html code
 *
 * @param  string  $name     The editable component name
 * @param  string  $type     The type of component
 * @param  string  $tag      The tag name to embed the component contents in
 * @param  array   $options  An array of options
 *
 * @return string
 */
function editable_component($name, $type = 'html', $tag = 'div', array $options = array())
{
  return get_component('sfEditableComponent', 'show', array(
    'name'         => $name,
    'type'         => $type,
    'tag'          => $tag,
    'options'      => $options,               // ↓ dont blame me, have a look at I18NHelper.php
    'sf_cache_key' => sprintf('%s-%s', $name, sfContext::getInstance()->getUser()->getCulture()),
  ));
}

/**
 * Echo an editable component contents
 *
 * @param  string  $name     The editable component name
 * @param  string  $type     The type of component
 * @param  string  $tag      The tag name to embed the component contents in
 * @param  array   $options  An array of options
 */
function include_editable_component($name, $type = 'html', $tag = 'div', array $options = array())
{
  echo editable_component($name, $type, $tag, $options);
}