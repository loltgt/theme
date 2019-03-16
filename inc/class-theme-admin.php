<?php
/**
 * theme admin
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Theme;
use \theme\Functions;


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
		if ( is_admin() || Functions::is_login() )
			do_action( 'theme_admin', $this );

	}



	/**
	 * Admin initialising
	 *
	 * @see /wp-admin/admin-header.php
	 */
	public function admin_initialize() {
		if (
			$this->theme->Options->get_value( 'enhance_ui' ) ||
			$this->theme->Options->get_value( 'enhance_font' )
		) {
			add_action( 'admin_enqueue_scripts', array($this, 'admin_queue'), 9999 );
			add_filter( 'admin_body_class', array($this, 'admin_enhance_body_classes') );
		}

		add_action('print_media_templates', array($this, 'extend_gallery_settings') );

		if ( current_theme_supports( 'lqip' ) )
			add_filter( 'wp_generate_attachment_metadata', array($this, 'lqip_support'), 10, 2 );

		add_editor_style( 'assets/css/editor' . $this->asset_prefix . '.css' );
	}


	/**
	 * Login initialising
	 *
	 * @see login_header()
	 */
	public function login_initialize() {
		if (
			$this->theme->Options->get_value( 'enhance_ui' ) ||
			$this->theme->Options->get_value( 'enhance_font' )
		) {
			add_action( 'login_enqueue_scripts', array($this, 'login_queue'), 9999 );
			add_filter( 'login_body_class', array($this, 'login_enhance_body_classes') );
		}

		//TODO wc compatibility
		if ( $this->theme->Options->get_value( 'disable_rp' ) ) {
			add_action( 'login_form_lostpassword', array($this, 'login_disable_resetpassword_helper'), 0 );
			add_action( 'login_form_retrievepassword',  array($this, 'login_disable_resetpassword_helper'), 0 );
			add_action( 'login_form_resetpass',  array($this, 'login_disable_resetpassword_helper'), 0 );
			add_action( 'login_form_rp',  array($this, 'login_disable_resetpassword_helper'), 0 );
		}
	}


	/**
	 * Admin assets queue
	 *
	 * @global null|string $typenow
	 */
	public function admin_queue() {
		global $typenow;

		//TODO test: check < 4.9.2
		wp_enqueue_style(
			'theme-admin-style',
			get_theme_file_uri( ASSETS_BASE_PATH . '/css/admin' . $this->asset_prefix . '.css' ),
			null,
			null,
			null
		);

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'lodash' );

		if ( $typenow ) {
			wp_enqueue_script( 'wp-i18n' );
			wp_enqueue_script( 'wp-api' );
			wp_enqueue_script( 'wp-blocks' );
			wp_enqueue_script( 'wp-element' );
			wp_enqueue_script( 'wp-components' );
			wp_enqueue_script( 'wp-editor' );
		}

		add_action( 'admin_footer', array($this, 'admin_default_inline_script'), 9999 );
	}


	/**
	 * Login assets queue
	 */
	public function login_queue() {
		wp_enqueue_style(
			'theme-login-style',
			get_theme_file_uri( ASSETS_BASE_PATH . '/css/login' . $this->asset_prefix . '.css' ),
			null,
			null,
			null
		);
	}


	/**
	 * Output default inline script
	 */
	public function admin_default_inline_script() {
		get_template_part( 'template-parts/admin-script-default', substr( $this->asset_prefix, 1 ) );
	}


	/**
	 * Adds enhance classname(s) to the body class in admin space
	 *
	 * @see /wp-admin/admin-header.php
	 *
	 * @param string $classes
	 * @return string $classes
	 */
	public function admin_enhance_body_classes( $classes ) {
		if ( $this->theme->Options->get_value( 'enhance_ui' ) )
			$classes .= ' enhance-ui';

		if ( $this->theme->Options->get_value( 'enhance_font' ) )
			$classes .= ' enhance-font';

		return ltrim( $classes );
	}


	/**
	 * Adds enhance classname(s) and advanced customizations to the body class in login space
	 *
	 * @see login_header()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function login_enhance_body_classes( $classes ) {
		if ( $this->theme->Options->get_value( 'enhance_ui' ) )
			$classes[] = 'enhance-ui';

		if ( $this->theme->Options->get_value( 'enhance_font' ) )
			$classes[] = ' enhance-font';

		if ( $this->theme->Options->get_value( 'disable_rp' ) )
			$classes[] = ' no-rp';

		return $classes;
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


	/**
	 * Add custom theme settings to the gallery media manager and shortcode
	 *
	 * @link https://wordpress.org/support/topic/how-to-add-fields-to-gallery-settings/
	 *
	 * @see \theme\Functions\gallery_shortcode()
	 * @see /wp-includes/media-template.php
	 */
	public function extend_gallery_settings() {
		$template = "\t\t<script type=\"text/html\" id=\"tmpl-theme__gallery-settings\">\n";
		$template .= "\t\t\t<label class=\"setting\">\n\t\t\t\t<span>%s</span>\n";
		$template .= "\t\t\t\t<input type=\"checkbox\" data-setting=\"slider\" />\n\t\t\t</label>\n\t\t</script>\n";

		$template = sprintf( $template, __( 'Enable slider', 'theme' ) );

		echo $template;
	}


	/**
	 * Low Quality Image Placeholder support
	 *
	 * @link https://gist.github.com/leolweb/1fd7a58722c4e4351073
	 * @license MIT License
	 *
	 * @see wp_generate_attachment_metadata()
	 *
	 * @global array $_wp_additional_image_sizes
	 * @param array $metadata
	 * @param array $attachment_id
	 * @return array $metadata
	 */
	public function lqip_support( $metadata, $attachment_id ) {
		global $_wp_additional_image_sizes;

		if ( isset( $_wp_theme_features['lqip'][0] ) ) {
			$image_sizes = $_wp_theme_features['lqip'][0];
		} else {
			$image_sizes = get_intermediate_image_sizes();

			/**
			 * Filters the image sizes when uploading an image
			 *
			 * @see wp_generate_attachment_metadata()
			 *
			 * @param array $image_sizes
			 */
			$image_sizes = apply_filters( 'intermediate_image_sizes_advanced', $image_sizes );
		}

		$imagick_implementation = extension_loaded( 'imagick' ) ? true : false;
		$file = get_attached_file( $attachment_id );
		$quality = apply_filters( 'temp_lqip_quality', array( 10, 9 ) );
		$ncolors = apply_filters( 'temp_lqip_ncolors', 32 );
		$mime_type = get_post_mime_type( $attachment_id );

		if (
			! preg_match( '!^image/!', $mime_type ) ||
			! file_is_displayable_image( $file )
		)
			return $metadata;

		$path_parts = pathinfo( $file );
		$src_path = trailingslashit( $path_parts['dirname'] );

		foreach ( $image_sizes as $size ) {
			if ( ! isset( $metadata['sizes'][$size] ) )
				continue;

			if ( isset( $_wp_additional_image_sizes[$size]['width'] ) )
				$width = intval( $_wp_additional_image_sizes[$size]['width'] );
			else
				$width = get_option( "{$size}_size_w" );

			if ( isset( $_wp_additional_image_sizes[$size]['height'] ) )
				$height = intval( $_wp_additional_image_sizes[$size]['height'] );
			else
				$height = get_option( "{$size}_size_h" );

			if ( isset( $_wp_additional_image_sizes[$size]['crop'] ) )
				$crop = intval( $_wp_additional_image_sizes[$size]['crop'] );
			else
				$crop = get_option( "{$size}_crop" );

			$new_size = $size . '-lqip';
			$filename = str_replace(
				'.' . $path_parts['extension'],
				'-lqip.' . $path_parts['extension'],
				$metadata['sizes'][$size]['file']
			);

			$src_image = $src_path . $metadata['sizes'][$size]['file'];

			$image = wp_get_image_editor( $src_image );
			$image->resize( $width, $height, $crop );

			if ( $imagick_implementation ) {
				$image->set_quality( $quality[0] );
				$image->save( $src_path . $filename );
			} else {
				$headers = headers_list();

				ob_start();
				$image->stream();
				$src = ob_get_contents();
				ob_end_clean();

				header_remove();

				foreach ( $headers as $header )
					header( $header );

				if ( empty( $src ) ) {
					$image->set_quality( $quality[0] );
					$image->save( $src_path . $filename );
				} else {
					$src = imagecreatefromstring( $src );

					if ( $mime_type === 'image/jpeg' ) {
						imagejpeg( $src, $src_path . $filename, $quality[0] );
					} else if ( $mime_type === 'image/png' || $mime_type === 'image/gif' ) {
						$src_size = $image->get_size();
						$dest = imagecreatetruecolor( $src_size['width'], $src_size['height'] );
						$bg = imagecolorallocate( $dest, 255, 255, 255 );
						imagefill( $dest, 0, 0, $bg );
						imagecopyresampled(
							$dest,
							$src,
							0,
							0,
							0,
							0,
							$src_size['width'],
							$src_size['height'],
							$src_size['width'],
							$src_size['height']
						);
						imagetruecolortopalette( $dest, false, $ncolors );

						if ( $mime_type === 'image/png' )
							imagepng( $dest, $src_path . $filename, $quality[1] );

						if ( $mime_type === 'image/gif' )
							imagegif( $dest, $src_path . $filename, $quality[1] );

						imagedestroy( $new );
					}

					imagedestroy( $src );
				}

			}

			if ( ! is_wp_error( $image ) ) {
				$metadata['sizes'][$new_size] = $metadata['sizes'][$size];
				$metadata['sizes'][$new_size]['file'] = $filename;
			}
		}

		return $metadata;
	}


}

new Admin;