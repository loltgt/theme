<?php
/**
 * theme options
 *
 * //TODO reflow label, input[type=checkbox|radio]
 *
 * @package theme
 * @version 2.0
 */

namespace theme;

use \Exception;

use \theme\Theme;


/**
 * Options class
 */
class Options {

	// @type object $theme - \theme\Theme
	private $theme;

	// @type string $page_title
	public $page_title;

	// @type string $menu_title
	public $menu_title;

	// @type string $options_page
	public $options_page;

	// @type string $option_group
	public $option_group;

	// @type string $option_name
	public $option_name;

	// @type array $options
	protected $options;

	// @type array $fields
	protected $fields;

	// @type array $sanitize_fields
	protected $sanitize_fields;


	/**
	 * Function __construct
	 */
	function __construct() {

		$this->theme = Theme::instance();

		$this->options_page = 'options-general.php';
		$this->options_slug = 'theme';
		$this->option_group = 'theme_options';
		$this->option_name = 'theme_settings';

		$this->page_title = _x( 'theme theme options', 'page_title', 'theme' );
		$this->menu_title = _x( 'Theme', 'menu_title', 'theme' );


		$this->fields = array(
			'text' => 'text_field_render',
			'checkbox' => 'checkbox_field_render',
			'radio' => 'radio_field_render',
			'textarea' => 'textarea_field_render',
			'select' => 'select_field_render',
			'group' => 'group_field_render'
		);
		$this->options = get_option( $this->option_name );
		$this->sanitize_fields = array();

		add_action( 'admin_init', array($this, 'register_settings') );
		add_action( 'admin_menu', array($this, 'register_options_subpages'), 0 );

		Theme::register( "Options", $this );

	}



	/**
	 * Registers settings
	 */
	public function register_settings() {
		register_setting(
			$this->option_group,
			$this->option_name,
			array($this, 'settings_sanitize_callback')
		);
	}


	/**
	 * Registers the settings subpage
	 */
	public function register_options_subpages() {
		$this->create_options_subpage(
			$this->page_title,
			$this->menu_title,
			$this->options_slug,
			array($this, 'settings'),
			array($this, 'settings_page')
		);
	}


	/**
	 * Creates the settings subpage
	 *
	 * @param string $page_title
	 * @param string $menu_title
	 * @param string $menu_slug
	 * @param function $settings_callback
	 * @param function $page_callback
	 */
	public function create_options_subpage( $page_title, $menu_title, $menu_slug, $settings_callback, $page_callback ) {
		if ( empty( $page_title ) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' . 
				'Missing \'page_title\' argument.' );

		if ( empty( $menu_title) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' .
				'Missing \'menu_title\' argument.' );

		if ( empty( $menu_slug ) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' .
				'Missing \'menu_slug\' argument.' );

		if ( empty( $settings_callback ) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' .
				'Missing \'settings_callback\' argument.' );


		if ( empty( $page_callback ) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' .
				'Missing \'page_callback\' argument.' );

		if ( ! is_callable( $settings_callback ) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' .
				'Bad \'settings_callback\' argument.' );


		if ( ! is_callable( $page_callback ) )
			throw new Exception( '\theme\Options->create_options_subpage() : ' .
				'Bad \'page_callback\' argument.' );

		add_submenu_page(
			$this->options_page,
			$page_title,
			$menu_title,
			'manage_options',
			$menu_slug,
			$page_callback
		);

		call_user_func( $settings_callback );
	}


	/**
	 * Registers settings page sections and fields
	 */
	public function settings() {
		add_settings_section(
			'theme_settings_section',
			'',
			array($this, 'settings_section'),
			$this->options_page
		);


		/**
		 * theme_settings_begin hook.
		 *
		 * @param object $this - \theme\Options
		 */
		do_action( 'theme_settings_begin', $this );


		// Frontend section

		add_settings_section(
			'theme_settings_frontend_section',
			__( 'Frontend', 'theme' ),
			array($this, 'settings_frontend_section'),
			$this->options_page
		);


		add_settings_field(
			'use_smilies',
			__( 'Convert emoticons like :-) and :-P to graphics on display.' ),
			array($this, 'checkbox_field_render'),
			$this->options_page,
			'theme_settings_frontend_section',
			array(
				'name' => $this->get_name( 'use_smilies' ),
				'value' => $this->get_value( 'use_smilies' ),
				'default' => true
			)
		);

		$this->add_settings_field_sanitize(
			'use_smilies',
			'bool',
			true
		);


		/**
		 * theme_settings_frontend_section hook.
		 *
		 * @param object $this - \theme\Options
		 */
		do_action( 'theme_settings_frontend_section', $this );


		// Backend section

		add_settings_section(
			'theme_settings_backend_section',
			__( 'Backend', 'theme' ),
			array($this, 'settings_backend_section'),
			$this->options_page
		);


		/**
		 * theme_settings_backend_section hook.
		 *
		 * @param object $this - \theme\Options
		 */
		do_action( 'theme_settings_backend_section', $this );


		// Advanced section

		add_settings_section(
			'theme_settings_advanced_section',
			__( 'Advanced', 'theme' ),
			array($this, 'settings_advanced_section'),
			$this->options_page
		);


		add_settings_field(
			'disable_redirect_guess_permalink',
			__( 'Prevents redirecting to nearest matching URL', 'theme' ),
			array($this, 'checkbox_field_render'),
			$this->options_page,
			'theme_settings_advanced_section',
			array(
				'name' => $this->get_name('disable_redirect_guess_permalink'),
				'value' => $this->get_value('disable_redirect_guess_permalink'),
				'default' => true
			)
		);

		$this->add_settings_field_sanitize(
			'disable_redirect_guess_permalink',
			'bool'
		);


		add_settings_field(
			'unexpone_versions',
			__( 'Unexposes software versions', 'theme' ),
			array($this, 'checkbox_field_render'),
			$this->options_page,
			'theme_settings_advanced_section',
			array(
				'name' => $this->get_name('unexpone_ver'),
				'value' => $this->get_value('unexpone_ver'),
				'default' => true
			)
		);

		$this->add_settings_field_sanitize(
			'unexpone_versions',
			'bool',
			true,
			'unexpone_ver'
		);


		add_settings_field(
			'disable_rp',
			__( 'Disables the reset password functionality', 'theme' ),
			array($this, 'checkbox_field_render'),
			$this->options_page,
			'theme_settings_advanced_section',
			array(
				'name' => $this->get_name('disable_rp'),
				'value' => $this->get_value('disable_rp'),
				'default' => true
			)
		);

		$this->add_settings_field_sanitize(
			'disable_rp',
			'bool'
		);


		/**
		 * theme_settings_advanced_section hook.
		 *
		 * @param object $this - \theme\Options
		 */
		do_action( 'theme_settings_advanced_section', $this );


		/**
		 * theme_settings_end hook.
		 *
		 * @param object $this - \theme\Options
		 */
		do_action( 'theme_settings_end', $this );
	}


	/**
	 * Builds the settings page
	 */
	public function settings_page() {
		echo '<div class="wrap">',
			 '<form action="options.php" method="post">',
			 '<h1>' . __( 'Theme settings', 'theme' ) . '</h1>';

		settings_fields( $this->option_group );
		do_settings_sections( $this->options_page );
		submit_button();

		echo '</form></div>';
	}


	/**
	 * Process the submitted data and dispose notices
	 *
	 * @param array $data
	 * @return array $data
	 */
	public function settings_sanitize_callback( $data ) {
		if ( empty( $data ) )
			$data = $this->options;

		$defaults = apply_filters( 'theme_options_default_messages', array(
			'error' => __( 'Error while saving.' ),
			'updated' => __( 'Settings saved.' )
		) );

		$status = 'updated';

		foreach ( $this->sanitize_fields as $name => $sanitize ) {
			if (
				isset( $data[$name] ) &&
				( $sanitize['type'] === 'string' && empty( $data[$name] ) ) &&
				( isset( $sanitize['required'] ) && $sanitize['required'] )
			) {
				$status = 'error';
				continue;
			}

			if ( isset( $sanitize['fn'] ) && is_function( $sanitize['fn'] ) ) {
				$data[$name] = $sanitize['fn']( $data[$name] );
			} else {
				settype( $data[$name], $sanitize['type'] );
			}

			if ( gettype( $data[$name] ) !== $sanitize['type'] ) {
				$status = 'error';
				continue;
			}
		}

		add_settings_error( $this->option_group, esc_attr( $this->option_name ), $defaults[$status], $status );

		return $data;
	}


	/**
	 * Output introduction settings section
	 *
	 * @param string $name
	 * @return mixed void
	 */
	public function settings_section() {
		echo '';
	}


	/**
	 * Output settings frontend section
	 *
	 * @param string $name
	 * @return mixed void
	 */
	public function settings_frontend_section() {
		echo '';
	}


	/**
	 * Output settings backend section
	 *
	 * @param string $name
	 * @return mixed void
	 */
	public function settings_backend_section() {
		echo '';
	}


	/**
	 * Output settings advanced section
	 *
	 * @param string $name
	 * @return mixed void
	 */
	public function settings_advanced_section() {
		echo '';
	}


	/**
	 * Gets name value
	 *
	 * @param string $name
	 * @return mixed void
	 */
	public function get_name( $name ) {
		if ( empty( $name ) )
			throw new Exception( '\theme\Options->get_name() : Missing \'name\' argument.' );

		return $this->option_name . '[' . $name . ']';
	}


	/**
	 * Gets option value
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed void|$default
	 */
	public function get_value( $name, $default = '' ) {
		if ( empty( $name ) )
			throw new Exception( '\theme\Options->get_value() : Missing \'value\' argument.' );

		if ( isset( $this->options[$name] ) )
			return $this->options[$name];

		return $default;
	}


	/**
	 * Sanitizes fields and mutates/transforms variable types
	 *
	 * //TODO check $callback
	 *
	 * @param string $slug
	 * @param string $type
	 * @param bool $required
	 * @param string $name
	 * @param function $callback
	 */
	public function add_settings_field_sanitize( $slug, $type = '', $required = false, $name = '', $callback = null ) {
		if ( empty( $slug ) )
			throw new Exception( '\theme\Options->add_settings_field_sanitize() : Missing \'slug\' argument.' );

		if ( empty( $type ) )
			throw new Exception( '\theme\Options->add_settings_field_sanitize() : Missing \'type\' argument.' );

		if ( ! empty( $callback ) && ! is_callable( $callback ) )
			throw new Exception( '\theme\Options->add_settings_field_sanitize() : Bad \'callback\' argument.' );

		$name = esc_attr( empty( $name ) ? $slug : $name );
		$sanitize = array( 'slug' => esc_attr( $slug ) );

		switch ( $type ) {
			case 'bool' :
			case 'boolean' :
				$sanitize['type'] = 'boolean';
			break;

			case 'int' :
			case 'integer' :
				$sanitize['type'] = 'integer';
			break;

			case 'float' :
			case 'double' :
				$sanitize['type'] = 'double';
			break;

			case 'string' :
			case 'array' :
			case 'object' :
			case 'null' :
				$sanitize['type'] = $type;
			break;

			default :
				throw new Exception( '\theme\Options->add_settings_field_sanitize() : Bad \'type\' argument.' );
		}

		if ( $required )
			$sanitize['required'] = true;

		$this->sanitize_fields[$name] = $sanitize;
	}


	/**
	 * Renderizes description field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘description‘
	 * 		@type string ‘description_html‘
	 * }
	 */
	public function description_render( $args ) {
		if ( empty( $args['description'] ) )
			throw new Exception( '\theme\Options->description_render() : ' .
				'Missing args[\'description\'] argument.' );

		if ( isset( $args['description_html'] ) ) {
			echo $args['description'];
		} else {
			echo '<p class="description" id="' . $args['name'] . '-description">',
				 strip_tags( $args['description'], '<a>' ) . '</p>';
		}
	}


	/**
	 * Renderizes label field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘title‘
	 * 		@type string ‘label_for‘
	 * 		@type string ‘description‘
	 * }
	 */
	public function label_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->label_field_render() : ' .
				'Missing args[\'name\'] argument.' );

		if ( empty( $args['title'] ) )
			throw new Exception( '\theme\Options->label_field_render() : ' .
				'Missing args[\'title\'] argument.' );

		$args = wp_parse_args( $args, array('label_for' => null) );

		echo '<label ',
			 ( $args['label_for'] ? ' for="' . esc_attr( $args['label_for'] ) . '"' : '' ),
			 '>' . esc_html( $args['title'] ) . '</label> ';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );
	}


	/**
	 * Renderizes input text field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘value‘
	 * 		@type string ‘description‘
	 * }
	 */
	public function text_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->text_field_render() :' .
				'Missing args[\'name\'] argument' );

		$args = wp_parse_args( $args, array('value' => '') );

		echo '<input type="text" name="' . esc_attr( $args['name'] ) . '" ',
			 'value="' . esc_attr( $args['value'] ) . '" />';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );
	}


	/**
	 * Renderizes input checkbox field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘value‘
	 * 		@type string ‘default‘
	 * 		@type string ‘description‘
	 * }
	 */
	public function checkbox_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->checkbox_field_render() : ' . 
				'Missing args[\'name\'] argument.' );

		$args = wp_parse_args( $args, array('value' => 0, 'default' => 0) );
		$args['_value'] = $args['value'];

		if ( $args['default'] && empty( $args['value'] ) )
			$args['value'] = 1;

		echo '<input type="checkbox" name="' . esc_attr( $args['name'] ) . '" ',
			 checked( $args['_value'], 1, 0 ) . ' value="' . esc_attr( $args['value'] ) . '" />';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );
	}


	/**
	 * Renderizes input radio field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘value‘
	 * 		@type string ‘default‘
	 * 		@type string ‘description‘
	 * }
	 */
	public function radio_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->radio_field_render() : ' .
				'Missing args[\'name\'] argument.' );

		$args = wp_parse_args( $args, array('value' => 0, 'default' => 0) );
		$args['_value'] = $args['value'];

		if ( $args['default'] && empty( $args['value'] ) )
			$args['value'] = 1;

		echo '<input type="radio" name="' . esc_attr( $args['name'] ) . '" ',
			checked( $args['_value'], 1, 0 ) . ' value="' . esc_attr( $args['value'] ) . '" />';
	}


	/**
	 * Renderizes textarea field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘value‘
	 * 		@type string ‘description‘
	 * }
	 */
	public function textarea_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->textarea_field_render() : ' .
				'Missing args[\'name\'] argument' );

		$args = wp_parse_args( $args, array('value' => '') );

		echo '<textarea name="' . esc_attr( $args['name'] ) . '" cols="40" rows="5">',
			 $args['value'] . '</textarea>';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );
	}

	/**
	 * Renderizes select field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘value‘
	 * 		@type string ‘default‘
	 * 		@type array ‘options‘
	 * 		@type string ‘description‘
	 * }
	 */
	public function select_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->select_field_render() : ' .
				'Missing args[\'name\'] argument.' );

		if ( empty( $args['options'] ) )
			throw new Exception( '\theme\Options->select_field_render() : ' . 
				'Missing args[\'options\'] argument' );

		if ( ! is_array( $args['options'] ) )
			throw new Exception( '\theme\Options->select_field_render() : ' . 
				'Bad args[\'options\'] argument' );

		$args = wp_parse_args( $args, array('value' => '', 'default' => '') );

		if ( $args['default'] && empty( $args['value'] ) )
			$args['value'] = $args['default'];

		echo '<select name="' . esc_attr( $args['name'] ) . '">';

		foreach ( (array) $args['options'] as $name => $label ) :
			echo '<option value="' . esc_attr( $name ) . '" ',
				 selected( ( (string) $args['value'] === (string) $name ), 1, 0 ) . '>',
				 esc_html( $label ) . '</option>';
		endforeach;
		
		echo '</select>';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );
	}


	/**
	 * Renderizes group field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘description‘
	 * 		@type array ‘fields‘
	 * }
	 */
	public function group_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->group_field_render() : ' . 
				'Missing args[\'name\'] argument.' );

		if ( empty( $args['fields'] ) )
			throw new Exception( '\theme\Options->group_field_render() : ' . 
				'Missing args[\'fields\'] argument.' );

		$args = wp_parse_args( $args, array('value' => '') );

		echo '<div class="group-wrap">';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );

		foreach ( (array) $args['fields'] as $i => $field ) :
			echo '<div class="group-field" data-id="' . $i . '">';

			if ( isset( $this->fields[$field['type']] ) ) :
				$field['value'] = '';

				if ( empty( $field['label'] ) )
					$field['label'] = $field['name'];

				if ( ! empty( $args['value'][$i][$field['name']] ) )
					$field['value'] = $args['value'][$i][$field['name']];

				$field['name'] = $args['name'] . '[' . $i . '][' . $field['name'] . ']';

				echo '<span class="group-field-label label">',
					 esc_html( $field['label'] ) . '</span> ';

				call_user_func( array($this, $this->fields[$field['type']]), $field );
			endif;

			echo '</div>';
		endforeach;

		echo '</div>';
	}


	/**
	 * Renderizes repeatable field
	 *
	 * @param array $args {
	 * 		@type string ‘name‘
	 * 		@type string ‘description‘
	 * 		@type array ‘fields‘
	 * 		@type bool ‘sortable‘
	 * 		@type string ‘add_button‘
	 * 		@type string ‘remove_button‘
	 * 		@type bool ‘disable_buttons‘
	 * }
	 */
	public function repeatable_field_render( $args ) {
		if ( empty( $args['name'] ) )
			throw new Exception( '\theme\Options->repeatable_field_render() : ' .
				'Missing args[\'name\'] argument.' );

		if ( empty( $args['fields'] ) )
			throw new Exception( '\theme\Options->repeatable_field_render() : ' .
				'Missing args[\'fields\'] argument.' );

		$args = wp_parse_args( $args, array(
			'value' => null,
			'sortable' => false,
			'add_button' => _x( 'Add Row', 'options', 'theme' ),
			'remove_button' => _x( 'Remove Row', 'options', 'theme' ),
			'disable_buttons' => false
		) );

		echo '<div class="repeatable-wrap">';

		if ( isset( $args['description'] ) )
			call_user_func( array($this, 'description_render'), $args );

		echo '<ul class="repeatable-fields-list meta-box-sortables ui-sortable"',
			 ( $args['sortable'] ? ' data-sortable' : '' ) . '>';

		foreach ( (array) $args['fields'] as $field ) :
			echo '<li class="reapeatable-field-template" data-template>';

			if ( isset( $this->fields[$field['type']] ) ) :
				$field['value'] = '';

				if ( empty( $field['label'] ) )
					$field['label'] = $field['name'];

				$field['name'] = $args['name'] . '[][' . $field['name'] . ']';

				echo '<span class="repeatable-field-label label">',
					 $field['label'] . '</span> ';

				call_user_func( array($this, $this->fields[$field['type']]), $field );

				if ( ! $args['disable_buttons'] ) :
					echo '<input type="button" name="remove[]" ',
						 'class="repeatable-field-remove button" ',
						 'value="' . esc_attr( $args['remove_button'] ) . '" />';
				endif;
			endif;

			echo '</li>';
		endforeach;

		foreach ( (array) $args['value'] as $i => $value ) :
			echo '<li class="reapeatable-field-template" data-template>';

			foreach ( (array) $args['fields'] as $field ) :
				if ( isset( $this->fields[$field['type']] ) ) :
					$field['value'] = '';

					if ( empty( $field['label'] ) )
						$field['label'] = $field['name'];

					if ( ! empty( $args['value'][$i][$field['name']] ) )
						$field['value'] = $args['value'][$i][$field['name']];

					$field['name'] = $args['name'] . '[' . $i . '][' . $field['name'] . ']';

					echo '<span class="repeatable-field-label label">',
						 $field['label'] . '</span> ';

					call_user_func( array($this, $this->fields[$field['type']]), $field );

					if ( ! $args['disable_buttons'] ) :
						echo '<input type="button" name="remove[' . $i . ']" ',
							 'class="repeatable-field-remove button" ',
							 'value="' . esc_attr( $args['remove_button'] ) . '" />';
					endif;
				endif;
			endforeach;

			echo '</li>';
		endforeach;
		
		echo '</ul>';
		
		if ( ! $args['disable_buttons'] ) :
			echo '<input type="button" name="add[]" class="repeatable-field-add button" ',
				 'value="' . esc_attr( $args['add_button'] ) . '" />';
		endif;

		echo '</div>';
	}


}

new Options;