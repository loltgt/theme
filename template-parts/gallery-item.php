<?php
/**
 * Gallery item template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


$atts = get_query_var( 'shortcode_atts' );
$id = get_query_var( 'shortcode_id' );

$attr = array('gallery' => true, 'class' => 'figure-img img-fluid');
$instance = $atts['instance'];
$attachment = $atts['attachments'][$id];
$image_meta = wp_get_attachment_metadata( $id );
$caption = false;

if ( is_singular() && trim( $attachment->post_excerpt ) ) :
	$caption = wptexturize( $attachment->post_excerpt );
	$attr['aria-describedby'] = "gallery-{$instance}-{$id}";
endif;

if ( isset( $image_meta['height'], $image_meta['width'] ) )
	$attr['class'] .= ( $image_meta['height'] > $image_meta['width'] ) ? ' portrait' : ' landscape';
?>
<figure <?php the_data_class( 'gallery_item', array('figure', 'gallery-item') ); the_data_extras( 'gallery_item' ); ?>>
<?php
if ( $atts['link'] && 'file' === $atts['link'] ) :
	//TODO img remove width height => post_thumbnail_html_sizes()
	//the_gallery_item_file( $id, $atts['size'], $attr );
	echo wp_get_attachment_link( $id, $atts['size'], '', '', '', $attr );
elseif ( $atts['link'] && 'none' === $atts['link'] ) :
	//TODO img remove width height => post_thumbnail_html_sizes()
	//the_gallery_item_image( $id, $atts['size'], $attr );
	echo wp_get_attachment_image( $id, $atts['size'], '', $attr );
else :
	//TODO img remove width height => post_thumbnail_html_sizes()
	//the_gallery_item_link( $id, $atts['size'], $attr );
	echo wp_get_attachment_link( $id, $atts['size'], true, '', '', $attr );
endif;
?>
<?php if ( $caption ) : ?>
<figcaption class="figure-caption wp-caption-text gallery-caption">
<?php echo $caption; ?>
</figcaption>
<?php endif; ?>
</figure>
