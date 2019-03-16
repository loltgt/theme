<?php
/**
 * Hero template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>
<section id="hero-<?php the_data_ID(); ?>" <?php the_data_class( 'hero', 'hero' ); ?>>
<h6 class="sr-only sr-only-focusable"><?php echo _x( 'Slideshow hero', 'slideshow accessibility', 'theme' ); ?></h6>
<?php get_template_part( 'template-parts/slideshow' ); ?>
</section>
