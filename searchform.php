<?php
/**
 * Search form template
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( is_singular() ) :
	$post_type = 'post';
elseif ( is_404() ) :
	$post_type = null;
else :
	$post_type = get_post_type();
endif;

$post_type_object = get_post_type_object( $post_type );

$label = '';

if ( $post_type_object && ! $post_type_object->exclude_from_search )
	$label = strtolower( $post_type_object->labels->singular_name );

/**
 * theme_search_field_label filter.
 *
 * @param string $label
 */
$label = apply_filters( 'theme_search_field_label', $label );
?>
<form id="search-form-<?php the_ID(); ?>" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" >
	<label for="search-form-input-<?php the_ID(); ?>" class="sr-only sr-only-focusable"><?php echo _x( 'Search for:', 'label', 'theme' ); ?></label>
	<div class="input-group">
		<input type="search" id="search-form-input-<?php the_ID(); ?>" class="form-control search-field" placeholder="<?php printf( _x( 'Search %s &hellip;', 'placeholder', 'theme' ), $label ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<div class="input-group-append">
			<button type="submit" class="btn btn-primary search-submit"><?php echo _x( 'Search', 'submit button', 'theme' ); ?></button>
		</div>
	</div>
<?php if ( $post_type ) : ?>
	<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>" />
<?php endif; ?>
</form>
