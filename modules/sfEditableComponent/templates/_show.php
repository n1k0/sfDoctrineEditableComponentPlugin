
<?php echo content_tag($tag, ('html' === $component['type'] ? $component->getContent(ESC_RAW) : $component['content']), array(
  'id'    => $name,
  'class' => sprintf('%s %s %s', $componentCssClassName, $component ? $component['type'] : '', isset($options['class']) ? $options['class'] : ''),
)) ?>