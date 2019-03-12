<?php
/**
 * Modal - Page template part
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


?>
<section id="page-<?php the_ID(); ?>" <?php post_class(); the_data_extras( 'page' ); ?>>
<?php if ( true === strpos( get_the_content(), '</h' ) ) : ?>
<header class="modal-header">
<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
</header>
<?php endif; ?>
<div class="modal-body">
<?php the_content(); ?>
<?php if ( Layer::have_rows( 'layers' ) ) : ?>
<?php while ( Layer::have_rows( 'layers' ) ) : Layer::the_row(); ?>
<div <?php the_data_class( 'page_layer', 'page-content-layer' ); the_data_extras( 'page_layer' ); ?>>
<?php if ( Layer::have_rows( 'section' ) ) : while ( Layer::have_rows( 'section' ) ) : Layer::the_row(); ?>
<div <?php the_data_class( 'page_section', 'page-content-section' ); ?>>
<?php get_template_part( 'template-parts/section', Layer::get_field_name() ); ?>
</div>
<?php endwhile; endif; ?>
</div>
<?php endwhile; endif; ?>
</div>
</section>
