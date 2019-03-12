<?php
/**
 * Default inline frontend script template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>

<script type='text/javascript'>
function owl(element) {
  var $ = jQuery;
  var element = $(element);

  if (! element.length) {
    return false;
  }

  var _setOptions = function(element, defaults) {
    var options = {};

    if (element.data('autoplay') !== undefined) {
      options.autoplay = true;
      options.autoplayHoverPause = true;

      if (element.data('autoplay-duration-time') !== undefined) {
        options.autoplayTimeout = parseInt(element.data('autoplay-duration-time'));
        options.autoplayTimeout = options.autoplayTimeout * 1000;
      }

      if (element.data('autoplay-transition-time') !== undefined) {
        options.autoplaySpeed = parseInt(element.data('autoplay-transition-time'));
      }
    }

    $.extend(true, options, defaults);

    return options;
  }

  var _load = function(element) {
    var element = $(this);
    var defaults = { loop: 1, items: 1 };
    var options = _setOptions(element, defaults);

    element.owlCarousel(options);
  }

  element.each(_load);
}

function load($) {
  if ($body.find('.hero').length) {
    $body.addClass('has-hero');
  } else {
    $body.removeClass('has-hero');
  }

  if ($body.hasClass('page-template-wizard')) {
    var alert = $('.alert.alert-info');

    if (alert.length) {
      if (/javascript/i.test($('li', alert[0]).text())) {
        $(alert[0]).remove();
      }
    }
  }

  if ('owlCarousel' in $.fn && $('.owl-carousel').length) {
    owl.apply($('.owl-carousel'), [ $ ]);
  }

  if ($().selectWoo) {
    $(window).on('load', selectWooLoadUnload, [ $ ]);
  }

  if ($('iframe').length) {
    embed_responsive($);
  }
}

function selectWooLoadUnload($) {
  if (window.isMaybeMobile) {
    $('select.country_select:visible, select.state_select:visible').each(function() {
      $(this).selectWoo('destroy');
    });
  } else {
    $('.woocommerce-fields > .nav-tabs .nav-link').on('shown.bs.tab', function(event) {
      if (! event.currentTarget.selectWoo) {
        $(document.body).trigger('country_to_state_changed');
        event.currentTarget.selectWoo = true;
      }
    });
  }
}

/**
 * @link https://stackoverflow.com/questions/8498592/extract-hostname-name-from-string
 */
function embed_responsive_provider_ratio(src) {
  var matches = src.match(/^https?\:\/\/([^\/:?#]+)(?:[\/:?#]|$)/i);

  if (matches && matches[1]) {
    var provider = matches[1].split('.');
    provider = provider[provider.length - 2];
  }

  var ratio_class = '16by9';

  switch (provider) {
    case 'spotify' :
      ratio_class = '21by9';
    break;

    case 'youtube' :
    case 'vimeo' :
    break;

    default:
      provider = '';
  }

  return 'embed-responsive-' + ratio_class + ' ' + provider;
}

function embed_responsive($) {
  var objects = $('iframe');

  $.each(objects, function() {
    var src = this.src;
    var classname = embed_responsive_provider_ratio(src);

    $(this).wrap(function() {
      var wrapper = '<div class="embed-responsive ' + classname + '">';
      wrapper += '<div class="embed-responsive-item"></div></div>';
      return wrapper;
    });
  })
}


jQuery(document).ready(function($) {
  window.$body = $(document.body);
  window.isMaybeMobile = true;

  if (matchMedia && window.matchMedia('(min-width: 1399px')) {
    window.isMaybeMobile = false;
  }

  load($);
});
</script>
