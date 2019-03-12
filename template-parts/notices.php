<?php
/**
 * Notices template part to display notification messages 
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


$response = get_query_var( 'response' );
$messages = $response['messages'];

switch ( $response['type'] ) :
	case 'success' :
		$type_class = 'alert-success';
	break;

	case 'warning' :
		$type_class = 'alert-danger';
	break;

	case 'error' :
		if ( count( $messages ) )
			$type_class = 'alert-danger';
		else
			$type_class = 'alert-warning';
	break;

	default :
		$type_class = 'alert-info';
endswitch;
?>
<div <?php the_data_class( 'response', array('alert', $type_class) ); ?> role="alert">
<div class="container">
<ul class="list-unstyled">
<?php foreach ( $messages as $message ) : ?>
	<li><?php echo wp_kses_post( $message ); ?></li>
<?php endforeach; ?>
</ul>
</div>
</div>
