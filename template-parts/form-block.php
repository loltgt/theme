<?php
/**
 * Form block template part
 *
 * //TODO refactoring hooks & filters
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


while ( Layer::have_rows( 'fieldset' ) ) : Layer::the_row();
?>
<fieldset id="fieldset-<?php the_data_ID( 'form_fieldset' ); ?>"<?php the_data_extras( 'form_fieldset', '', array('class' => 'form-group') ); ?>>
<?php
if ( $legend = Layer::get_subfield( 'legend' ) ) :
/**
 * Filter for form legend
 *
 * @param string $legend
 */
?>
<legend><?php echo apply_filters( 'theme_form_legend', $legend ); ?></legend>
<?php endif; ?>
<?php
if ( $begin = Layer::get_subfield( 'fieldset_start' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $begin );
endif;
?>
<?php if ( Layer::have_rows( 'row' ) ) : while ( Layer::have_rows( 'row' ) ) : Layer::the_row(); ?>
<div <?php the_data_class( 'form_row', 'form-group' ); ?>>
<?php
if ( $before = Layer::get_subfield( 'before' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $before );
endif;
?>
<?php get_template_part( 'template-parts/form-field-block', Layer::get_field_name() ); ?>
<?php
if ( $after = Layer::get_subfield( 'after' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $after );
endif;
?>
</div>
<?php endwhile; endif; ?>
<?php
if ( $end = Layer::get_subfield( 'fieldset_end' ) ) :
	/**
	 * Filters the post content,
	 * from \the_content()
	 *
	 * @param string $content
	 */
	echo apply_filters( 'the_content', $end );
endif;
?>
</fieldset>
<?php
endwhile;