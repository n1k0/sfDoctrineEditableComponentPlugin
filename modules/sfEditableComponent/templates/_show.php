<?php echo content_tag($tag, $component ? $component->getContent('html' === $component['type'] ? ESC_RAW : null) : null, array(
  'id'    => $name,
  'class' => sprintf('%s %s %s', $componentCssClassName, $component ? $component->getType() : '', isset($options['class']) ? $options['class'] : ''),
)) ?>