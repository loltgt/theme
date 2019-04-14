<?php
/**
 * Default inline backend script minified template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;
?>

<script type='text/javascript'>
!function(e,t){"use strict";t.addEventListener("DOMContentLoaded",function(){wp&&lodash&&("hooks"in wp&&wp.hooks.hasFilter("editor.BlockEdit")&&(e.theme_columns_inheriterance={initialized:!1,grid:12,last:-1,count:0}),"media"in wp&&wp.media.view.Settings.Gallery&&t.getElementById("tmpl-theme__gallery-settings")&&(lodash.extend(wp.media.gallery.defaults,{slider:""}),wp.media.view.Settings.Gallery=wp.media.view.Settings.Gallery.extend({template:function(e){return wp.media.template("gallery-settings")(e)+wp.media.template("theme__gallery-settings")(e)}})))})}(window,document);
</script>
