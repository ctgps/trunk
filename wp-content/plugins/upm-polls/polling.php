<?php 
error_reporting(0);
require_once( '../../../wp-load.php' );
wp();
$ref = parse_url( $_SERVER['HTTP_REFERER'] );
if( $_SERVER["HTTP_HOST"] != $ref['host'] ){
	exit('UPM Error:128');
}

global $wpdb;

if( $_POST['upm_poll_id'] && $_POST['upm_action'] == 'polling' ){
		#########################################################
		$user = wp_get_current_user();
		$logging = get_option('pppm_poll_logging');
		if( $logging == 1 || $logging == 4 ){
			setcookie("_upm-polls-".$_POST['upm_poll_id'], '1', time()+ intval(upm_get_seconds()), '/');
		}
		if( intval($_POST['upm_answer']) ){
			$wpdb->query( "INSERT INTO `".$wpdb->prefix."pppm_polls_votes` VALUES( NULL, ".intval($_POST['upm_poll_id']).", 
																						 ".intval($_POST['upm_answer']).",
																						 ".intval($user->ID).",
																						 '".$_SERVER['REMOTE_ADDR']."',
																						 ".time().", '')" );
		}
		//upm_polls_result($_POST['upm_poll_id'], $_POST['type']);
}
elseif( $_GET['do'] == 'result' && $_GET['PID'] ){
	if( $_GET['type'] == 'specific'){global $post; $post = get_post( $_GET['post'] );}
	$POLL = pppm_get_polls( $_GET['type'], get_option('pppm_poll_first_poll') );
	if( $POLL['id'] !='' ){ $button = true; } else { $button = false; } 
	upm_polls_result($_GET['PID'], $_GET['type'], $button);
}
elseif( $_GET['do'] == 'next' ){
	if( $_GET['type'] == 'specific'){global $post; $post = get_post( $_GET['post'] );}
	upm_polls( 'next' , $_GET['type'] );
}
?>