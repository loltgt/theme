<?php
/**
 * Form shortcode block template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$atts = get_query_var( 'shortcode_atts' );
$post = get_post( $atts['post'] );

setup_postdata( $post );

$enable_tpp = Layer::get_field( 'enable_form_tpp' );

if ( ! $enable_tpp && ! Layer::get_field( 'fieldset' ) )
	return;

$form_id = get_data_ID( 'form', 'shortcode' );
?>
<form id="form-<?php the_data_ID( 'form', 'shortcode' ); ?>" <?php the_data_class( 'form', 'form' ); the_data_extras( 'form' ); ?>>
<?php
/**
 * theme_before_form hook.
 *
 * @hooked /theme/Functions->before_form - 10
 *
 * @param string|int $form_id
 */
do_action( 'theme_before_form', $form_id );
?>
<?php get_template_part( 'template-parts/form', ( $enable_tpp ? 'plugin' : 'block' ) ); ?>
<?php
/**
 * theme_after_form hook.
 *
 * @hooked /theme/Functions->wizard_after_form - 10
 * @hooked /theme/Functions->after_form - 10
 *
 * @param string|int $form_id
 */
do_action( 'theme_after_form', $form_id );
?>
</form>

<?php
wp_reset_postdata();