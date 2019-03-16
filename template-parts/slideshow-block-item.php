<?php
/**
 * Slideshow block item template part
 * 
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


?>
<div <?php the_data_class( 'slideshow_slide', 'slide' ); the_data_extras( 'slideshow_slide' ); ?>>
<?php switch ( Layer::get_subfield( 'media_type' ) ) : ?>
<?php case 'image' : ?>
	<figure class="image"<?php the_data_extras( 'slideshow_slide', 'image' ); ?>>
<?php if ( $landscape = Layer::get_subfield( 'image_landscape' ) ) : ?>
		<img class="visible-landscape" src="<?php echo esc_attr( $landscape['url'] ); ?>"<?php if ( $landscape['alt'] ) { ?> alt="<?php echo esc_attr( $landscape['alt'] ); ?>"<?php } ?>>
<?php endif; if ( Layer::get_subfield( 'enable_portrait' ) ) : ?>
<?php if ( $portrait = Layer::get_subfield( 'image_portrait' ) ) : ?>
		<img class="visible-portrait" src="<?php echo esc_attr( $portrait['url'] ); ?>"<?php if ( $portrait['alt'] ) { ?> alt="<?php echo esc_attr( $portrait['alt'] ); ?>"<?php } ?>>
<?php endif; endif; ?>
	</figure>
<?php break; case 'embed' : ?>
	<div class="embed"<?php the_data_extras( 'slideshow_slide', 'embed' ); ?>>
<?php
if ( $embed = Layer::get_subfield( 'embed' ) ) :
	echo $embed;
endif;
?>
	</div>
<?php break; case 'video' : ?>
	<video class="video"<?php the_data_extras( 'slideshow_slide', 'video' ); ?>>
<?php
if ( $fallback = Layer::get_subfield( 'video_fallback' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $fallback );
endif;
?>
<?php if ( $sources = Layer::get_subfield( 'video_sources' ) ) : foreach ( $sources as $source ) : ?>
		<source type="<?php echo esc_attr( $souce['type'] ); ?>" src="<?php echo esc_attr( $source['src'] ); ?>">
<?php endforeach; endif; ?>
<?php if ( $captions = Layer::get_subfield('video_captions' ) ) : foreach ( $captions as $caption ) : ?>
		<track kind="captions" src="<?php echo esc_attr( $caption['src'] ); ?>" srclang="<?php echo esc_attr( $caption['srclang'] ); ?>" label="<?php echo esc_attr( $caption['label'] ); ?>">
<?php endforeach; endif; ?>
	</video>
<?php break; case 'custom' : ?>
	<div class="custom"<?php the_data_extras( 'slideshow_slide', 'custom' ); ?>>
<?php
if ( $custom = Layer::get_subfield( 'custom' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $custom );
endif;
?>
	</div>
<?php break; ?>
<?php endswitch; ?>
<?php if ( $caption_text = Layer::get_subfield( 'caption_text' ) ) : ?>
	<div <?php the_data_class( 'slideshow_slide_caption', 'caption' ); the_data_extras( 'slideshow_slide', 'caption' ); ?>>
<div class="container">
<?php echo apply_filters( 'the_content', $caption_text ); ?></div>
	</div>
<?php endif; ?>
<?php if ( $cta_type = Layer::get_subfield( 'cta_type' ) ) : ?>
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
<?php
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', Layer::get_subfield( 'cta_custom' ) );
?>
<?php break; ?>
<?php endswitch; ?>
	</div>
<?php endif; ?>
</div>
