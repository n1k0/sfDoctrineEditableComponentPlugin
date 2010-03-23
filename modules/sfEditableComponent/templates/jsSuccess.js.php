$(document).ready(function() {
  // Configuration
  var getServiceUrl = '<?php echo url_for('@editable_component_service_get') ?>';
  var updateServiceUrl = '<?php echo url_for('@editable_component_service_update') ?>';
  
  // Facebox settings
  $.facebox.settings.opacity = 0.4;
  $.facebox.settings.loadingImage = '<?php echo $pluginWebRoot ?>/facebox/loading.gif';
  $.facebox.settings.closeImage   = '<?php echo $pluginWebRoot ?>/facebox/closelabel.gif';
  
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
    $.facebox(function(){
      $.get(getServiceUrl, function(result) {
        $.facebox(
        '<form action="' + updateServiceUrl + '" method="post" id="sfEditableComponentForm">'
          + '<h2>Edit ' + $(component).attr('id') + '</h2>'
          + '<textarea name="value">' + $(component).html().trim() + '</textarea>'
          + '<input type="hidden" value="' + $(component).attr('id') + '" name="id"/>'
          + '<input type="hidden" value="' + ($(component).hasClass('plain') ? 'plain' : 'html') + '" name="type"/>'
          + '<input type="submit" value="<?php echo __('Update') ?>"/>'
        + '</form>'
        );
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
      var content = $(form).children('textarea').val();
      var id = $(form).children('input[type=hidden]').val();
      $('#' + id).html(content);
      $.facebox.close();
      return true;
    }, 'json');
    return false;
  });
});