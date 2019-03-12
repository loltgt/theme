<?php
/**
 * Default inline frontend script minified template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>

<script type='text/javascript'>
function owl(e){var o=e(this),t={};void 0!==o.attr("data-autoplay")&&(t.autoplay=!0,t.autoplayHoverPause=!0),e.extend(!0,t,{loop:1,items:1}),o.owlCarousel(t)}function load(e){if($body.find(".hero").length?$body.addClass("has-hero"):$body.removeClass("has-hero"),$body.hasClass("page-template-wizard")){var o=e(".alert.alert-info");o.length&&/javascript/i.test(e("li",o[0]).text())&&e(o[0]).remove()}"owlCarousel"in e.fn&&e(".owl-carousel").length&&owl.apply(e(".owl-carousel"),[e]),e().selectWoo&&e(window).on("load",selectWooLoadUnload,[e]),e("iframe").length&&embed_responsive(e)}function selectWooLoadUnload(e){window.isMaybeMobile?e("select.country_select:visible, select.state_select:visible").each(function(){e(this).selectWoo("destroy")}):e(".woocommerce-fields > .nav-tabs .nav-link").on("shown.bs.tab",function(o){o.currentTarget.selectWoo||(e(document.body).trigger("country_to_state_changed"),o.currentTarget.selectWoo=!0)})}function embed_responsive_provider_ratio(e){var o=e.match(/^https?\:\/\/([^\/:?#]+)(?:[\/:?#]|$)/i);if(o&&o[1]){var t=o[1].split(".");t=t[t.length-2]}var a="16by9";switch(t){case"spotify":a="21by9";break;case"youtube":case"vimeo":break;default:t=""}return"embed-responsive-"+a+" "+t}function embed_responsive(e){var o=e("iframe");e.each(o,function(){var o=embed_responsive_provider_ratio(this.src);e(this).wrap(function(){var e='<div class="embed-responsive '+o+'">';return e+='<div class="embed-responsive-item"></div></div>'})})}jQuery(document).ready(function(e){window.$body=e(document.body),window.isMaybeMobile=!0,matchMedia&&window.matchMedia("(min-width: 1399px")&&(window.isMaybeMobile=!1),load(e)});
</script>
