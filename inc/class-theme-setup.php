<?php
/**
 * theme setup
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Theme;

use \WP_Query;


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

		if ( SCRIPT_DEBUG || ( defined( 'THEME_DEBUG' ) && THEME_DEBUG ) ) {
			$this->asset_prefix = '';
			$this->asset_suffix = '?' . time();
		}

		add_action( 'after_setup_theme', array($this, 'initialize') );
		add_action( 'after_setup_theme', array($this, 'register_widgets'), 999 );
		add_action( 'after_setup_theme', array($this, 'register_shortcodes'), 9999 );
		add_action( 'wp_head', array($this, 'metas'), 0 );
		add_action( 'widgets_init', array($this, 'widgets_init') );

		// Loads Bootstrap default assets only if isn't embeded in a child theme
		if ( ! is_child_theme() ) {
			add_action( 'wp_enqueue_scripts', array($this, 'styles_queue') );
			add_action( 'wp_enqueue_scripts', array($this, 'scripts_queue') );
			add_action( 'wp_enqueue_scripts', array($this, 'assets_queue') );
		}

		add_filter( 'theme_scandir_exclusions', array($this, 'template_scandir_exclusions') );

		add_action( 'theme_load_slideshow_assets', array($this, 'load_slideshow_assets') );

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
			// TODO check
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

			add_filter( 'option_use_smilies', array($this, 'option_use_smilies_override') );

			//TODO remove filters (convert_smilies)
			//TODO test comments emoji
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
		add_theme_support( 'html5', array('comment-list', 'comment-form', 'gallery', 'caption') );

		// Adds post formats support
		add_theme_support( 'post-formats' );

		// Adds site icon support
		add_theme_support( 'site-icon' );

		// Adds logo support
		add_theme_support( 'custom-logo', array(
			'width' => 250,
			'height' => 250,
			'flex-width' => true
		) );

		// Adds customizer selective refresh widgets support
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Gutenberg related
		add_theme_support( 'editor-styles' );

		// Loads textdomain(s)
		load_theme_textdomain( 'theme', TEMPLATE_DIRECTORY . LANGUAGE_BASE_PATH );

		load_textdomain(
			'advanced-custom-fields-rgba-color',
			TEMPLATE_DIRECTORY . LIBRARY_BASE_PATH . '/advanced-custom-fields-rgba-color/lang/'
		);

		// Registers menu
		register_nav_menus( array(
			'primary' => __( 'Primary navigation', 'theme' ),
			'secondary' => __( 'Secondary navigation', 'theme' ),
			'footer' => __( 'Footer navigation', 'theme' )
		) );

		// Adds BrowserSync support
		if ( WP_DEBUG && ( defined( 'BROWSERSYNC' ) && BROWSERSYNC ) ) {
			if ( current_theme_supports( 'browsersync' ) )
				add_action( 'wp_footer', array( $this, 'browsersync_support' ), 9999 );
		}
	}


	/**
	 * Adds essential meta tags in the page head
	 */
	public function metas() {
		echo "<meta charset=\"utf-8\" />\n",
			 "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />\n";
	}


	/**
	 * Adds the BrowserSync support
	 */
	public function browsersync_support() {
		$browsersync = get_theme_support( 'browsersync' );

		$hostname = isset( $browsersync['host'] ) ? (string) $browsersync['host'] : 'localhost';
		$port = isset( $browsersync['port'] ) ? (int) $browsersync['port'] : 3000;

		echo "<script id='__bs_script__'>\n/* <![CDATA[ */\ndocument.write(\"",
			 "<script async src='http://{$hostname}:{$port}/browser-sync/browser-sync-client.js'><\/script>",
			 "\");\n/* ]]> */\n</script>\n";
	}


	/**
	 * Frontend stylesheets queue
	 */
	public function styles_queue() {

		// Bootstrap 4
		wp_register_style(
			'bootstrap-4',
			get_theme_file_uri( ASSETS_BASE_PATH . '/css/lib/bootstrap/bootstrap' . $this->asset_prefix . '.css' ),
			null,
			'4.3.1'
		);

		// Owl Carousel 2
		wp_register_style(
			'owl-carousel-2',
			get_theme_file_uri( ASSETS_BASE_PATH . '/css/lib/owl.carousel/owl.carousel' . $this->asset_prefix . '.css' ),
			'2.3.4',
			null
		);

	}


	/**
	 * Frontend scripts queue
	 */
	public function scripts_queue() {

		// Register script: Bootstrap 4
		wp_register_script(
			'bootstrap-4',
			get_theme_file_uri( ASSETS_BASE_PATH . '/js/lib/bootstrap/bootstrap.bundle' . $this->asset_prefix . '.js' ),
			array('jquery'),
			'4.3.1',
			true
		);

		// Register script: Owl Carousel 2
		wp_register_script(
			'owl-carousel-2',
			get_theme_file_uri( ASSETS_BASE_PATH . '/js/lib/owl.carousel/owl.carousel' . $this->asset_prefix . '.js' ),
			array('jquery'),
			'2.3.4',
			true
		);

		// Deregister script: jQuery
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', false, array('jquery-core'), null );

		/**
		 * Filter to enable superseding the built-in jQuery library
		 *
		 * @param bool void
		 */
		if ( apply_filters( 'theme_supersedes_bi_jquery', true ) ) {
			wp_deregister_script( 'jquery-core' );
			wp_register_script(
				'jquery-core',
				get_theme_file_uri( ASSETS_BASE_PATH . '/js/lib/jquery/jquery' . $this->asset_prefix . '.js' ),
				null,
				null
			);
		} else {
			wp_add_inline_script( 'jquery-core', 'window.$ = jQuery;' );
		}

	}


	/**
	 * Frontend assets queue
	 *
	 * @see wp_head()
	 * @see wp_footer() 
	 */
	public function assets_queue() {
		wp_dequeue_style( 'wp-block-library' );

		wp_enqueue_style( 'bootstrap-4' );
		wp_enqueue_style( 'owl-carousel-2' );

		wp_enqueue_style( 'theme-style', get_stylesheet_uri() );


		wp_enqueue_script( 'bootstrap-4' );
		wp_enqueue_script( 'owl-carousel-2' );


		add_action( 'wp_footer', array($this, 'default_inline_script'), 9999 );
	}


	/**
	 * Output default inline script
	 */
	public function default_inline_script() {
		get_template_part( 'template-parts/script-default', substr( $this->asset_prefix, 1 ) );
	}


	/**
	 * Registers the widget area
	 *
	 * //TODO markup
	 */
	public function widgets_init() {
		register_sidebar( array(
			'name' => __( 'Sidebar', 'theme' ),
			'id' => 'sidebar',
			'description'  => __( 'A sidebar to displays widgets.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation primary (before menu)', 'theme' ),
			'id' => 'navigation-primary-before',
			'description' => __( 'Add widgets before the primary menu navigation.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation primary (after menu)', 'theme' ),
			'id' => 'navigation-primary-after',
			'description' => __( 'Add widgets after the primary menu navigation.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation secondary (before menu)', 'theme' ),
			'id' => 'navigation-secondary-before',
			'description' => __( 'Add widgets before the secondary menu navigation.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Navigation secondary (after menu)', 'theme' ),
			'id' => 'navigation-secondary-after',
			'description' => __( 'Add widgets after the secondary menu navigation.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Page (top)', 'theme' ),
			'id' => 'page-top',
			'description' => __( 'Add widgets in top of the page.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Page (bottom)', 'theme' ),
			'id' => 'page-bottom',
			'description' => __( 'Add widgets in bottom of the page.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Front page (top)', 'theme' ),
			'id' => 'front-page-top',
			'description' => __( 'Add widgets in top of the front page.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Front page (bottom)', 'theme' ),
			'id' => 'front-page-bottom',
			'description' => __( 'Add widgets in bottom of the front page.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Post (top)', 'theme' ),
			'id' => 'post-top',
			'description' => __( 'Add widgets in top of the post.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Post (bottom)', 'theme' ),
			'id' => 'post-bottom',
			'description' => __( 'Add widgets in bottom of the post.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Footer (before menu)', 'theme' ),
			'id' => 'footer-before',
			'description' => __( 'Add widgets before the footer menu.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		register_sidebar( array(
			'name' => __( 'Footer (after menu)', 'theme' ),
			'id' => 'footer-after',
			'description' => __( 'Add widgets after the footer menu.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );

		if ( $this->theme->Functions->has_shop() ) {
			register_sidebar( array(
				'name' => __( 'Store', 'theme' ),
				'id' => 'shop',
				'description' => __( 'A sidebar to displays widget in the shop after the content.', 'theme' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			) );
		}
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

		/* workaround */
		if ( isset( $GLOBALS['sitepress'] ) )
			remove_action( 'wp_head', array( $GLOBALS['sitepress'], 'meta_generator_tag' ) );
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
	 * Registers theme widgets
	 */
	public function register_widgets() {

		// Register widget: \theme\Widget_Recent_Posts
		require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-widget-recent-posts.php';
		register_widget( '\theme\Widget_Recent_Posts' );

		// Register widget: \theme\Widget_Recent_Comments
		require TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-widget-recent-comments.php';
		register_widget( '\theme\Widget_Recent_Comments' );

	}


	/**
	 * Registers theme shortcodes
	 */
	public function register_shortcodes() {
		add_shortcode( 'date', array($this, 'date_shortcode') );

		if ( current_theme_supports( 'html5', 'gallery' ) ) {
			remove_shortcode( 'gallery', 'gallery_shortcode' );
			add_shortcode( 'gallery', array($this, 'gallery_shortcode') );
		}

		add_shortcode( 'widget', array($this, 'widget_shortcode') );
		add_shortcode( 'page', array($this, 'page_shortcode') );
		add_shortcode( 'post', array($this, 'post_shortcode') );
		add_shortcode( 'email', array($this, 'email_shortcode') );

		add_shortcode(
			( shortcode_exists( 'slideshow' ) ? 'slideshow-block' : 'slideshow' ),
			array($this, 'shortcode__block')
		);

		add_shortcode(
			( shortcode_exists( 'form' ) ? 'form-block' : 'form' ),
			array($this, 'shortcode__block')
		);
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
	 * The gallery shortcode, overrides the built-in gallery
	 *
	 * @see gallery_shortcode()
	 *
	 * @param array $attr {
	 * 		@type string ‘instance‘
	 * 		@type string ‘order‘
	 * 		@type string ‘orderby‘
	 * 		@type int|string ‘id‘
	 * 		@type int ‘columns‘
	 * 		@type string ‘size‘
	 * 		@type string ‘include‘
	 * 		@type string ‘exclude‘
	 * 		@type string ‘link‘
	 * 		@type bool ‘slider‘
	 * }
	 * @return string void|$output
	 */
	public function gallery_shortcode( $attr ) {
		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		/**
		 * Filters the default gallery shortcode output.
		 *
		 * //LEGACY
		 *
		 * @see gallery_shortcode()
		 *
		 * @param string $output
		 * @param array $attr
		 * @param int $instance
		 */
		$output = apply_filters( 'post_gallery', '', $attr, $instance );

		if ( $output != '' )
			echo $output;

		$atts = shortcode_atts( array(
			'instance' => $instance,
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'id' => $post ? $post->ID : 0,
			'columns' => 3,
			'size' => 'thumbnail',
			'include' => '',
			'exclude' => '',
			'link' => '',
			'slider' => ''
		), $attr, 'gallery' );

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array(
				'include' => $atts['include'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby']
			) );

			$atts['attachments'] = array();

			foreach ( $_attachments as $key => $val ) {
				$atts['attachments'][$val->ID] = $_attachments[$key];
			}
		} else if ( ! empty( $atts['exclude'] ) ) {
			$atts['attachments'] = get_children( array(
				'post_parent' => $id,
				'exclude' => $atts['exclude'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby']
			) );
		} else {
			$atts['attachments'] = get_children( array(
				'post_parent' => $id,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby']
			) );
		}

		if ( empty( $atts['attachments'] ) )
			return '';

		if ( is_feed() ) {
			$output = "\n";

			foreach ( $atts['attachments'] as $att_id => $attachment )
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";

			return $output;
		}

		$atts['columns'] = (int) $atts['columns'] ? $atts['columns'] : 1;
		$atts['cols'] = intval( 12 / $atts['columns'] );

		ob_start();

		add_filter( 'wp_calculate_image_srcset_meta', array($this->theme->Functions, 'image_disable_responsive') );

		set_query_var( 'shortcode_atts', $atts );

		get_template_part( 'template-parts/gallery' );

		set_query_var( 'shortcode_atts', null );
		set_query_var( 'shortcode_id', null );

		remove_filter( 'wp_calculate_image_srcset_meta', array($this->theme->Functions, 'image_disable_responsive') );

		$output .= ob_get_contents();

		ob_end_clean();

		return $output;
	}


	/**
	 * The widget shortcode, output WP_Widget instance
	 *
	 * @see the_widget()
	 * @see /WP_Widget_Factory
	 *
	 * @global object $wp_widget_factory - WP_Widget_Factory
	 * @param array $attr {
	 * 		@type string ‘class‘
	 * 		@type string ‘title‘
	 * 		@type string ‘before_widget‘
	 * 		@type string ‘after_widget‘
	 * 		@type string ‘before_title‘
	 * 		@type string ‘after_title‘
	 *
	 * 		...
	 *
	 * 		@type int ‘_instance‘
	 * 		@type string ‘_widget‘
	 *
	 * 		...
	 * }
	 * @param null|string $content
	 * @return string void|$output
	 */
	function widget_shortcode( $attr, $content ) {
		if ( '' == $attr['class'] )
			return '';

		global $wp_widget_factory;

		$widget = $attr['class'];

		if ( ! isset( $wp_widget_factory->widgets[$widget] ) )
			$widget = 'WP_Widget_' . $widget;

		if ( ! isset( $wp_widget_factory->widgets[$widget] ) )
			return '';

		static $id = 0;
		$id++;

		$instance = shortcode_atts( array(
			'_id' => $id,
			'_widget' => $widget,
			'title' => '',
			'before_widget' => '<div class="widget %s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		), $attr, 'widget' );

		$args = array(
			'before_widget' => $instance['before_widget'],
			'after_widget' => $instance['after_widget'],
			'before_title' => $instance['before_title'],
			'after_title' => $instance['after_title']
		);

		unset( $instance['class'] );
		unset( $instance['before_widget'] );
		unset( $instance['after_widget'] );
		unset( $instance['before_title'] );
		unset( $instance['after_title'] );

		$instance = wp_parse_args( $attr, $instance );

		if ( $content )
			$instance['title'] = sanitize_text_field( $content );

		ob_start();

		the_widget(
			$widget,
			$instance,
			$args
		);

		return ob_get_clean();
	}


	/**
	 * Gets the post object for shortcode
	 *
	 * @param array $attr {
	 *		@type int ‘instance‘
	 * 		@type int ‘id‘
	 * 		@type string ‘name‘
	 * 		@type string ‘template‘
	 * }
	 * @return string void
	 */
	function get_shortcode_post_object( $post_type = 'post', $attr ) {
		static $instance = 0;
		$instance++;

		$atts = shortcode_atts( array(
			'instance' => $instance,
			'id' => '',
			'name' => '',
			'template' => 'post'
		), $attr, 'post_object' );

		if ( $atts['id'] )
			$atts['id'] = intval( $atts['id'] );

		if ( $atts['name'] )
			$atts['name'] = sanatize_text_field( $atts['name'] );

		if ( $atts['id'] && $atts['name'] )
			return '';

		if ( locate_template( 'template-parts/' . $atts['template'] . '.php' ) )
			$atts['template'] = 'template-parts/' . $atts['template'];
		else
			return '';

		/**
		 * Filters the arguments for the post object shortcode
		 *
		 * @see \WP_Query->get_posts()
		 *
		 * @param array void - ‘args‘
		 * @param string $post_type
		 * @param array $atts
		 */
		$query = new \WP_Query( apply_filters( 'theme_post_object_args', array(
			'post_type' => $post_type,
			'post_id' => $atts['id'],
			'post_name' => $atts['name'],
			'posts_per_page' => 1,
			'no_found_rows' => false,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true
		), $post_type, $atts ) );

		if ( ! $query->have_posts() )
			return '';

		$post = $query->post;

		/**
		 * theme_post_object_action hook.
		 *
		 * @param string $post_type
		 * @param array $atts
		 * @param object $post - \WP_Post
		 */
		do_action( 'theme_post_object_action', $post_type, $atts, $post );

		ob_start();

		setup_postdata( $post );

		get_template_part( $atts['template'] );

		wp_reset_postdata();

		return ob_get_clean();
	}


	/**
	 * The post shortcode, output post page
	 *
	 * @param array $attr {
	 * 		@type int ‘id‘
	 * 		@type string ‘name‘
	 * }
	 * @return string void
	 */
	function page_shortcode( $attr ) {
		$attr = wp_parse_args( $attr, array('template' => 'page') );

		return call_user_func( array($this, 'get_shortcode_post_object'), 'page', $attr );
	}


	/**
	 * The post shortcode, output post
	 *
	 * @param array $attr {
	 * 		@type int ‘id‘
	 * 		@type string ‘name‘
	 * }
	 * @return string void
	 */
	function post_shortcode( $attr ) {
		$attr = wp_parse_args( $attr, array('template' => 'post') );

		return call_user_func( array($this, 'get_shortcode_post_object'), 'post', $attr );
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


	/**
	 * A generic `block` shortcode, transforms theme built-in features in shortcode  
	 *
	 * @param array $attr {
	 * 		@type string ‘instance‘
	 * 		@type string ‘order‘
	 * 		@type string ‘orderby‘
	 * 		@type int|string ‘id‘
	 * 		@type int ‘columns‘
	 * 		@type string ‘size‘
	 * 		@type string ‘include‘
	 * 		@type string ‘exclude‘
	 * 		@type string ‘link‘
	 * }
	 * @param null|string $content
	 * @param null|string $shortcode_tag
	 * @return string void|$output
	 */
	public function shortcode__block( $attr, $content, $shortcode_tag ) {
		static $instance = 0;
		$instance++;

		$output = '';

		$atts = shortcode_atts( array(
			'instance' => $instance,
			'id' => 0
		), $attr, $shortcode_tag );

		$id = intval( $atts['id'] );

		if ( '' == $id )
			return $output;

		$atts['post'] = get_post( $id );

		if ( ! $atts['post'] )
			return $output;

		ob_start();

		set_query_var( 'shortcode_atts', $atts );

		get_template_part( 'template-parts/' . $shortcode_tag, 'shortcode-block' );

		set_query_var( 'shortcode_atts', null );

		$output .= ob_get_contents();

		ob_end_clean();

		return $output;
	}


	/**
	 * Loads slideshow assets,
	 * wrapping around the \get_footer() function
	 *
	 * @static
	 */
	public static function load_slideshow_assets() {
		add_action( 'get_footer', array($this, 'load_slideshow_assets__owl_carousel') );
	}


	/**
	 * Loads Owl Carousel 2 assets
	 *
	 * @static
	 *
	 * @see \theme\Setup::load_slideshow_assets()
	 */
	public static function load_slideshow_assets__owl_carousel() {
		wp_enqueue_script( 'owl-carousel-2' );
		wp_enqueue_style( 'owl-carousel-2' );
	}


}

new Setup;