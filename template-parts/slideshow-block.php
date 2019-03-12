<?php
/**
 * Slideshow block template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


/**
 * theme_load_slideshow_assets hook.
 *
 * @hooked /theme/Setup->load_slideshow_assets - 10
 */
do_action( 'theme_load_slideshow_assets' );

?>
<?php
while ( Layer::have_rows( 'slides' ) ) : Layer::the_row();
	get_template_part( 'template-parts/slideshow-block', 'item' );
endwhile;

wp_reset_postdata();
?>
<?php if ( $caption_text = Layer::get_subfield( 'caption_text' ) ) : ?>
	<div <?php the_data_class( 'slideshow_slide_caption', 'caption' ); the_data_extras( 'slideshow_slide', 'caption' ); ?>>
<div class="container">
<?php echo $caption_text; ?></div>
	</div>
<?php endif; ?>
<?php if ( $cta_type = Layer::get_subfield( 'cta_type', false ) ) : ?>
	<div <?php the_data_class( 'slideshow_slide_cta', 'cta' ); the_data_extras( 'slideshow_slide', 'cta' ); ?>>
<?php switch ( $cta_type ) : ?>
<?php case 'link' : ?>
		<a <?php the_data_class( 'slideshow_slide_caption_link', 'cta-link' ); ?> href="<?php echo esc_attr( esc_url( Layer::get_subfield( 'cta_link' ) ) ); ?>" title="<?php echo esc_attr( Layer::get_subfield( 'cta_title' ) ); ?>"><?php echo Layer::get_subfield( 'cta_label' ); ?></a>
<?php break; case 'button' : ?>
<?php if ( $cta_label = Layer::get_subfield( 'cta_label' ) ) : ?>
		<p <?php the_data_class( 'slideshow_slide_caption_label', 'cta-label' ); ?>><?php echo $cta_label; ?></p>
<?php endif; ?>
		<a <?php the_data_class( 'slideshow_slide_caption_button', 'cta-button' ); ?> href="<?php echo esc_attr( esc_url( Layer::get_subfield( 'cta_link' ) ) ); ?>" title="<?php echo esc_attr( Layer::get_subfield( 'cta_title' ) ); ?>"><?php echo Layer::get_subfield( 'cta_button' ); ?></a>
<?php break; case 'custom' : ?>
<?php echo Layer::get_subfield( 'cta_custom' ); ?>
<?php break; ?>
<?php endswitch; ?>
	</div>
<?php
endif;