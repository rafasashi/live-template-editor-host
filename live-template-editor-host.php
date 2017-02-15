<?php
/*
 * Plugin Name: Live Template Editor Host
 * Version: 1.0
 * Plugin URI: https://github.com/rafasashi
 * Description: Live Template Editor Host allows you to live edit templates.
 * Author: Rafasashi
 * Author URI: https://github.com/rafasashi
 * Requires at least: 4.6
 * Tested up to: 4.7
 *
 * Text Domain: ltple-host
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Rafasashi
 * @since 1.0.0
 */
	
	/**
	* Add documentation link
	*
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	if(!function_exists('is_dev_env')){
		
		function is_dev_env( $dev_ip = '176.132.10.223' ){
			
			if( $_SERVER['REMOTE_ADDR'] == $dev_ip || ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == $dev_ip ) ){
				
				return true;
			}

			return false;		
		}
	}
	
	if(!function_exists('ltple_row_meta')){
	
		function ltple_row_meta( $links, $file ){
			
			if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
				
				$new_links = array( '<a href="https://github.com/rafasashi" target="_blank">' . __( 'Documentation', 'cleanlogin' ) . '</a>' );
				$links = array_merge( $links, $new_links );
			}
			
			return $links;
		}
	}
	
	add_filter('plugin_row_meta', 'ltple_row_meta', 10, 2);
	
	$mode = ( is_dev_env() ? '-dev' : '');
	
	if( $mode == '-dev' ){
		
		ini_set('display_errors', 1);
	}

	// Load plugin class files
	require_once( 'includes'.$mode.'/class-ltple-host.php' );
	require_once( 'includes'.$mode.'/class-ltple-host-settings.php' );

	// Autoload plugin libraries
	
	$lib = glob( __DIR__ . '/includes'.$mode.'/lib/class-ltple-host-*.php');
	
	foreach($lib as $file){
		
		require_once( $file );
	}

	/**
	 * Returns the main instance of LTPLE_Host to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object LTPLE_Host
	 */
	function LTPLE_Host () {
		
		$instance = LTPLE_Host::instance( __FILE__, '1.0.0' );

		if ( is_null( $instance->_dev ) ) {
			
			$instance->_dev = ( is_dev_env() ? '-dev' : '');
		}		
		
		if ( is_null( $instance->settings ) ) {
			
			$instance->settings = LTPLE_Host_Settings::instance( $instance );
		}

		return $instance;
	}
	
	LTPLE_Host()->register_post_type( 'domain', __( 'Domain name', 'live-template-editor-host' ), __( 'Domain name', 'live-template-editor-host' ), '', array(

		'public' 				=> false,
		'publicly_queryable' 	=> false,
		'exclude_from_search' 	=> true,
		'show_ui' 				=> true,
		'show_in_menu' 			=> false,
		'show_in_nav_menus' 	=> false,
		'query_var' 			=> true,
		'can_export' 			=> true,
		'rewrite' 				=> false,
		'capability_type' 		=> 'post',
		'has_archive' 			=> false,
		'hierarchical' 			=> false,
		'show_in_rest' 			=> false,
		//'supports' 			=> array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail' ),
		'supports' 				=> array('title', 'author'),
		'menu_position' 		=> 5,
		'menu_icon' 			=> 'dashicons-admin-post',
	));