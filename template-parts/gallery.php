<?php
/**
 * Gallery template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


$atts = get_query_var( 'shortcode_atts' );

if ( ! $atts )
	return;

$gallery_id = $atts['id'];
?>

<div id="gallery-<?php the_data_ID( 'gallery' ); ?>" <?php the_data_class( 'gallery', 'gallery' ); the_data_extras( 'gallery' ); ?>>
<?php
/**
 * theme_before_gallery hook.
 *
 * @param int $gallery_id
 */
do_action( 'theme_before_gallery', $gallery_id );
?>
<?php
foreach ( $atts['attachments'] as $id => $attachment ) : set_query_var( 'shortcode_id', $id );
	get_template_part( 'template-parts/gallery', 'item' );
endforeach;
?>
<?php
/**
 * theme_after_gallery hook.
 *
 * @param int $gallery_id
 */
do_action( 'theme_after_gallery', $gallery_id );
?>
</div>
<?php
wp_reset_postdata();