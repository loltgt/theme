<?php
/**
 * theme setup
 *
 * @package theme
 * @version 2.0
 */

namespace theme;

use \theme\Theme;


/**
 * Setup class
 */
class Setup {

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

		$this->asset_prefix = '.min';
		$this->asset_suffix = '';

		if ( SCRIPT_DEBUG ) {
			$this->asset_prefix = '';
			$this->asset_suffix = '?' . time();
		}

		add_action( 'after_setup_theme', array($this, 'initialize') );
		add_action( 'after_setup_theme', array($this, 'register_shortcodes'), 9999 );
		add_action( 'widgets_init', array($this, 'widgets_init') );

		// Loads default assets only if isn't embeded in a child theme
		if ( ! is_child_theme() ) {
			add_action( 'wp_enqueue_scripts', array($this, 'assets_queue') );
		}

		add_filter( 'theme_scandir_exclusions', array($this, 'template_scandir_exclusions') );

		if ( $this->theme->Options->get_value( 'unexpone_ver', false ) ) {
			add_action( 'init', array($this, 'frontend_unexpone_software_versions'), 0 );
			add_action( 'login_init', array($this, 'backend_unexpone_software_versions'), 0 );

			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
			remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
		}

		//TODO rewrite slug exact permalink
		if ( $this->theme->Options->get_value( 'disable_redirect_guess_permalink', false ) )
			add_filter( 'redirect_canonical', array($this, 'disable_redirect_guess_permalink') );

		// restores graphical smilies renderering option
		if ( ! $this->theme->Options->get_value( 'use_smilies', false ) ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'embed_head', 'print_emoji_detection_script' );

			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

			add_filter( 'option_use_smilies', array($this, 'option_use_smilies_override') );
		}

		Theme::register( "Setup", $this );

	}



	/**
	 * Initialize
	 */
	function initialize() {
		// Adds posts and comments RSS feed support
		add_theme_support( 'automatic-feed-links' );

		// Adds title tag support
		add_theme_support( 'title-tag' );

		// Adds thumbnail(s) support
		add_theme_support( 'post-thumbnails' );

		// Adds html5 support
		add_theme_support( 'html5',
			array('script', 'style', 'comment-list', 'comment-form', 'gallery', 'caption')
		);

		// Adds site icon support
		add_theme_support( 'site-icon' );

		// Adds post formats support
		add_theme_support( 'post-formats',
			array('audio', 'video', 'gallery', 'image', 'aside', 'chat', 'link', 'quote', 'status')
		);

		// Gutenberg related
		add_theme_support( 'editor-styles' );

		// Loads textdomain(s)
		load_theme_textdomain( 'theme', TEMPLATE_DIRECTORY . LANGUAGE_BASE_PATH );

		// Registers menu
		register_nav_menus( array(
			'primary' => __( 'Primary navigation', 'theme' ),
			'secondary' => __( 'Secondary navigation', 'theme' ),
			'footer' => __( 'Footer navigation', 'theme' )
		) );
	}


	/**
	 * Frontend assets queue
	 *
	 * @see wp_head()
	 * @see wp_footer() 
	 */
	public function assets_queue() {
		wp_enqueue_style( 'theme-style', get_stylesheet_uri() );
	}


	/**
	 * Registers the widget area
	 */
	public function widgets_init() {
		register_sidebar( array(
			'name' => __( 'Sidebar', 'theme' ),
			'id' => 'sidebar',
			'description'  => __( 'A sidebar to displays widgets.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation primary (before menu)', 'theme' ),
			'id' => 'navigation-primary-before',
			'description' => __( 'Add widgets before the primary menu navigation.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation primary (after menu)', 'theme' ),
			'id' => 'navigation-primary-after',
			'description' => __( 'Add widgets after the primary menu navigation.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation secondary (before menu)', 'theme' ),
			'id' => 'navigation-secondary-before',
			'description' => __( 'Add widgets before the secondary menu navigation.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation secondary (after menu)', 'theme' ),
			'id' => 'navigation-secondary-after',
			'description' => __( 'Add widgets after the secondary menu navigation.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Page (top)', 'theme' ),
			'id' => 'page-top',
			'description' => __( 'Add widgets in top of the page.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Page (bottom)', 'theme' ),
			'id' => 'page-bottom',
			'description' => __( 'Add widgets in bottom of the page.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Front page (top)', 'theme' ),
			'id' => 'front-page-top',
			'description' => __( 'Add widgets in top of the front page.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Front page (bottom)', 'theme' ),
			'id' => 'front-page-bottom',
			'description' => __( 'Add widgets in bottom of the front page.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Post (top)', 'theme' ),
			'id' => 'post-top',
			'description' => __( 'Add widgets in top of the post.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Post (bottom)', 'theme' ),
			'id' => 'post-bottom',
			'description' => __( 'Add widgets in bottom of the post.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Footer (before menu)', 'theme' ),
			'id' => 'footer-before',
			'description' => __( 'Add widgets before the footer menu.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Footer (after menu)', 'theme' ),
			'id' => 'footer-after',
			'description' => __( 'Add widgets after the footer menu.', 'theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );
	}


	/**
	 * Overrides get_smilies option to disable graphical rendering
	 *
	 * @see get_option()
	 * @see /wp-admin/options-writing.php
	 *
	 * @return string void
	 */
	public function option_use_smilies_override() {
		return $this->theme->Options->get_value( 'use_smiles', false );
	}


	/**
	 * Unexposes software versions from the frontend
	 */
	public function frontend_unexpone_software_versions() {
		add_filter( 'get_the_generator_html', array($this, 'unexpone_generator_version'), 9999, 2 );
		add_filter( 'get_the_generator_xhtml', array($this, 'unexpone_generator_version'), 9999, 2 );
		add_filter( 'get_the_generator_atom', array($this, 'unexpone_generator_version'), 9999, 2 );
		add_filter( 'get_the_generator_rss2', array($this, 'unexpone_generator_version'), 9999, 2 );
		add_filter( 'get_the_generator_rdf', array($this, 'unexpone_generator_version'), 9999, 2 );
		add_filter( 'get_the_generator_comment', array($this, 'unexpone_generator_version'), 9999, 2 );

		add_action( 'wp_print_styles', array($this, 'unexpone_queued_styles_version'), 9999 );
		add_action( 'wp_print_scripts', array($this, 'unexpone_queued_scripts_version'), 9999 );
	}


	/**
	 * Unexposes software versions from the backend
	 */
	public function backend_unexpone_software_versions() {
		$this->unexpone_queued_styles_version();
		$this->unexpone_queued_styles_version();
	}


	/**
	 * Unexposes software version from queued style assets
	 *
	 * @see wp_print_styles()
	 *
	 * @global object $wp_styles - WP_Styles
	 * @return object $wp_styles
	 */
	public function unexpone_queued_styles_version() {
		global $wp_styles;

		if ( ! $wp_styles )
			return $wp_styles;

		$wp_styles->default_version = false;

		foreach ( $wp_styles->registered as $handle => $args )
			$wp_styles->registered[$handle]->ver = false;

		return $wp_styles;
	}


	/**
	 * Unexposes software version from queued script assets
	 *
	 * @see wp_print_scripts()
	 *
	 * @global object $wp_scripts - WP_Scripts
	 * @return object $wp_scripts
	 */
	public function unexpone_queued_scripts_version() {
		global $wp_scripts;

		if ( ! $wp_scripts )
			return $wp_scripts;

		$wp_scripts->default_version = false;

		foreach ( $wp_scripts->registered as $handle => $args )
			$wp_scripts->registered[$handle]->ver = false;

		return $wp_scripts;
	}


	/**
	 * Unexposes software version from generator tags
	 * 
	 * @see get_the_generator()
	 *
	 * @param string $gen
	 * @param string $type
	 * @return string void
	 */
	public function unexpone_generator_version( $gen, $type ) {
		switch ( $type ) {
			case 'atom' :
				return str_replace( ' version="' . get_bloginfo_rss( 'version' ) . '"', '', $gen );
			break;

			case 'rss2' :
			case 'rdf' :
				return str_replace( '?v=' . get_bloginfo_rss( 'version' ), '', $gen );
			break;

			case 'comment' :
				return str_replace( '/' . get_bloginfo_rss( 'version' ) . '"', '', $gen );
			break;

			default :
				if ( strpos( 'WordPress', $gen ) )
					return str_replace( ' ' . get_bloginfo( 'version' ), '', $gen );
				else
					return preg_replace( '/content="(.*?)\s(\d.*?)"/', 'content="$1"', $gen );
					
		}
	}


	/**
	 * Excludes some directories from template locate
	 *
	 * @see /WP_Theme->theme_scandir_exclusions()
	 *
	 * @param array $exclusions
	 * @return array void
	 */
	public function template_scandir_exclusions() {
		return array('assets', 'inc', 'lang', 'lib', 'src', 'template-parts');
	}


	/**
	 * Prevents redirect to nearest matching URL
	 * 
	 * @see redirect_canonical()
	 * @link https://core.trac.wordpress.org/ticket/16557#comment:28
	 *
	 * @param string $redirect_url
	 * @return bool|string void|$redirect_url
	 */
	public function disable_redirect_guess_permalink( $redirect_url ) {
		if ( is_404() && ! isset( $_GET['p'] ) )
			return false;

		return $redirect_url;
	}


	/**
	 * Registers theme shortcodes
	 */
	public function register_shortcodes() {
		add_shortcode( 'date', array($this, 'date_shortcode') );
		add_shortcode( 'email', array($this, 'email_shortcode') );
	}


	/**
	 * The date shortcode, displays a formatted date 
	 *
	 * @param array $attr {
	 * 		@type string ‘class‘
	 * 		@type string ‘time‘
	 * 		@type bool ‘format‘
	 * }
	 * @return string void
	 */
	public function date_shortcode( $attr ) {
		$attr = (array) $attr;

		$atts = shortcode_atts( array(
			'class' => 'date',
			'time' => current_time( 'mysql' ),
			'format' => false
		), $attr, 'date' );

		$value = false;

		if (
			preg_match(
				'/(year)|(month)|(day)|(hours)|(minutes)|(seconds)/',
				print_r( $attr, true ), $matches
			)
		)
			$value = $matches[1];

		if ( $value ) {
			switch ( $value ) {
				case 'year' : $date_format = 'Y'; break;
				case 'month' : $date_format = 'm'; break;
				case 'day' : $date_format = 'd'; break;
				case 'hours' : $date_format = 'H'; break;
				case 'minutes' : $date_format = 'i'; break;
				case 'seconds' : $date_format = 's'; break;
			}
		} else {
			$date_format = get_option( 'date_format' );
		}

		$class = esc_attr( $atts['class'] );
		$date = mysql2date( $date_format, $atts['time'] );

		if ( $date )
			return "<time class=\"{$class}\">{$date}</time>\n";
	}


	/**
	 * The email shortcode with encryption
	 *
	 * @see \theme\Functions->email_filter()
	 *
	 * @param array $attr {
	 * 		@type string $title
	 * 		@type string $class
	 * 		@type string $crypt
	 * }
	 * @param null|string $content
	 * @return string void
	 */
	function email_shortcode( $attr, $content ) {
		if ( ! $content )
			return;

		$atts = shortcode_atts( array(
			'title' => '',
			'class' => '',
			'text' => '',
			'crypt' => true
		), $attr, 'email' );

		return call_user_func(
			array($this->theme->Functions, 'email_filter'),
			$content,
			$atts['title'],
			$atts['class'],
			$atts['text'],
			$atts['crypt']
		);
	}



}

new Setup;