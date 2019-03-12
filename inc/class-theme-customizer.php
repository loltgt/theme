<?php
/**
 * theme customizer
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Theme;


/**
 * Customizer class
 */
class Customizer {

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

		add_action( 'customize_register', array($this, 'register') );
		add_action( 'customize_preview_init', array($this, 'customize_preview_script') );

		Theme::register( "Customizer", $this );

	}



	/**
	 * Registers control and settings for theme customizer
	 *
	 * @param object $wp_customize - WP_Customize_Manager
	 */
	public function register( $wp_customize ) {

		// Adds custom brand name option

		$wp_customize->add_setting( 'theme_settings[brand_name]', array(
			'default' => get_option( 'theme_settings[brand_name]' ),
			'type' => 'option',
			'capability' => 'manage_options',
			'transport' => 'postMessage'
		) );

		$wp_customize->add_control( 'theme_settings[brand_name]', array(
			'label' => __( 'Brand Name', 'theme' ),
			'section' => 'title_tagline'
		) );

		$wp_customize->selective_refresh->add_partial( 'theme_settings[brand_name]', array(
			'settings' => array('theme_settings[brand_name]'),
			'selector' => '.custom-logo-link',
			'render_callback' => array($this, 'selective_refresh_partial_brand'),
			'container_inclusive' => true,
		) );


		// Adds layout settings

		$wp_customize->add_section( 'view_settings', array(
			'title' => __( 'Layout settings', 'theme' ),
			'priority' => 130
		) );

		$wp_customize->add_setting( 'page_dispose', array(
			'default' => 'ltr',
			'sanitize_callback' => array($this, 'sanitize_page_dispose')
		) );

		$wp_customize->add_control( 'page_dispose', array(
			'label' => __( 'Page disposition', 'theme' ),
			'priority' => 100,
			'section' => 'view_settings',
			'type' => 'radio',
			'description' => __( 'Select the disposition of the content.', 'theme' ),
			'choices' => array(
				'ltr' => __( 'Left to right', 'theme' ),
				'rtl' => __( 'Right to left', 'theme' ),
			),
			'active_callback' => array($this, 'is_view_with_page_dispose_option')
		) );

	}


	/**
	 * Loads the customizer script
	 */
	public function customize_preview_script() {
		add_action( 'wp_footer', array($this, 'default_inline_script') );
	}


	/**
	 * Output customizer inline script
	 */
	public function default_inline_script() {
		get_template_part(
			'template-parts/customizer-script-default',
			substr( $this->asset_prefix, 1 )
		);
	}


	/**
	 * Sanitizes page disposition options
	 *
	 * @param string $input
	 * @return string $input|void
	 */
	public function sanitize_page_dispose( $input ) {
		$valid = array(
			'ltr' => __( 'Left to right', 'theme' ),
			'rtl' => __( 'Right to left', 'theme' )
		);

		if ( array_key_exists( $input, $valid ) )
			return $input;

		return '';
	}


	/**
	 * (Re-)renderizes the site brand for selective refresh
	 */
	public function selective_refresh_partial_brand() {
		return get_custom_logo();
	}


	/**
	 * Conditional for pages with disposition option
	 *
	 * @return bool void
	 */
	public function is_view_with_page_dispose_option() {
		return (
			is_page_template( 'page-templates/page-sidebar.php' ) &&
			is_active_sidebar( 'sidebar' )
		);
	}


}

new Customizer;