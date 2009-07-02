<div class="<?php echo sfConfig::get('app_sfDoctrineEditableComponentPlugin_css_class_name', 'editable') ?>" id="<?php echo $component->getName() ?>">
  <?php if ('html' === $component->getType()): ?>
    <?php echo $component->getContent(ESC_RAW) ?>
  <?php else: ?>
    <?php echo $component->getContent() ?>  
  <?php endif; ?>
</div>
<?php if ($sf_user->hasCredential(sfConfig::get('app_sfDoctrineEditableComponentPlugin_admin_credential', 'editable_content_admin'))): ?>
<script type="text/javascript">
$(document).ready(function() {
  $('#<?php echo $component->getName() ?>').editable($('a#sfEditableComponentService').attr('href'), {
    type        : 'wysiwyg',
    onblur      : 'ignore',
    submit      : '<?php echo sfConfig::get('app_sfDoctrineEditableComponentPlugin_controls.OK', 'OK') ?>',
    cancel      : '<?php echo sfConfig::get('app_sfDoctrineEditableComponentPlugin_controls.Cancel', 'Cancel') ?>',
    event       : 'dblclick',
    submitdata  : { namespace: '<?php echo $component->getNamespace() ?>', type: '<?php echo $component->getType() ?>' }
  });
});
</script>
<?php endif; ?>