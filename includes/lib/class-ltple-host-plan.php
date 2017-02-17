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
		
		add_action( 'init' , array( $this, 'init_plan' ) );
	}
	
	public function init_plan(){

		if( isset($_POST['pk']) && isset($_POST['pd']) ){

			$this->key 		= sanitize_text_field( $_POST['pk'] );
			$this->dataStr 	= LTPLE_Host::base64_urldecode( sanitize_text_field($_POST['pd']) );
			$this->data 	= json_decode( html_entity_decode( $this->dataStr ),true );
			
			if( !empty($this->data['subscriber']) && !empty($this->data['meta']) && !empty($this->data['client']) ){
				
				$user_email = sanitize_email($this->data['subscriber']);
				$user_name 	= sanitize_user($user_email);

				// check if the user exists

				if(filter_var( $user_email, FILTER_VALIDATE_EMAIL )){
					
					$user_id = email_exists( $user_email );
					
					if( !is_numeric($user_id) ){
						
						// register new suscriber
						
						$user_data = array(
							'user_login'  	=>  $user_name,
							'user_email'   	=>  $user_email,
						);
						
						if( !get_userdatabylogin( $user_name ) ){
							
							$user_id = wp_insert_user( $user_data );					
						}						
					}
					
					if( is_numeric($user_id) ){
						
						// parse meta
	
						foreach($this->data['meta'] as $meta){
							
							if( !empty($meta['domain_name']['name']) ){
								
								// parse domains
								
								foreach($meta['domain_name']['name'] as $i => $name){
									
									if( !empty($meta['domain_name']['name']) ){

										// get domain_name
									
										$domain_name = $name.$meta['domain_name']['ext'][$i];
										
										//check if domain exists
										
										$domain = get_page_by_title( $domain_name, OBJECT, 'domain' );

										if( empty($domain) ){
											
											// add domain	
											
											$args = array(
												
												'post_author' 	=> $user_id,
												'post_title' 	=> $domain_name,
												'post_name' 	=> $domain_name,
												'post_type' 	=> 'domain',
												'post_status' 	=> 'publish'
											);

											if($domain_id = wp_insert_post( $args )){

												// store domain client
												
												update_post_meta($domain_id, 'domainClientUrl', $this->data['client'] );
											
												// send notification
												
												wp_mail($this->parent->settings->options->emailSupport, 'Domain name '.$domain_name.' added by user id ' . $user_id . ' - ip ' . $this->parent->request->ip, print_r($_SERVER,true));
											
												// echo message
											
												echo $domain_name.' added'.PHP_EOL;
											}
										}
									}
								}
							}
						}
						
						http_response_code(200);

						echo 'Hosting plan synchronized for user '.$user_id;
						exit;
					}				
				}
			}
			
			http_response_code(404);

			echo 'Hosting plan synchronization failed...';
			exit;			
		}
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