<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class LTPLE_Host_Host {

	public $url = '';

	/**
	 * Constructor function
	 */
	public function __construct ( $parent ) {
		
		$this->parent = $parent;
		
		$this->url = get_option( $this->parent->_base . 'host_url' );
		$this->key = get_option( $this->parent->_base . 'host_key' );
		
		if( is_admin() ){
			
			//restrict admin dashboard to host url
			
			if( !empty($this->url) && WP_SITEURL != $this->url ){
				
				echo 'This page doesn\'t exists...';
				exit;				
			}
		}
	}
}