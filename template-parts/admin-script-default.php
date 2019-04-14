<?php
/**
 * Default inline backend script template part
 *
 * //TODO FIX
 *
 * @package theme
 * @version 1.0
 */

namespace theme;
?>

<script type='text/javascript'>
(function(window, document) {
  'use strict';


  /**
   * Initializes columns inheriterance
   *
   * @see /wp-includes/js/dist/edit-post.js
   */
  var initColumnsClassname = function(__) {
    if ('theme_columns_inheriterance' in window && ! theme_columns_inheriterance.initialized) {
      theme_columns_inheriterance.initialized = true;
    }

    return __;
  }


  /**
   * Adds custom classname(s) for parent columns
   *
   * @see /wp-includes/js/dist/blocks.js
   */
  var addColumnsClassname = function(props, blockType, attributes) {
    if (blockType && attributes && typeof attributes === 'object' && blockType.name === 'core/columns' && 'columns' in attributes) {
      if ('theme_columns_inheriterance' in window && ! theme_columns_inheriterance.initialized) {
        return props;
      }

      var tci = window.theme_columns_inheriterance;

      var cols = 1;
      var classes = [];

      var columns = parseInt(attributes.columns) || 1;

      if ('_id' in attributes && attributes._id && '_columns' in attributes && attributes._columns === columns) {
        return props;
      }

      attributes._id = lodash.uniqueId();
      attributes._columns = columns;

      tci.last++;

      var index = parseInt(tci.last);
      var grid = parseInt(tci.grid);

      cols = grid / columns;

      tci[index] = { _id: attributes._id, _cols: cols, columns: columns };
      tci.last = index;

      if ('update' in tci === false && attributes._columns !== columns) {
        tci.update = true;
      } else {
        tci.count++;
      }

      classes.push('row');

      var _classes = props.className.replace(/(\srow)/g, '');
      classes = lodash.union(props.className.split(' '), classes);
      classes = lodash.uniq(classes);

      props.className = classes.join(' ');

      console.log('columns', arguments);
    }

    return props;
  }

  /**
   * Adds custom classname(s) for child columns
   *
   * @see /wp-includes/js/dist/blocks.js
   */
  var addColumnClassname = function(props, blockType, attributes) {
    if (blockType && attributes && typeof attributes === 'object' && blockType.name === 'core/column') {
      if ('theme_columns_inheriterance' in window && ! theme_columns_inheriterance.initialized) {
        return props;
      }

      var tci = window.theme_columns_inheriterance;

      if ('_id' in attributes && attributes._id && '_columns' in attributes && tci.update) {
        return props;
      }

      var classes = [];

      var index = tci.last;
      var parent = tci[index]._id;
      var columns = parseInt(tci[index].columns);
      var col = parseInt(tci[index]._cols);

      for (var i = parseInt(tci.count); i > 0; i--) {
        if (columns === 0) {
          parent = tci[i]._id;
          columns = parseInt(tci[i].columns);
          col = parseInt(tci[i]._cols);
        }
      }

      if (! columns) {
        return props;
      }

      attributes._id = lodash.uniqueId();
      attributes._parent = parent;
      attributes._columns = columns;
      attributes.columns = columns;

      tci[index].columns--;

      classes.push('col-' + col);

      var _classes = props.className.replace(/(\scol-\d)/g, '');
      classes = lodash.union(_classes.split(' '), classes);
      classes = lodash.uniq(classes);

      props.className = classes.join(' ');

      console.log('column', arguments);
    }

    return props;
  }


  /**
   * Adds custom theme settings to the gallery media manager view
   *
   * //TODO implement core/gallery extraProps
   *
   * @see /wp-includes/js/media-views.js
   */
  var mediaGallerySettings = function() {
    lodash.extend(wp.media.gallery.defaults, {
      slider: ''
    });

    wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
      template: function(view) {
        return wp.media.template('gallery-settings')(view) +
          wp.media.template('theme__gallery-settings')(view);
      }
    });
  }


  /**
   * Load function
   */
  var _load = function() {
    if (! wp || ! lodash) {
      return;
    }

    if ('hooks' in wp && wp.hooks.hasFilter('editor.BlockEdit')) {
      window.theme_columns_inheriterance = { initialized: false, grid: 12, last: -1, count: 0 };

      /* //TODO FIX
      wp.hooks.addFilter('editor.BlockEdit', 'theme/init-columns-classname', initColumnsClassname);
      wp.hooks.addFilter('blocks.getSaveContent.extraProps', 'theme/add-columns-classname', addColumnsClassname);
      wp.hooks.addFilter('blocks.getSaveContent.extraProps', 'theme/add-column-classname', addColumnClassname);*/
    }

    if ('media' in wp && wp.media.view.Settings.Gallery) {
      if (document.getElementById('tmpl-theme__gallery-settings')) {
        mediaGallerySettings();
      }
    }
  }

  document.addEventListener('DOMContentLoaded', _load);

})(window, document);
</script>
