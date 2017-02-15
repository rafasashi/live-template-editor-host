<!DOCTYPE html>
<html>

    <head>
	
        <title>Live Editor</title>	
		
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/ui-lightness/jquery-ui.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>	

		<script type="text/javascript" src="<?php echo dirname( plugin_dir_url( __FILE__ )); ?>/assets/dependencies/prettify/prettify.js"></script>
		
		<style>
			
			<?php
			
			$assets 		= trailingslashit(dirname(dirname( __FILE__ ))) . 'assets';
			$dependencies 	= $assets . '/dependencies';
			$livetpleditor 	= $dependencies . '/livetpleditor'; //TODO set $this->_dev		
			
			echo file_get_contents( trailingslashit(dirname(dirname( __FILE__ ))) . 'assets/css/frontend.css' ).PHP_EOL;
			
			echo file_get_contents( $livetpleditor . '/livetpleditor-3.0.css' ).PHP_EOL;
			
			echo file_get_contents( $dependencies . '/chosen/chosen.min.css' ).PHP_EOL;
			echo file_get_contents( $dependencies . '/chosen/ImageSelect.css' ).PHP_EOL;
			echo file_get_contents( $dependencies . '/prettify/prettify.css' ).PHP_EOL;

			echo file_get_contents( $dependencies . '/medium-editor/css/medium-editor.css' ).PHP_EOL;
			echo file_get_contents( $dependencies . '/medium-editor/css/themes/bootstrap.css' ).PHP_EOL;
			
			?>			
			
			pre{ 
			
				height: 400px;
				overflow-y: scroll;
				overflow-x: hidden;
			}
			
			.panel-body {
				
				padding: 10px 0px 10px 20px;
			}
			
			select.form-control + .chosen-container.chosen-container-single .chosen-single {
			
				height:auto;
			}
			
			.chose-image-small, .chose-image-list {
				width: 70px;
				max-height: 70px;
			}
			
			.row {
				margin-left:0;
				margin-right:0;
			}

			.row .sp {
			   min-height:600px;
			   overflow:hidden;
			   
			   /*
			   margin-bottom:10px;
			   border-bottom:3px #eee solid;
			   */
			}
			.editor {
			   border-right:3px #eee solid;
			}
			.pane-label {
			  position: absolute;
			  z-index: 99;
			  padding: 0;
			  font-size: 80%;
			  opacity: .4;
			  right: 15px;
			  bottom: 15px;
			  margin: 0;
			}
			.cont {
				width:100%;
			}
			.inner {
				overflow-y:auto;
				overflow-x:hidden;
				height:100%;
			}
			.ui-resizable { position: relative;}
			.ui-resizable-handle { position: absolute;font-size: 0.1px;z-index: 99999; display: block; }
			.ui-resizable-w { cursor: w-resize; width: 7px; left: -5px; top: 0; height: 100%; }

			.btn {
				border-radius:0px;
			}			
		</style>
		
	</head>
	
	<body style="overflow:hidden;">
	
		<?php

			if( have_posts() ) : while ( have_posts() ) : the_post();

				the_content();

			endwhile; endif;
		?>
		
	</body>
	
</html>