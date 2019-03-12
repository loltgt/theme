<?php
/**
 * Form field block template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$i = Layer::get_current_index();

$field = Layer::get_field_name();

/**
 * Filter for form type
 *
 * @param string void
 */
$field_type = apply_filters( 'theme_form_field_type', Layer::get_subfield( 'type' ) );

/**
 * Filter for form label
 *
 * @param string void
 */
$field_label = apply_filters( 'theme_form_field_label', Layer::get_subfield( 'label' ) );

/**
 * Filter for form default value
 *
 * @param string void
 */
$field_default = apply_filters( 'theme_form_field_default', Layer::get_subfield( 'default' ) );


switch ( $field ) :
	case 'input' :
?>
		<label for="row-<?php the_data_ID( 'form_field', $i ); ?>" <?php the_data_class( 'form_field_label' ); ?>><?php echo $field_label; ?></label>
		<input id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" type="<?php echo esc_attr( $field_type ); ?>"<?php the_data_extras( 'form_field', $field, array('class' => 'form-control'), array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?> />
<?php
		the_form_field_validation();
	break;

	case 'textarea' :
?>
		<label for="row-<?php the_data_ID( 'form_field', $i ); ?>" <?php the_data_class( 'form_field_label' ); ?>><?php echo $field_label; ?></label>
		<textarea id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" <?php the_data_extras( 'form_field', $field, array('class' => 'form-control'), array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?>><?php echo $field_default; ?></textarea>
<?php
		the_form_field_validation();
	break;

	case 'select' :
?>
		<label for="row-<?php the_data_ID( 'form_field', $i ); ?>" <?php the_data_class( 'form_field_label' ); ?>><?php echo $field_label; ?></label>
		<select id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" <?php the_data_extras( 'form_field', $field, array('class' => 'form-control'), array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?>>
<?php foreach ( Layer::get_subfield( 'options' ) as $option ) : ?>
			<option<?php the_data_extras( 'form_field_select_option', null, null, array($i, $option) ); ?>><?php echo $option['value']; ?></option>
<?php endforeach; ?>
		</select>
<?php
		the_form_field_validation();
	break;

	case 'checkbox' :
?>
		
		<input id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" type="<?php echo esc_attr( $field_type ); ?>"<?php the_data_extras( 'form_field', $field, array('class' => 'form-check-input'), array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?> /> 
		<label for="row-<?php the_data_ID( 'form_field', $i ); ?>" <?php the_data_class( 'form_field_label', 'form-check-label' ); ?>><?php echo $field_label; ?></label>
<?php
		the_form_field_validation();
	break;

	case 'button' :
		if ( Layer::get_subfield( 'element' ) == 'button' ) :
?>
		<button id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" type="<?php echo esc_attr( $field_type ); ?>"<?php the_data_extras( 'form_field', $field, array('class' => 'btn'), array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?>><?php echo $field_label; ?></button>
<?php else : ?>
		<input id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" type="<?php echo esc_attr( $field_type ); ?>"<?php the_data_extras( 'form_field', $field, array('class' => 'btn'), array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?> />
<?php
		endif;
	break;

	case 'hidden' :
?>
		<input id="row-<?php the_data_ID( 'form_field', $i ); ?>" name="row<?php the_data_ID( 'form_field', array($i, true) ); ?>" type="hidden"<?php the_data_extras( 'form_field', $field, null, array( 'type' => $field_type, 'label' => $field_label, 'default' => $field_default ) ); ?> />
<?php
	break;
endswitch;
