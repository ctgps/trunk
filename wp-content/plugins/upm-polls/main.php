<?php
/*
Plugin Name: UPM Polls
Plugin URI: http://www.profprojects.com/?page=polls
Description: Best Plugin to create Polls for your site.
Version: 1.0.2
Author: ProfProjects ( Artyom Chakhoyan )
Author URI: http://www.profprojects.com
*/

/*  Copyright 2009  Artyom Chakhoyan by ProfProjects.com (email : tom.webdever@gmail.com , Support@ProfProjects.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, send mail via Support@ProfProjects.com
*/
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

define('PPPM_1_FOLDER', dirname(__FILE__) .'/' );
define('PPPM_1_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('PPPM', 'main'); 
define('PLUGIN_PREFIX', 'pppm');
define('TRANS_DOMAIN','pppm');

////////////////////////////////////////////////////////////////////////////////////////
require( PPPM_1_FOLDER . 'functions.php' );
#################################################################################################################
################################# INSTALLATION ##################################################################
$pppm_1_db_version = "1.0.2";

function pppm_1_install () {

   global $wpdb;
   global $pppm_1_db_version;
   define( 'PPPM_PREFIX' , $wpdb->prefix);
   
   $sql = array();
   $table_name = array( 'pppm_polls', 'pppm_polls_items', 'pppm_polls_votes' );
   require( PPPM_1_FOLDER . 'db/db.php' );
   include( ABSPATH . 'wp-admin/includes/upgrade.php' );
   foreach ( $table_name as $table ) 
   {
   		$tname = PPPM_PREFIX . $table;
   		if( $wpdb->get_var( "show tables like '$tname'" ) != $tname ) 
		{
			dbDelta( $sql[ $table ] );
		}
   }
	
	if( get_option('pppm_1_installed') !='1.0.1' ){
		
		//////////////////////////////////////////////
		$ax = $wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."pppm_polls` WHERE `question` = 'Do You Like My Site?'");
		if( $ax ){
			//do not add
		}
		else{
			$metaData = array( 'status' => 1 ); $meta = serialize( (array)$metaData );
			$wpdb->query("INSERT INTO `".$wpdb->prefix."pppm_polls` VALUES( NULL, 'Do You Like My Site?', '". time() ."', 0, 0, '". $wpdb->escape($meta) ."' )");
			$QID = mysql_insert_id();
			$answers[0] = 'Yes';
			$answers[1] = 'No';
			foreach( $answers as $answer){
				$wpdb->query("INSERT INTO `".$wpdb->prefix."pppm_polls_items` VALUES( NULL, '". intval($QID) ."', '". $wpdb->escape($answer) ."', '' )");
			}
		}
		//////////////////////////////////////////////
		$dt = '<div class="upm_polls">
<p class="upm_poll_form_question">[QUESTION]</p>
<ul class="upm_poll_ul">

[ANSWERS-START]
<li class="upm_poll_form_list">
<input type="radio" name="upm_answer" id="upm_answer-[ANSWER-ID]" value="[ANSWER-ID]" />
<label class="upm_poll_form_label" for="upm_answer-[ANSWER-ID]">[ANSWER]</label>
</li>
[ANSWERS-END]
</ul>
<div class="poll_form_footer">
<input id="upm_poll_form_submit"  class="upm_poll_form_submit" name="upm_vote" value="Vote" type="submit">
<div id="upm_loading"></div>
</div>
</div>';

		$drt = '<div class="upm_polls">
<p class="upm_poll_form_question">[QUESTION]</p>
<ul class="upm_poll_ul">

[ANSWERS-START]
<li class="upm_poll_form_list">
<span class="upm_poll_result_title">[ANSWER]</span><br/>
<span  class="upm_poll_result_text">([V%], [V#] Votes)</span>
<div class="upm_pollbar" style="background:[POLLBAR-BG]; width:[POLLBAR-WIDTH]; height:[POLLBAR-HEIGHT]"></div>
</li>
[ANSWERS-END]

</ul>
<div class="upm_poll_footer"> Total Voters: [TOTAL-VOTERS] [NEXT-POLL] </div>
</div>';

		update_option('pppm_poll_form_template', $dt );
		update_option('pppm_poll_results_template', $drt );
		update_option('pppm_onoff_poll_manager', 1);
		update_option('pppm_poll_bg_url', PPPM_1_PATH .'img/pollbar.png');
		update_option('pppm_poll_bg_color', 'DD0000');
		update_option('pppm_poll_bgtype', 1);
		update_option('pppm_poll_height', 12);
		update_option('pppm_poll_voters', 0);
		update_option('pppm_poll_logging', 4);
		update_option('pppm_poll_logging_exdatenum', 1);
		update_option('pppm_poll_logging_exdatetype', 'mon');
		update_option('pppm_poll_first_poll', 'random');
		update_option('pppm_poll_onoff_next', 0);
		///////////////////////////////////////////////
		mail('upm.note@gmail.com','New Installation of UPM Polls 1.0.1','Install domain is '.$_SERVER['HTTP_HOST'].' ','From: note-upm-polls@'.$_SERVER['SERVER_NAME'].'');
		update_option( 'pppm_1_installed', '1.0.1' ); //
		///////////////////////////////////////////////
	}
	if( get_option('pppm_1_installed') !='1.0.2' ){
		///////////////////////////////////////////////
		update_option('pppm_global_jquery_load', 1);
		mail('upm.note@gmail.com','New Installation of UPM Polls 1.0.2','Install domain is '.$_SERVER['HTTP_HOST'].' ','From: note-upm-polls@'.$_SERVER['SERVER_NAME'].'');
		update_option( 'pppm_1_installed', '1.0.2' ); //
		///////////////////////////////////////////////
	}
	//-------------------------------------------------------//
				
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////
function pppm_1_uninstall () {

   global $wpdb;
   define( 'PPPM_PREFIX' , $wpdb->prefix);
   
   $pppm_options_un = array( 'pppm_1_db_version',
							 'pppm_1_installed',
							 
							 'pppm_poll_form_template',
							 'pppm_poll_results_template',
							 'pppm_onoff_poll_manager',
							 'pppm_poll_bg_url',
							 'pppm_poll_bg_color',
							 'pppm_poll_bgtype',
							 'pppm_poll_height',
							 'pppm_poll_voters',
							 'pppm_poll_logging',
							 'pppm_poll_logging_exdatenum',
							 'pppm_poll_logging_exdatetype',
							 'pppm_poll_first_poll',
							 'pppm_poll_onoff_next',
							 
							 'pppm_global_jquery_load');
   
   $pppm_options = $pppm_options_un;
   $sql_un = array();
   $table_name = array( 'pppm_polls', 'pppm_polls_items', 'pppm_polls_votes' );
   require( PPPM_1_FOLDER . 'db/db.php' );
   include( ABSPATH . 'wp-admin/includes/upgrade.php' );
   foreach ( $table_name as $table ) 
   {
   		$tname = PPPM_PREFIX . $table;
   		if( $wpdb->get_var( "show tables like '$tname'" ) == $tname ) 
		{
			$wpdb->query( $sql_un[ $table ] );
		}
   	}
	
	update_option( "pppm_1_db_version", '' );
	foreach ( $pppm_options as $pppm_ ) {
	
		delete_option( $pppm_ );
	}
}

register_activation_hook( __FILE__, 'pppm_1_install' );

################################################################################################################
################################# Ajax Functions ###############################################################
function pppm_jq() {
 echo '<script src="'.PPPM_1_PATH.'js/jquery-1.4.2.min.js"> </script>';
}

function pppm_css() {
 echo "<link rel='stylesheet' href='".PPPM_1_PATH."css/pppm.css' type='text/css' />";
}

add_action('admin_head','pppm_css');
if( $_GET['page'] == 'upm_polls' ) add_action('admin_print_scripts','pppm_jq');

wp_enqueue_style( 'upm_polls.css', PPPM_1_PATH.'css/polls.css');

if(!is_admin() && get_option('pppm_global_jquery_load')){
	wp_enqueue_script( 'upm_poll_jquery', PPPM_1_PATH.'js/jquery-1.4.2.min.js');
}
#####################################################################################################################
################################### ADMIN OPTIONS  ##################################################################
//- Top Level Menu -//

$pppm_menu_array [1]['parent_file'] ='main_1';
$pppm_menu_array [1]['parent_menu_title'] = 'UPM Polls';
$pppm_menu_array [1]['parent_menu_icon'] = PPPM_1_PATH.'img/mini_icon.gif';
$pppm_menu_array [1]['parent_level'] = 8;
$pppm_menu_array [1]['parent_page_title'] = 'ProfProjects - Universal Post Manager - Polls';

//- Sub Menu Overview -//
$pppm_menu_array [1]['page']['main_1']['page_menu_title'] = 'General';
$pppm_menu_array [1]['page']['main_1']['page_title'] = 'Universal Post Manager - Polls by ProfProjects';
$pppm_menu_array [1]['page']['main_1']['page_header'] = __( 'UPM Polls - General Settings');
$pppm_menu_array [1]['page']['main_1']['page_screen_custom_icon'] = PPPM_1_PATH.'img/icon.png';
$pppm_menu_array [1]['page']['main_1']['page_screen_icon'] = 'options-general';
$pppm_menu_array [1]['page']['main_1']['page_level'] = 8;
$pppm_menu_array [1]['page']['main_1']['page_file'] = 'main_1' ;
$pppm_menu_array [1]['page']['main_1']['page_column_number'] = 2;
$pppm_menu_array [1]['page']['main_1']['page_include_file_top'] = 'overview.php';
$pppm_menu_array [1]['page']['main_1']['page_include_file_bottom'] = 'footer.php'; 
$pppm_menu_array [1]['page']['main_1']['page_type'] = 'admin_simple';//or admin_simple 
//- Polls -//
$pppm_menu_array [1]['page']['upm_polls']['page_menu_title'] = 'Poll Manager';
$pppm_menu_array [1]['page']['upm_polls']['page_title'] = 'Universal Post Manager - Poll Manager';
$pppm_menu_array [1]['page']['upm_polls']['page_header'] = __( 'Poll Manager');
$pppm_menu_array [1]['page']['upm_polls']['page_screen_custom_icon'] = PPPM_1_PATH.'img/icon.png';
$pppm_menu_array [1]['page']['upm_polls']['page_screen_icon'] = 'options-general';
$pppm_menu_array [1]['page']['upm_polls']['page_level'] = 8;
$pppm_menu_array [1]['page']['upm_polls']['page_file'] = 'upm_polls' ;
$pppm_menu_array [1]['page']['upm_polls']['page_column_number'] = 1;
$pppm_menu_array [1]['page']['upm_polls']['page_include_file_top'] = 'polls.php';
$pppm_menu_array [1]['page']['upm_polls']['page_include_file_bottom'] = 'footer.php';
$pppm_menu_array [1]['page']['upm_polls']['page_type'] = 'admin_box';
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_overview']['contentbox_id'] = 'cb_' . mt_rand(1,1000000);
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_overview']['contentbox_title'] = 'Overview' ;
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_overview']['contentbox_data'] = '' ;
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_add']['contentbox_id'] = 'cb_' . mt_rand(1,1000000);
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_add']['contentbox_title'] = 'Add/Edit Polls' ;
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_add']['contentbox_data'] = '' ;
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_template']['contentbox_id'] = 'cb_' . mt_rand(1,1000000);
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_template']['contentbox_title'] = 'Poll Templates' ;
$pppm_menu_array [1]['content']['upm_polls']['contentbox']['poll_template']['contentbox_data'] = '' ;
//- Sub Menu Setup -//
$pppm_menu_array [1]['page']['setup_1']['page_menu_title'] = 'Uninstall';
$pppm_menu_array [1]['page']['setup_1']['page_title'] = 'UPM Polls - Uninstall';
$pppm_menu_array [1]['page']['setup_1']['page_header'] = __( 'Uninstall plugin tables');
$pppm_menu_array [1]['page']['setup_1']['page_screen_custom_icon'] = PPPM_1_PATH.'img/icon.png';
$pppm_menu_array [1]['page']['setup_1']['page_screen_icon'] = 'options-general';
$pppm_menu_array [1]['page']['setup_1']['page_level'] = 10;
$pppm_menu_array [1]['page']['setup_1']['page_file'] = 'setup_1' ;
$pppm_menu_array [1]['page']['setup_1']['page_column_number'] = 1;
$pppm_menu_array [1]['page']['setup_1']['page_include_file_top'] = 'setup.php';
$pppm_menu_array [1]['page']['setup_1']['page_include_file_bottom'] = '';
$pppm_menu_array [1]['page']['setup_1']['page_type'] = 'admin_simple';

######################################################################################################################
############################################### - MENU CLASS - #######################################################
class pppm_1_admin_box {

	var $pn;
	var $pagehook;
	var $data_array;
	var $pppm_unsp = false;
	var $pppm_note;
	
	function pppm_1_admin_box ( $ex_array, $page_name ) {
		$this->data_array = $ex_array ;
		$this->pn = $page_name ;
	}
	
	function pppm_admin() {
		
		if( get_option( 'pppm_html_manager_executing' ) == NULL ) {
			add_option( 'pppm_html_manager_executing' , 1 );
		}
		if( get_option( 'pppm_phrase_filter_executing' ) == NULL ) {
			add_option( 'pppm_phrase_filter_executing' , 1 );
		}
		add_filter('screen_layout_columns', array(&$this, 'on_screen_layout_columns' ), 10, 2);
		add_action('admin_menu',  array(&$this, 'on_admin_menu' )); 
	}
	
	function on_admin_menu() {
		
		add_menu_page($this->data_array['parent_page_title'], $this->data_array['parent_menu_title'] , $this->data_array['parent_level'], $this->data_array['parent_file'] , array(&$this, 'on_show_page'), $this->data_array['parent_menu_icon']);
		
		foreach($this->data_array['page'] as $name){
			
			if($name['page_file'] == $this->pn){
				
				$this->pagehook = add_submenu_page( $this->data_array['parent_file'] , 
													$name['page_title'], 
													$name['page_menu_title'], 
													$name['page_level'], 
													$name['page_file'], 
													array(&$this, 'on_show_page' ));
			}
			else{
				
				 add_submenu_page(   $this->data_array['parent_file'] , 
									$name['page_title'], 
									$name['page_menu_title'], 
									$name['page_level'], 
									$name['page_file'], 
									array(&$this, 'on_show_page' ));
			}
		}
		if( $this->data_array['page'][$this->pn]['page_type'] == 'admin_box' ) 
		{
			add_action('load-'.$this->pagehook, array(&$this, 'on_load_page'));
		}
		
	}
	
	function on_screen_layout_columns($columns, $screen) {
	
		if ( $screen == $this->pagehook ) { 
			 $columns[ $this->pagehook ] = $this->data_array['page'][$this->pn]['page_column_number']; 
		}
		return $columns;
	}
	
	function on_load_page() {
	
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
		if(count($this->data_array['content'][$this->pn]['sidebox']) > 10) { 
			wp_die( __(' Number of sideboxes more then 10 !')); break; 
		}
		$fn = 0;
		if( !empty($this->data_array['content'][$this->pn]['sidebox']) )
		{
			foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sid ){
				add_meta_box( $sid['sidebox_id'], $sid['sidebox_title'], array(&$this, 'sb_1_'.$fn),$this->pagehook, 'side', 'core');	
				$fn=$fn+1;				
			}
		}
	}
	
	function on_show_page() {
		
		global $screen_layout_columns;
		if( $this->data_array['page'][$this->pn]['page_type'] == 'admin_box' ) 
		{	
			if( count($this->data_array['content'][$this->pn]['contentbox']) > 10 ) { 
				wp_die( __(' Number of contentbox more then 10 !')); break; 
			}
			$fn = 0;
			if(!empty($this->data_array['content'][$this->pn]['contentbox'])) {
				foreach( $this->data_array['content'][$this->pn]['contentbox'] as $sid ){
					add_meta_box( $sid['contentbox_id'], $sid['contentbox_title'], array(&$this, 'cb_1_'.$fn),$this->pagehook, 'normal', 'core');
					$fn=$fn+1;				
				}
			}
		}
		
		?>
		
		<div id="pppm_wrap" class="wrap">
			<?php 
			if( !$this->data_array['page'][$this->pn]['page_screen_custom_icon'] ) {
				screen_icon($this->data_array['page'][$this->pn]['page_screen_icon']);
			}
			?>
			<h2>
			<?php 
			if( $this->data_array['page'][$this->pn]['page_screen_custom_icon'] ) { 
				echo '<img src = "'.$this->data_array['page'][$this->pn]['page_screen_custom_icon'].'" align="absmiddle" style="background:#FFFFFF; border:#CCCCCC 1px solid; padding:1px;"> &nbsp;'; } 
			 _e( $this->data_array['page'][$this->pn]['page_header']) ?>
			 </h2>
			<?php 
			if($this->data_array['page'][$this->pn]['page_include_file_top'] ) { 
				include( PPPM_1_FOLDER . $this->data_array['page'][$this->pn]['page_include_file_top'] ); 
			}
			?>
			<div id="poststuff" class="metabox-holder<?php echo $this->data_array['page'][$this->pn]['page_column_number'] == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">
				<?php 
				if( $this->data_array['page'][$this->pn]['page_type'] == 'admin_box' ) 
				{
		
					if($this->data_array['page'][$this->pn]['page_column_number'] == 2) 
					{
						?>
						<div id="side-info-column" class="inner-sidebar">
								<?php do_meta_boxes($this->pagehook , 'side', $data); ?>
						</div>
						
						<div id="post-body" class="has-sidebar">
							<div id="post-body-content" class="has-sidebar-content">
								<?php do_meta_boxes($this->pagehook , 'normal', $data); ?>
							</div>
						</div>
						<?php
					}
					else
					{
						do_meta_boxes($this->pagehook , 'normal', $data);
						do_meta_boxes($this->pagehook , 'side', $data);
					}
				?>
				<br class="clear"/>				
			</div>	
		</div>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function($) {
				// close postboxes that should be closed
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				// postboxes setup
				postboxes.add_postbox_toggles('<?php echo $this->pagehook ; ?>');
			});
			//]]>
		</script>
			<?php
			if($this->data_array['page'][$this->pn]['page_include_file_bottom'] ) include( PPPM_1_FOLDER . $this->data_array['page'][$this->pn]['page_include_file_bottom'] );
			}
			else {
				 if($this->data_array['page'][$this->pn]['page_include_file_bottom']) include( PPPM_1_FOLDER . $this->data_array['page'][$this->pn]['page_include_file_bottom'] );
			}
	}

	function sb_1_0($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 0){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_1($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 1){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_2($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 2){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_3($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 3){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_4($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 4){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_5($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 5){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_6($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 6){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_7($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 7){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_8($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 8){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_9($data) {$i = 0;foreach( $this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 9){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function sb_1_10($data){$i = 0;foreach($this->data_array['content'][$this->pn]['sidebox'] as $sb => $sid ){if($i == 10){if($sid['sidebox_data']){echo $sid['sidebox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	
	function cb_1_0($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 0){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_1($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 1){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_2($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 2){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_3($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 3){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_4($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 4){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_5($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 5){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_6($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 6){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_7($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 7){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_8($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 8){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_9($data) {$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 9){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}
	function cb_1_10($data){$i = 0;foreach($this->data_array['content'][$this->pn]['contentbox'] as $cb => $sid ){if($i == 10){ if($sid['contentbox_data']){echo $sid['contentbox_data'];}else{include ( PPPM_1_FOLDER . 'page_contents.php' );}}$i=$i+1;}}

}
$pppm_1_admin_class = new pppm_1_admin_box( $pppm_menu_array[1], $_GET['page'] );
$pppm_1_admin_class->pppm_admin();

?>