<?php
/**
 * Default inline customizer script template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>

<script type='text/javascript'>
jQuery(document).ready(function($) {
  wp.customize('theme_settings[brand_name]', function(value) {
    value.bind(function(to) {
      $('.custom-logo-link').html(to);
    });
  });
});
</script>
