<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class LTPLE_Host_Settings {

	/**
	 * The single instance of LTPLE_Host_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		
		$this->parent = $parent;

		$this->base = 'ltple_';
		
		$this->plugin 		 	= new stdClass();
		$this->plugin->slug  	= 'live-template-editor-host';
		$this->plugin->title 	= 'Live Template Editor Host';
		$this->plugin->short 	= 'Live Editor';
		
		// get options
		
		$this->options 				 = new stdClass();
		$this->options->emailSupport = get_option( $this->base . 'email_support');		
		
		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_items' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_items () {
		
		//add menu in wordpress settings
		
		//$page = add_options_page( __( 'Live Template Editor Host', $this->plugin->slug ) , __( 'Live Template Editor Hosthronizer', $this->plugin->slug ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		//add_action( 'admin_print_styles' . $page, array( $this, 'settings_assets' ) );
		
		//add menu in wordpress dashboard
		
		add_menu_page('Live Editor Host', 'Live Editor Host', 'manage_options', $this->plugin->slug, array($this, 'settings_page'),'dashicons-layout');
	
		add_submenu_page(
		
			$this->plugin->slug,
			__( 'Plan Events', $this->plugin->slug ),
			__( 'Plan Events', $this->plugin->slug ),
			'edit_pages',
			'edit.php?post_type=plan-event'
		);	
	
		add_submenu_page(
		
			$this->plugin->slug,
			__( 'Stripe Events', $this->plugin->slug ),
			__( 'Stripe Events', $this->plugin->slug ),
			'edit_pages',
			'edit.php?post_type=stripe-event'
		);
		
		add_submenu_page(
		
			$this->plugin->slug,
			__( 'Paypal Events', $this->plugin->slug ),
			__( 'Paypal Events', $this->plugin->slug ),
			'edit_pages',
			'edit.php?post_type=paypal-event'
		);
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the cbp-admin-js script below
		wp_enqueue_style( 'farbtastic' );
    	wp_enqueue_script( 'farbtastic' );

    	// We're including the WP media scripts here because they're needed for the image upload field
    	// If you're not including an image upload then you can leave this function call out
    	wp_enqueue_media();

    	wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0' );
    	wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', $this->plugin->slug ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['settings'] = array(
			'title'					=> __( 'General settings', $this->plugin->slug ),
			'description'			=> '',
			'fields'				=> array(
				
				array(
					'id' 			=> 'client_url',
					'label'			=> __( 'Client url' , $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'http://', $this->plugin->slug )
				),
				array(
					'id' 			=> 'client_id',
					'label'			=> __( 'Client ID' , $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'number',
					'default'		=> '0',
					'placeholder'	=> __( '0', $this->plugin->slug )
				),
				array(
					'id' 			=> 'client_key',
					'label'			=> __( 'Client key' , 'live-template-editor-client' ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( '', 'live-template-editor-client' )
				),
				array(
					'id' 			=> 'email_support',
					'label'			=> __( 'Support email' , $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'support@example.com', $this->plugin->slug )
				)
				
				/*
				array(
					'id' 			=> 'password_field',
					'label'			=> __( 'A Password' , $this->plugin->slug ),
					'description'	=> __( 'This is a standard password field.', $this->plugin->slug ),
					'type'			=> 'password',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text', $this->plugin->slug )
				),
				array(
					'id' 			=> 'secret_text_field',
					'label'			=> __( 'Some Secret Text' , $this->plugin->slug ),
					'description'	=> __( 'This is a secret text field - any data saved here will not be displayed after the page has reloaded, but it will be saved.', $this->plugin->slug ),
					'type'			=> 'text_secret',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text', $this->plugin->slug )
				),
				array(
					'id' 			=> 'text_block',
					'label'			=> __( 'A Text Block' , $this->plugin->slug ),
					'description'	=> __( 'This is a standard text area.', $this->plugin->slug ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text for this textarea', $this->plugin->slug )
				),
				array(
					'id' 			=> 'single_checkbox',
					'label'			=> __( 'An Option', $this->plugin->slug ),
					'description'	=> __( 'A standard checkbox - if you save this option as checked then it will store the option as \'on\', otherwise it will be an empty string.', $this->plugin->slug ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'select_box',
					'label'			=> __( 'A Select Box', $this->plugin->slug ),
					'description'	=> __( 'A standard select box.', $this->plugin->slug ),
					'type'			=> 'select',
					'options'		=> array( 'drupal' => 'Drupal', 'joomla' => 'Joomla', 'wordpress' => 'WordPress' ),
					'default'		=> 'wordpress'
				),
				array(
					'id' 			=> 'radio_buttons',
					'label'			=> __( 'Some Options', $this->plugin->slug ),
					'description'	=> __( 'A standard set of radio buttons.', $this->plugin->slug ),
					'type'			=> 'radio',
					'options'		=> array( 'superman' => 'Superman', 'batman' => 'Batman', 'ironman' => 'Iron Man' ),
					'default'		=> 'batman'
				),
				array(
					'id' 			=> 'multiple_checkboxes',
					'label'			=> __( 'Some Items', $this->plugin->slug ),
					'description'	=> __( 'You can select multiple items and they will be stored as an array.', $this->plugin->slug ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'square' => 'Square', 'circle' => 'Circle', 'rectangle' => 'Rectangle', 'triangle' => 'Triangle' ),
					'default'		=> array( 'circle', 'triangle' )
				)
				*/
			)
		);
		
		$settings['checkout'] = array(
			'title'					=> __( 'Checkout settings', $this->plugin->slug ),
			'description'			=> '',
			'fields'				=> array(
				
				array(
					'id' 			=> 'stripe_test_secret_key',
					'label'			=> __( 'Stripe Test secret key' , $this->plugin->slug ),
					'description'	=> 'Stripe test secret key',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'sk_test_', $this->plugin->slug )
				),
				array(
					'id' 			=> 'stripe_test_publishable_key',
					'label'			=> __( 'Stripe Test publishable key' , $this->plugin->slug ),
					'description'	=> 'Stripe test publishable key',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'pk_test_', $this->plugin->slug )
				),
				array(
					'id' 			=> 'stripe_live_secret_key',
					'label'			=> __( 'Stripe Live secret key' , $this->plugin->slug ),
					'description'	=> 'Stripe live secret key',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'sk_live_', $this->plugin->slug )
				),
				array(
					'id' 			=> 'stripe_live_publishable_key',
					'label'			=> __( 'Stripe Live publishable key' , $this->plugin->slug ),
					'description'	=> 'Stripe live publishable key',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'pk_live_', $this->plugin->slug )
				),
				array(
					'id' 			=> 'stripe_mode',
					'label'			=> __( 'Stripe mode - User', $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'radio',
					'options'		=> array( 'test' => 'Test', 'live' => 'Live'),
					'default'		=> 'test'
				),
				array(
					'id' 			=> 'stripe_mode_admin',
					'label'			=> __( 'Stripe mode - Admin', $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'radio',
					'options'		=> array( 'test' => 'Test', 'live' => 'Live'),
					'default'		=> 'test'
				),
				array(
					'id' 			=> 'stripe_checkout_img',
					'label'			=> __( 'Stripe Checkout image url' , $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'http://', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_test_api_user',
					'label'			=> __( 'Paypal Test API user' , $this->plugin->slug ),
					'description'	=> 'Paypal Test API user',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'service_api1.example.com', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_test_api_pwd',
					'label'			=> __( 'Paypal Test API password' , $this->plugin->slug ),
					'description'	=> 'Paypal Test API password',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'mypassword', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_test_api_sign',
					'label'			=> __( 'Paypal Test API signature' , $this->plugin->slug ),
					'description'	=> 'Paypal Test API signature',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'AMEtk-H0fl3-XL8...', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_live_api_user',
					'label'			=> __( 'Paypal Live API user' , $this->plugin->slug ),
					'description'	=> 'Paypal Live API user',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'service_api1.example.com', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_live_api_pwd',
					'label'			=> __( 'Paypal Live API password' , $this->plugin->slug ),
					'description'	=> 'Paypal Live API password',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'mypassword', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_live_api_sign',
					'label'			=> __( 'Paypal Live API signature' , $this->plugin->slug ),
					'description'	=> 'Paypal Live API signature',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'AMEtk-H0fl3-XL8...', $this->plugin->slug )
				),
				array(
					'id' 			=> 'ppl_mode',
					'label'			=> __( 'Paypal mode - User', $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'radio',
					'options'		=> array( 'test' => 'Test', 'live' => 'Live'),
					'default'		=> 'live'
				),
				array(
					'id' 			=> 'ppl_mode_admin',
					'label'			=> __( 'Paypal mode - Admin', $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'radio',
					'options'		=> array( 'test' => 'Test', 'live' => 'Live'),
					'default'		=> 'live'
				),
				array(
					'id' 			=> 'ppl_checkout_img',
					'label'			=> __( 'Paypal Checkout image url' , $this->plugin->slug ),
					'description'	=> '',
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'http://', $this->plugin->slug )
				)
			)
		);

		/*
		$settings['extra'] = array(
			'title'					=> __( 'Extra', $this->plugin->slug ),
			'description'			=> __( 'These are some extra input fields that maybe aren\'t as common as the others.', $this->plugin->slug ),
			'fields'				=> array(
				array(
					'id' 			=> 'number_field',
					'label'			=> __( 'A Number' , $this->plugin->slug ),
					'description'	=> __( 'This is a standard number field - if this field contains anything other than numbers then the form will not be submitted.', $this->plugin->slug ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'	=> __( '42', $this->plugin->slug )
				),
				array(
					'id' 			=> 'colour_picker',
					'label'			=> __( 'Pick a colour', $this->plugin->slug ),
					'description'	=> __( 'This uses WordPress\' built-in colour picker - the option is stored as the colour\'s hex code.', $this->plugin->slug ),
					'type'			=> 'color',
					'default'		=> '#21759B'
				),
				array(
					'id' 			=> 'an_image',
					'label'			=> __( 'An Image' , $this->plugin->slug ),
					'description'	=> __( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an imge the thumbnail will display above these buttons.', $this->plugin->slug ),
					'type'			=> 'image',
					'default'		=> '',
					'placeholder'	=> ''
				),
				array(
					'id' 			=> 'multi_select_box',
					'label'			=> __( 'A Multi-Select Box', $this->plugin->slug ),
					'description'	=> __( 'A standard multi-select box - the saved data is stored as an array.', $this->plugin->slug ),
					'type'			=> 'select_multi',
					'options'		=> array( 'linux' => 'Linux', 'mac' => 'Mac', 'windows' => 'Windows' ),
					'default'		=> array( 'linux' )
				)
			)
		);
		*/
		
		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
										
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			
			$html .= '<h1>' . __( 'Live Template Editor Host' , $this->plugin->slug ) . '</h1>' . "\n";

			$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

				$c = 0;
				foreach ( $this->settings as $section => $data ) {

					// Set tab class
					$class = 'nav-tab';
					if ( ! isset( $_GET['tab'] ) ) {
						if ( 0 == $c ) {
							$class .= ' nav-tab-active';
						}
					} else {
						if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
							$class .= ' nav-tab-active';
						}
					}

					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}

					// Output tab
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			$html .= '<form style="margin-top:10px;" method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , $this->plugin->slug ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
			
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main LTPLE_Host_Settings Instance
	 *
	 * Ensures only one instance of LTPLE_Host_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see LTPLE_Host()
	 * @return Main LTPLE_Host_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}