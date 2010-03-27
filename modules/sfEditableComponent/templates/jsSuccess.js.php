$(document).ready(function(){
  // Configuration
  var getServiceUrl = '<?php echo url_for('@editable_component_service_get') ?>';
  var updateServiceUrl = '<?php echo url_for('@editable_component_service_update') ?>';
  var CKConfig = {
      toolbar  : 'Basic',
      language : '<?php echo $sf_user->getCulture() ?>',
      width    : 640,
      tabSpaces: 2,
      toolbar  : [
        ['Source', '-', 'RemoveFormat'],
        ['Bold', 'Italic', 'Underline', 'Strike'], 
        ['Link', 'Unlink', 'Anchor'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'], 
        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  
        '/', 
        ['Styles', 'Format'], 
        ['Image', 'Table', 'HorizontalRule', 'SpecialChar'],
        ['Maximize',  'ShowBlocks']
      ]
    };
  
  // Facebox settings
  $.facebox.settings.opacity = 0.4;
  $.facebox.settings.loadingImage = '<?php echo $pluginWebRoot ?>/facebox/loading.gif';
  $.facebox.settings.closeImage   = '<?php echo $pluginWebRoot ?>/facebox/closelabel.gif';
  $(this).bind('close.facebox', function() {
    // Removes every CKEditor opened instance
    if (CKEDITOR.instances['sfEditableComponentTextarea']) {
      CKEDITOR.remove(CKEDITOR.instances['sfEditableComponentTextarea']);
    }
  });
  
  // Components behaviors
  // Empty content check
  $('.<?php echo $componentCssClassName ?>').each(function(){
    if ('' == $(this).html().trim()) {
      $(this).html('<?php echo $defaultContent ?>');
    }
  });
  // Link deactivation
  $('.<?php echo $componentCssClassName ?> a').click(function() {
    if (confirm('Open link in a new window?')) {
      window.open($(this).attr('href'));
    }
    return false;
  });
  // Editing mode trigger
  $('.<?php echo $componentCssClassName ?>').dblclick(function() {
    var component = $(this);
    var type = $(component).hasClass('plain') ? 'plain' : 'html';
    $.facebox(function(){
      $.get(getServiceUrl, function(result) {
        $.facebox(
        '<form action="' + updateServiceUrl + '" method="post" id="sfEditableComponentForm">'
          + '<h2>Edit ' + $(component).attr('id') + '</h2>'
          + '<p><textarea name="value" id="sfEditableComponentTextarea">' + $(component).html().trim() + '</textarea></p>'
          + '<input type="hidden" value="' + $(component).attr('id') + '" name="id" id="sfEditableComponentId"/>'
          + '<input type="hidden" value="' + type + '" name="type" id="sfEditableComponentType"/>'
          + '<p><input type="submit" value="<?php echo __('Update') ?>"/></p>'
        + '</form>'
        );
        if ('html' == type) {
          CKEDITOR.replace('sfEditableComponentTextarea', CKConfig);
        }
      }, 'json');
    });
  });
  // Component contents update form
  $('#sfEditableComponentForm').live('submit', function(data){
    var form = $(this);
    $.post($(form).attr('action'), $(form).serialize(), function(result){
      if (result.error != '') {
        $(form).children('h2').after('<p class="error">Error encountered: ' + result.error + '</p>');
        return false;
      }
      $('#' + $('#sfEditableComponentId').val()).html($('#sfEditableComponentTextarea').val());
      $.facebox.close();
      return true;
    }, 'json');
    return false;
  });  
});
