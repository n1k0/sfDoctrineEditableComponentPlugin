<<?php echo $tag ?> class="<?php echo $componentCssClassName ?> <?php echo $component ? $component->getType() : '' ?>"
  id="<?php echo $name ?>">
  <?php if ($component): ?>
    <?php if ('html' === $component->getType()): ?>
      <?php echo $component->getContent(ESC_RAW) ?>
    <?php else: ?>
      <?php echo $component->getContent() ?>  
    <?php endif; ?>
  <?php endif; ?>
</<?php echo $tag ?>>