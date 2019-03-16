<?php
/**
 * Form template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$enable_tpp = Layer::get_field( 'enable_form_tpp' );

if ( ! $enable_tpp && ! Layer::get_field( 'fieldset' ) )
	return;

$form_id = get_data_ID( 'form', 'expone' );
?>
<form id="form-<?php the_data_ID( 'form' ); ?>" <?php the_data_class( 'form', 'form' ); the_data_extras( 'form' ); ?>>
<?php
/**
 * theme_before_form hook.
 *
 * @hooked \theme\Functions->before_form - 10
 *
 * @param string|int $form_id
 */
do_action( 'theme_before_form', $form_id );
?>
<?php
if ( $begin = Layer::get_field( 'form_start' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $begin );
endif;
?>
<?php get_template_part( 'template-parts/form', ( $enable_tpp ? 'plugin' : 'block' ) ); ?>
<?php
if ( $end = Layer::get_field( 'form_end' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $end );
endif;
?>
<?php
/**
 * theme_after_form hook.
 *
 * @hooked \theme\Functions->wizard_after_form - 10
 * @hooked \theme\Functions->after_form - 10
 *
 * @param string|int $form_id
 */
do_action( 'theme_after_form', $form_id );
?>
</form>
