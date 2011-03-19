<?php

/*
Plugin Name: CTGPS plugin(s) for BuddyPress
Description: plugin(s) used for CTGPS's specific needs
Version: 0.0.1
Author: Dinosoft dinosoft@qq.com
*/

add_filter("bp_core_signup_send_activation_key", function() {return false;}, 999 );

add_filter( 'bp_registration_needs_activation', function() {return false;}, 999  );

	
/**
 * ctgps_bp_get_user_profile_array(  )
 *
 * Returns the $key => $value array from user basic profile
 * and BP_XProfile_Group::get raw array.
 * 
 * @param $uid user id
 * @return $key => $value array
 */
function ctgps_bp_get_user_profile_array( $uid ){
    
    $groups = BP_XProfile_Group::get( array(
		'profile_group_id' => 1, //default group
		'user_id' => $uid,
		'hide_empty_groups' => true,
		'fetch_fields' => true,
		'fetch_field_data' => true
	) );
	
	$raw = $groups[0]->fields;
    $result = array();
    
    foreach ( $raw as $field ){
        $result[$field->name] = $field->data->value;
    }
    
   
    $user = new BP_Core_User( $uid );
    $result['id']=$uid;
    $result['avatar']= $user->avatar;
    $result['email'] = $user->email;
    $result['status'] = $user->status;
    
    return $result;
    
}
	
/**
 * bp_core_get_userid_from_display_name()
 *
 * Returns the user_id for a user based on their display_name.
 *
 * @param $display_name str Username to check.
 * @global $wpdb WordPress DB access object.
 * @return false on no match
 * @return int the user ID of the matched user.
 */
function bp_core_get_userid_from_display_name( $display_name ) {
	global $wpdb;

	if ( empty( $display_name ) )
		return false;

	return  $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . CUSTOM_USER_TABLE . " WHERE display_name = %s", $display_name ) ) ;
}


// **** "Ticket" Menu *********
function ctgps_bp_adminbar_ticket_menu() {
	global $bp;

	
	if ( !is_user_logged_in() || !function_exists("ctgps_ticket_get_ticket_info_from_uid") )
		return false;
    $result= ctgps_ticket_get_ticket_info_from_uid( get_current_user_id() );
    if ( !$result->ticketnum )
        return false;
    
   
	echo '<li id="bp-adminbar-ticket-menu"><a href="' . get_home_url().  '/ticket-system">我的订票';
	
	if ( $result->ticketnum ) {  ?>
	    <span><?php echo $result->ticketnum ?></span>
	<?php 
	}

	echo '</a>';
	echo '<ul>';

	global $wpdb; 
	$coachname = $wpdb->get_results("select * from `{$wpdb->prefix}ctgps_ticket_info`");
	$coachname = $coachname[0]->coachname;
	?>
	<li><a href="<?php echo get_home_url() ?>/ticket-system"><span><?php echo $result->ticketnum ?></span><?php  echo $coachname; ?></a></li>

    <?php 
	echo '</ul>';
	echo '</li>';
}

add_action( 'bp_adminbar_menus', 'ctgps_bp_adminbar_ticket_menu',   7  );

function ctgps_bp_add_navXT(){
    if(function_exists('bcn_display')){
	    bcn_display();
    }
}
//add_action('bp_before_blog_home', 'ctgps_bp_add_navXT');
add_action('bp_before_archive', 'ctgps_bp_add_navXT');
add_action('bp_before_blog_page', 'ctgps_bp_add_navXT');

