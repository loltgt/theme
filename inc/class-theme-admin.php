<?php
/**
 * theme admin
 *
 * @package theme
 * @version 2.0
 */

namespace theme;

use \theme\Theme;


/**
 * Admin class
 */
class Admin {

	// @type object $theme - \theme\Theme
	private $theme;

	// @type string $prefix
	public $asset_prefix;

	// @type string $suffix
	public $asset_suffix;


	/**
	 * Function __construct
	 */
	function __construct() {

		$this->theme = Theme::instance();

		$this->asset_prefix = $this->theme->Setup->asset_prefix;
		$this->asset_suffix = $this->theme->Setup->asset_suffix;

		add_action( 'admin_init', array($this, 'admin_initialize') );
		add_action( 'login_init', array($this, 'login_initialize') );

		/**
		 * Hook to filter \theme\Admin outside here
		 *
		 * @param object $this
		 */
		if ( is_admin() || $this->theme->Functions::is_login() )
			do_action( 'theme_admin', $this );

	}



	/**
	 * Admin initialising
	 *
	 * @see /wp-admin/admin-header.php
	 */
	public function admin_initialize() {
	}


	/**
	 * Login initialising
	 *
	 * @see login_header()
	 */
	public function login_initialize() {
		if ( $this->theme->Options->get_value( 'disable_rp' ) ) {
			add_action( 'login_form_lostpassword', array($this, 'login_disable_resetpassword_helper'), 0 );
			add_action( 'login_form_retrievepassword',  array($this, 'login_disable_resetpassword_helper'), 0 );
			add_action( 'login_form_resetpass',  array($this, 'login_disable_resetpassword_helper'), 0 );
			add_action( 'login_form_rp',  array($this, 'login_disable_resetpassword_helper'), 0 );
		}
	}


	/**
	 * An helper function to disable password reset, 
	 * redirects to the login page and exit
	 *
	 * @see /wp-login.php
	 */
	public function login_disable_resetpassword_helper() {
		wp_redirect( wp_login_url() );

		exit;
	}


}

new Admin;