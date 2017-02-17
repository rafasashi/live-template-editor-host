<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class LTPLE_Host_Plan {
	
	public	$key 		= '';
	public	$dataStr 	= '';
	public	$data 		= null;	
	
	/**
	 * Constructor function
	 */
	public function __construct ( $parent ) {
		
		$this->parent = $parent;
		
		$this->init_plan();
	}
	
	public function init_plan(){

		if( isset($_POST['pk']) && isset($_POST['pd']) ){

			$this->key 		= sanitize_text_field( $_POST['pk'] );
			$this->dataStr 	= LTPLE_Host::base64_urldecode( sanitize_text_field($_POST['pd']) );
			$this->data 	= json_decode( html_entity_decode( $this->dataStr ),true );
		}
		
		var_dump($this->data);
		exit;
	}
	
	public function addDomain(){
		
		//add domain name
		
		/*
			
		$event_post = array(
			
			'post_author' 	=> $this->parent->editedUser->ID,
			'post_title' 	=> $this->data['name'],
			'post_name' 	=> $_GET['pk'],
			'post_content' 	=> '',
			'post_type' 	=> 'plan-event',
			'post_status' 	=> 'publish'
		);

		if($event_id = wp_insert_post( $event_post )){
				
			// set custom field
		
			update_post_meta($event_id, 'planEventJson', urldecode(json_encode($this->data,JSON_PRETTY_PRINT)));
					

			// redirect to client page
			
			$redir_url= $this->parent->client->url . 'editor/?pk=' . $_GET['pk'] . '&pd=' . $_GET['pd'] . '&pv='.md5( 'subscribed' . $_GET['pd'] . $this->parent->_time . $this->parent->editedUser->user_email ).'&_='.$this->parent->_time;
			
			wp_redirect( $redir_url );
			exit;
		}
		*/
	}
}