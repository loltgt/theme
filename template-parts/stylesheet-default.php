<?php
/**
 * Default inline frontend stylesheet template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>

<style type='text/css'>
#container { margin-bottom: 3rem; }
#main, #comments { margin-top: 2rem; }
#header { border-bottom: 1px solid rgba(0, 0, 0, 0.125); }
#footer { padding: 2rem 0; background-color: #f7f7f7; }
.page-header { margin-bottom: 2rem; }
.page-links { margin-top: 1rem; }
.post-header { position: relative; margin-bottom: 1rem; }
.post-navigation { position: absolute; top: 0.5rem; right: 0; }
.post-navigation a, .post-navigation .disabled { text-decoration: none; }
.post-footer { margin-top: 3rem; }
.woocommerce-products-header { margin-bottom: 1rem; }
.woocommerce-mini-cart { margin: 0; padding-left: 0; list-style: none; }
.card-click-target { position: absolute; top: 0; left: 0; bottom: 0; right: 0; z-index: 1; }
.entry.card .card-body a, .entry.card .entry-object { position: relative; z-index: 2; }
.list-entry.card, .search-entry.card { margin-bottom: 1rem; }
.entry.card .card-body .edit-link { float: right; }
.product-entry.card .onsale { position: absolute; padding: 0.4rem 0.6rem; z-index: 0; }
.product-entry.card .card-body { position: relative; }
.product-entry.card .card-body .edit-link { position: absolute; top: -3rem; right: 1rem; }
.entry .embed-responsive.spotify:before { padding-top: 22.5%; }

@media (min-width: 992px) {
  #container { min-height: 60vmin; }
  .post-navigation a, .post-navigation .disabled { font-size: 2rem; }
  .post-tags .post-tags-label { float: left; padding: 0.4rem 0; }
  .card.product-entry .button { float: right; }
}
@media (min-width: 1200px) {
  #container { min-height: 70vmin; }
}
</style>
