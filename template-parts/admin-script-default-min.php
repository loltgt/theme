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
!function(e,n){"use strict";var t=function(n){return"theme_columns_inheriterance"in e&&!theme_columns_inheriterance.initialized&&(theme_columns_inheriterance.initialized=!0),n},i=function(n,t,i){if(t&&i&&"object"==typeof i&&"core/columns"===t.name&&"columns"in i){if("theme_columns_inheriterance"in e&&!theme_columns_inheriterance.initialized)return n;var s,l=e.theme_columns_inheriterance,a=[],o=parseInt(i.columns)||1;if("_id"in i&&i._id&&"_columns"in i&&i._columns===o)return n;i._id=lodash.uniqueId(),i._columns=o,l.last++;var r=parseInt(l.last);s=parseInt(l.grid)/o,l[r]={_id:i._id,_cols:s,columns:o},l.last=r,"update"in l==!1&&i._columns!==o?l.update=!0:l.count++,a.push("row");n.className.replace(/(\srow)/g,"");a=lodash.union(n.className.split(" "),a),a=lodash.uniq(a),n.className=a.join(" "),console.log("columns",arguments)}return n},s=function(n,t,i){if(t&&i&&"object"==typeof i&&"core/column"===t.name){if("theme_columns_inheriterance"in e&&!theme_columns_inheriterance.initialized)return n;var s=e.theme_columns_inheriterance;if("_id"in i&&i._id&&"_columns"in i&&s.update)return n;for(var l=[],a=s.last,o=s[a]._id,r=parseInt(s[a].columns),c=parseInt(s[a]._cols),m=parseInt(s.count);m>0;m--)0===r&&(o=s[m]._id,r=parseInt(s[m].columns),c=parseInt(s[m]._cols));if(!r)return n;i._id=lodash.uniqueId(),i._parent=o,i._columns=r,i.columns=r,s[a].columns--,l.push("col-"+c);var u=n.className.replace(/(\scol-\d)/g,"");l=lodash.union(u.split(" "),l),l=lodash.uniq(l),n.className=l.join(" "),console.log("column",arguments)}return n};n.addEventListener("DOMContentLoaded",function(){wp&&lodash&&("hooks"in wp&&wp.hooks.hasFilter("editor.BlockEdit")&&(e.theme_columns_inheriterance={initialized:!1,grid:12,last:-1,count:0},wp.hooks.addFilter("editor.BlockEdit","theme/init-columns-classname",t),wp.hooks.addFilter("blocks.getSaveContent.extraProps","theme/add-columns-classname",i),wp.hooks.addFilter("blocks.getSaveContent.extraProps","theme/add-column-classname",s)),"media"in wp&&wp.media.view.Settings.Gallery&&n.getElementById("tmpl-theme__gallery-settings")&&(lodash.extend(wp.media.gallery.defaults,{slider:""}),wp.media.view.Settings.Gallery=wp.media.view.Settings.Gallery.extend({template:function(e){return wp.media.template("gallery-settings")(e)+wp.media.template("theme__gallery-settings")(e)}})))})}(window,document);
</script>
