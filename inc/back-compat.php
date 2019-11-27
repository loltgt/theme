<?php
/**
 * backward compatibility
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


/**
 * Prevents switching from an old versions of WordPress or PHP
 */
function switch_theme() {
	switch_theme( WP_DEFAULT_THEME );

	unset( $_GET['activated'] );

	add_action( 'admin_notices', '\theme\upgrade_notice' );
}
add_action( 'after_switch_theme', '\theme\switch_theme' );


/**
 * Adds a message for unsuccessful theme switch
 *
 * @global string $wp_version
 */
function upgrade_notice() {
	$message = sprintf(
		__( 'This theme requires at least WordPress 4.8 (current version %s) and PHP 7.x, please upgrade and try again.', 'theme' ),
		$GLOBALS['wp_version']
	);

	printf( '<div class="error"><p>%s</p></div>', $message );
}
