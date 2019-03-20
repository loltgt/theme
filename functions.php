<?php
/**
 * theme bootstrap
 *
 * My custom theme.
 *
 * @package theme
 * @version 1.0
 * @license GNU General Public License v2 or later
 * @copyright Copyright (c) 2019 Leonardo Laureti
 */

namespace theme;

use \Exception;



/* Set default constants */

define( 'TEMPLATE_DIRECTORY', get_template_directory() );
define( 'TEMPLATE_DIRECTORY_URI', get_template_directory_uri() );

define( 'STYLESHEET_DIRECTORY', get_stylesheet_directory() );
define( 'STYLESHEET_DIRECTORY_URI', get_stylesheet_directory_uri() );

define( 'INCLUDES_BASE_PATH', '/inc' );
define( 'LIBRARY_BASE_PATH', '/lib' );
define( 'LANGUAGE_BASE_PATH', '/lang' );
define( 'ASSETS_BASE_PATH', '/assets' );


/* Verify requirements */

if (
	version_compare( $GLOBALS['wp_version'], '4.8', '<' ) ||
	version_compare( PHP_VERSION, '7.0.0', '<' )
) {
	require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/back-compat.php';

	return;
}



/**
 * Theme main class
 */
class Theme {

	// @type null|object $_instance
	private static $_instance = null;

	// @type array $_context
	private $_context = array();

	// @type array $_storage
	private $_storage = array();


	/**
	 * Function __construct
	 */
	public function __construct() {
		if ( defined( "THEME_{COOKIEHASH}" ) )
			throw new Exception();

		define( "THEME_{COOKIEHASH}", spl_object_hash( $this ) );

		self::$_instance = $this;
	}

	/**
	 * Disables cloning method
	 *
	 * @access public
	 */
	public function __clone() {
		throw new Exception();
	}

	/**
	 * Disables waking-up method
	 *
	 * @access public
	 */
	public function __wakeup() {
		throw new Exception();
	}


	/**
	 * Retrieves the instance
	 *
	 * @access public
	 * @static
	 *
	 * @return object $_instance - \theme\Theme
	 */
	public static function instance() {
		return self::$_instance;
	}

	/**
	 * Gets context -or- by context name
	 *
	 * @access public
	 * @static
	 *
	 * @param string|null $context_name
	 * @return string|array void
	 */
	public static function context( $context_name = null ) {
		$context = array();

		foreach ( self::$_instance->_context as $_class_name => $_context_name )
			$context[$_context_name][] = $_class_name;

		if ( $context_name && isset( $context[$context_name] ) ) {
			if ( isset( $context[$context_name][1] ) )
				return $context[$context_name];
			else
				return $context[$context_name][0];
		}

		return $context;
	}

	/**
	 * Registers a class
	 *
	 * @access public
	 * @static
	 *
	 * @param string $class_name
	 * @param object $instance
	 * @param int|string $context
	 */
	public static function register( $class_name, $instance, $context = 0 ) {
		if ( ! is_int( $context ) && ! is_string( $context ) )
			throw new Exception( '\theme\Theme::register() : Bad \'context\' argument.' );

		if ( ! class_exists( "\\" . __NAMESPACE__ . "\\{$class_name}" ) )
			throw new Exception( '\theme\Theme::register() : Class not exists.' );

		self::$_instance->_context[$class_name] = $context;
		self::$_instance->{$class_name} = $instance;
	}

	/**
	 * De-registers a class
	 *
	 * @access public
	 * @static
	 *
	 * @param string $class_name
	 */
	public static function deregister( $class_name ) {
		unset( self::$_instance->_context[$class_name] );
		unset( self::$_instance->{$class_name} );
	}

	/**
	 * Checks if a class is registered
	 *
	 * @access public
	 * @static
	 *
	 * @param string $class_name
	 */
	public static function is_registered( $class_name ) {
		return isset( self::$_instance->{$class_name} );
	}

	/**
	 * Utility for debug
	 *
	 * @access public
	 * @static
	 */
	public static function debug() {
		var_dump( self::$_instance );
	}

	/**
	 * Utility storage getter
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed void
	 */
	public static function get( $key, $default = null ) {
		if ( isset( self::$_instance->_storage[$key] ) )
			return self::$_instance->_storage[$key];

		return $default;
	}

	/**
	 * Utility storage setter
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set( $key, $value = null ) {
		if ( ! is_string( $key ) )
			throw new Exception();

		self::$_instance->_storage[$key] = $value;
	}

	/**
	 * Unsets by storage key
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 */
	public static function unset( $key ) {
		unset( self::$_instance->_storage[$key] );
	}

	/**
	 * Checks for storage key
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @return bool void
	 */
	public static function isset( $key ) {
		return isset( self::$_instance->_storage[$key] );
	}

	/**
	 * Checks if storage key is empty
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param bool void
	 */
	public static function empty( $key ) {
		return empty( self::$_instance->_storage[$key] );
	}

}

new Theme;



/* Load includes */

require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-options.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-functions.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-layer.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-setup.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-admin.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-customizer.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-template.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-shop-wc.php';

require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/template-functions.php';
require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/template-tags.php';



/* Load 3th party libraries */

require_once TEMPLATE_DIRECTORY . LIBRARY_BASE_PATH . '/wp-bootstrap-navwalker/class-wp-bootstrap-navwalker.php';
require_once TEMPLATE_DIRECTORY . LIBRARY_BASE_PATH . '/wp-bootstrap-comment-walker/class-wp-bootstrap-comment-walker.php';


//add_action( 'wp_footer', array(Theme::instance(), 'debug'), 9999 );
