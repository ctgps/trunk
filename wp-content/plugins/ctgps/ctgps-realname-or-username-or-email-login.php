<?php
/*
Plugin Name: CTGPS Realname or Username or E-mail Login
Description: Allows you to log into WordPress (directly or via XML-RPC) using your realname/E-mail in addition to username. This plugin credits to another plugin 'Email Login' http://dentedreality.com.au/projects/wp-plugin-email-login/
Author: Dinosoft dinosoft@qq.com
Version: 0.1
*/

 
/**
 * If a realname(Chinese) is entered in the username box, then look up the matching username and authenticate as per normal, using that.
 * Please notice that there will be a conflict if some guys has the same realname, so we can't use realname to login.
 * @param string $user 
 * @param string $username 
 * @param string $password 
 * @return Results of autheticating via wp_authenticate_username_password(), using the username found when looking up via realname and password.
 */
function ctgps_realname_login_authenticate( $user, $username, $password ) {
 
    if ( preg_match("/[\x{4e00}-\x{9fa5}]/u", $username) ){
        global $wpdb;

        $result = $wpdb->get_results( $wpdb->prepare(
        "select user_login from {$wpdb->users} 
          where display_name ='%s' ", $username  ) );
        
        //var_dump($result);
        if ( count($result)==1 ){
           $username = $result[0]->user_login;
        }
        
    }else if ( preg_match("/@/u", $username) ) {
       //var_dump( $username );
       $user = get_user_by_email( $username );
	   if ( $user )
		 $username = $user->user_login;
    }


	
	return wp_authenticate_username_password( null, $username, $password );
}
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'ctgps_realname_login_authenticate', 20, 3 );

 

/**
 * Modify the string on the login page to prompt for username or email address
 */
function username_or_email_login() {
	?><script type="text/javascript">
	// Form Label
	document.getElementById('loginform').childNodes[1].childNodes[1].childNodes[0].nodeValue = '用户名/真实姓名/E-mail';
	
	// Error Messages
	if ( document.getElementById('login_error') )
		document.getElementById('login_error').innerHTML = document.getElementById('login_error').innerHTML.replace( '用户名', '用户名/真实姓名/E-mail' );
	</script><?php
} 
add_action( 'login_form', 'username_or_email_login' );
