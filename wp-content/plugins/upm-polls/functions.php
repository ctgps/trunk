<?php
if( !function_exists('upm_get_seconds') ){
	function upm_get_seconds(){
		
		$num = get_option('pppm_poll_logging_exdatenum');
		
		switch(get_option('pppm_poll_logging_exdatetype')){
			case 'sec' : $sec = $num; break;
			case 'min' : $sec = $num * 60 ; break;
			 case 'hr' : $sec = $num * 60 * 60 ; break;
			case 'day' : $sec = $num * 60 * 60 * 24 ; break;
			case 'mon' : $sec = $num * 60 * 60 * 24 * 30 ; break;
			 case 'yr' : $sec = $num * 60 * 60 * 24 * 30 * 12 ; break;
		}
		
		return $sec;
	}
}
if( !function_exists('upm_allow_1') ){
	function upm_allow_1(){
		
		global $user_ID;
		$UID = intval($user_ID);
		$_voters = get_option('pppm_poll_voters');
		
		if( $_voters == 0 ){
			$allow = true;
		}
		elseif( $_voters == 1 ){
			if( $UID > 0 ){$allow = true;}else{$allow = false;}
		}
		elseif( $_voters == 2 ){
			if( $UID > 0 ){$allow = false;}else{$allow = true;}
		}
		
		if( get_option('pppm_onoff_poll_manager') && $allow ){ $allow = true; }else{ $allow = false; }
		
		return $allow;
	}
}
/////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_upm_ayax_polls_result', 'upm_ayax_polls_result');
add_action('wp_ajax_upm_ayax_polls_result', 'upm_ayax_polls_result');

function upm_ayax_polls_result(){
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
			exit();
	}
	elseif( $_GET['do'] == 'result' && $_GET['PID'] ){
		if( $_GET['type'] == 'specific'){global $post; $post = get_post( $_GET['post'] );}
		$POLL = pppm_get_polls( $_GET['type'], get_option('pppm_poll_first_poll') );
		if( $POLL['id'] !='' ){ $button = true; } else { $button = false; } 
		upm_polls_result($_GET['PID'], $_GET['type'], $button);
		exit();
	}
	elseif( $_GET['do'] == 'next' ){
		if( $_GET['type'] == 'specific'){global $post; $post = get_post( $_GET['post'] );}
		upm_polls( 'next' , $_GET['type'] );
		exit();
	}

}
/////////////////////////////////////////////////////////////

function upm_polls( $mode = 'default', $_type = 'general' ){
	global $wpdb; 
	global $post;
	
	if($post->ID){
		$spID = $wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."pppm_polls` WHERE `post` = ".$post->ID);
		( $spID !='' ) ? $type = 'specific' : $type = 'general';
	}
	
	if( $type == 'general' ){
	
		if( is_numeric(get_option('pppm_poll_first_poll')) ){
			if( $mode == 'default' ){
				$POLL = pppm_get_polls( 'general', 'default', get_option('pppm_poll_first_poll') );
			}
			else{
				$POLL = pppm_get_polls( 'general', 'random' );
			}
		}
		else{
			$POLL = pppm_get_polls( 'general', get_option('pppm_poll_first_poll') );
		}
	}
	else{
		if( is_numeric(get_option('pppm_poll_first_poll'))){
			$POLL = pppm_get_polls( 'specific', get_option('pppm_poll_first_poll') );
		}
		else{
			$POLL = pppm_get_polls( 'specific', 'random' );
		}
	}
	
	if( $POLL['id'] == '' ) {
		
		$type = 'general';
		
		if( is_numeric(get_option('pppm_poll_first_poll')) ){
			if( $mode == 'default' ){
				$POLL = pppm_get_polls( 'general', 'default', get_option('pppm_poll_first_poll') );
			}
			else{
				$POLL = pppm_get_polls( 'general', 'random' );
			}
		}
		else{
			$POLL = pppm_get_polls( 'general', get_option('pppm_poll_first_poll') );
		}
	}
	
	$ach_type_array = array('jpg','jpe','jpeg','pjpeg','gif','png','bmp');
	foreach( $ach_type_array as $ach_extension ){
		if( file_exists( PPPM_1_DIR . '/upm-polls/'.$POLL['id'].'.'.$ach_extension) ){ $image = PPPM_1_DIR . '/upm-polls/'.$POLL['id'].'.'.$ach_extension; break;}
	}
	if( !file_exists(PPPM_1_DIR . '/upm-polls/'.$POLL['id'].'.'.$ach_extension) ){ $image = '/wp-content/themes/main/images/Poll-Logo-Image.jpg'; }
	
	$TMP = stripslashes(get_option('pppm_poll_form_template'));$t1 = explode('[ANSWERS-START]', $TMP);$t2 = explode('[ANSWERS-END]', $t1[1]);
	$TMP_HEADER = $t1[0];$TMP_LOOP = $t2[0];$TMP_FOOTER = $t2[1];
	$find = array('[QUESTION]', '[ANSWER]', '[ANSWER-ID]','[IMAGE]','[TEXT]');
	$replace = array($POLL['question'],'','','<img src="'.$image.'" width="268" alt="Liquor.com Poll">', htmlspecialchars(stripslashes($POLL['text'])) ); 
	$TMP_HEADER = str_replace( $find, $replace, $TMP_HEADER );
	
	if( upm_allow_1() && $POLL['id'] !='' ){	
	
		if( $mode == 'default' ):
		?>
        <style type="text/css">#upm_loading{background:center no-repeat url(<?php echo PPPM_1_PATH ?>img/loading.gif); height:17px; width:100%; display:none;}</style>
		<script type="text/javascript">//oRadio[i].value;
		function UPM_pollChecker(radio_name) { 
			var oRadio = document.upm_polls_form_content.elements[radio_name]; 
			for(var i = 0; i < oRadio.length; i++) {if(oRadio[i].checked) { return true; }} return false; 
		} 
		var f=1; var uplLimage=new Image();
		uplLimage.src='<?php echo PPPM_1_PATH ?>img/loading.gif'; 
		function Gh(){ if(UPM_pollChecker('upm_answer')){}else{alert('Please choose a valid poll answer!'); return false;} document.getElementById('upm_poll_form_submit').style.display = 'none'; document.getElementById('upm_loading').style.display = 'block';}
		function UPM_load( pid ){ 
			if( f%2 != 0 || f==1 ){
				f++;
			}
			else{ 
				f++;
				$.ajax({ type : "GET", url : "<?php echo get_bloginfo('siteurl') ?>/wp-admin/admin-ajax.php", 
						 data : "action=upm_ayax_polls_result&do=result&post=<?php echo $post->ID ?>&type=<?php echo $type ?>&PID="+pid, 
						 async   : false, success : function(responseText) { 
						 	document.getElementById('upm_polls_form-'+pid).style.display = 'none'; 
							document.getElementById('upm_polls_content-'+pid).style.display = 'block'; 
							document.getElementById('upm_polls_content-'+pid).innerHTML = responseText;}}); 
			}
		}
		function UPM_next() { 
			var responseText = $.ajax({  type : "GET", 
										 url     : "<?php echo get_bloginfo('siteurl') ?>/wp-admin/admin-ajax.php", 
										 data    : "action=upm_ayax_polls_result&do=next&post=<?php echo $post->ID ?>&type=<?php echo $type ?>", 
										 async   : false, success : function() {}}  ).responseText; 
			document.getElementById('upm_poll_box').innerHTML = responseText;
		}
        </script>
        <div id="upm_poll_box">
        <?php endif; ?>
        <div id="upm_polls_content-<?php echo $POLL['id'] ?>" style="display:none;"></div>
        <div id="upm_polls_form-<?php echo $POLL['id'] ?>">
            <form id="upm_polls_form_content" name="upm_polls_form_content" action="<?php echo PPPM_1_PATH ?>polling.php" target="upm_multi_polls-<?php echo $POLL['id'] ?>" method="post" onsubmit="return Gh()">
                <input type="hidden" name="upm_action" value="polling" /><input type="hidden" name="type" value="<?php echo $type ?>" /><input type="hidden" name="upm_poll_id" value="<?php echo $POLL['id'] ?>" />
                <?php echo $TMP_HEADER; 
				if(is_array($POLL['answers']) && !empty($POLL['answers']) ) {
					foreach( $POLL['answers'] as $item ): 
					$replace = array(stripslashes($POLL['question']), stripslashes($item['answer']),  $item['id']); 
					echo str_replace( $find, $replace, $TMP_LOOP ); 
					endforeach; 
					echo str_replace( array('[POLL-ID]','[NEXT-POLL]'), array($POLL['id'],' | <a href="javascript:UPM_next()">Next Poll</a></p>'), $TMP_FOOTER ); 
				}
				else{
					echo "There aren't any polls";
				}
				?>
            </form>
        	<iframe src="<?php echo PPPM_1_PATH ?>js/blank.html" name="upm_multi_polls-<?php echo $POLL['id'] ?>" style="margin:0px; border:0px; overflow:hidden; outline:0px; width:0px; height:0px;" frameborder="0" onload="UPM_load(<?php echo $POLL['id'] ?>)" scrolling="no"></iframe>
        </div>
        <?php
		if( $mode == 'default' ):
		?>
		</div>
		<?php
		endif;
		
	}
	else{
		?>
        <style type="text/css">#upm_loading{height:17px; width:100%; display:none; text-align:center;}</style>
		<script type="text/javascript">var f=1; var uplLimage=new Image();uplLimage.src='<?php echo PPPM_1_PATH ?>img/loading.gif'; function Gh(){document.getElementById('upm_poll_form_submit').style.display = 'none'; document.getElementById('upm_loading').style.display = 'block';}function UPM_load( pid ){ if( f%2 != 0 || f==1 ){f++;}else{ f++;$.ajax({ type : "GET", url : "<?php echo get_bloginfo('siteurl') ?>/wp-admin/admin-ajax.php", data    : "action=upm_ayax_polls_result&do=result&post=<?php echo $post->ID ?>&type=<?php echo $type ?>&PID="+pid, async   : false, success : function(responseText) { document.getElementById('upm_polls_form-'+pid).style.display = 'none'; document.getElementById('upm_polls_content-'+pid).style.display = 'block'; document.getElementById('upm_polls_content-'+pid).innerHTML = responseText;}}); }}function UPM_next() { var responseText = $.ajax({  type : "GET", url     : "<?php echo get_bloginfo('siteurl') ?>/wp-admin/admin-ajax.php", data    : "action=upm_ayax_polls_result&do=next&post=<?php echo $post->ID ?>&type=<?php echo $type ?>", async   : false, success : function() {}}  ).responseText; document.getElementById('upm_poll_box').innerHTML = responseText;}</script>
        <div id="upm_poll_box">
        <?php 
		$upm_polls = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls` WHERE `post` = ".$post->ID );
		foreach( $upm_polls as $poll){
			$metaData = unserialize(stripslashes($poll->meta));
			if($metaData['status']) $spp[] = $poll->id;
		}
		$pid = $spp[@array_rand($spp)];
		( $pid ) ? $type = 'specific' : $type = 'general';
		upm_polls_result($pid, $type, false); 
		?>
        </div>
        <?php 
	}
}


function upm_polls_result($poll_id, $type = 'general', $next_button = true, $full_access = false ){
		error_reporting(0);
		global $wpdb;
		$at = false;
		if( $type == 'admin' ){ $at = true; $type = 'general'; }
		if( $type == 'adminS' ){ $at = true; $type = 'specific'; }
		
		if( $poll_id != '' ){
			$POLL = pppm_get_polls( $type, 'default', $poll_id, true, $full_access );
		}
		else{
		
			if( $type == 'general' ){
				if( is_numeric(get_option('pppm_poll_first_poll'))){
					$POLL = pppm_get_polls( 'general', 'default', get_option('pppm_poll_first_poll'), true , $full_access);
				}
				else{
					$POLL = pppm_get_polls( 'general', 'random', 0, true, $full_access );
				}
			}
			else{
				if( is_numeric(get_option('pppm_poll_first_poll'))){
					$POLL = pppm_get_polls( 'specific', 'random', get_option('pppm_poll_first_poll'), true, $full_access );
				}
				else{
					$POLL = pppm_get_polls( 'specific', get_option('pppm_poll_first_poll'), 0, true, $full_access );
				}
			}
		}
		
		$poll_id = $POLL['id'];
		
		if( $at ){
			$TMP = '<div class="upm_polls">
					<ol class="upm_poll_ul">
					[ANSWERS-START]
					<li class="upm_poll_form_list">
					<span class="upm_poll_result_title">[ANSWER]</span>
					<span  class="upm_poll_result_text">([V%], [V#] Votes)</span>
					<div class="upm_pollbar" style="background:[POLLBAR-BG]; width:[POLLBAR-WIDTH]; height:8px"></div>
					</li>
					[ANSWERS-END]
					</ol>
					<div class="upm_poll_footer"> <strong>Total Votes: [TOTAL-VOTERS]</strong> </div>
					</div>';
		}
		else{
			$TMP = stripslashes(get_option('pppm_poll_results_template'));
		}
		$t1 = explode('[ANSWERS-START]', $TMP);
		$t2 = explode('[ANSWERS-END]', $t1[1]);
		$TMP_HEADER = $t1[0];
		$TMP_LOOP = $t2[0];
		$TMP_FOOTER = $t2[1];
		$TMP_HEADER = str_replace( '[QUESTION]', stripslashes($POLL['question']), $TMP_HEADER );
		echo $TMP_HEADER;
		$pnum = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid` = ".intval($poll_id) ));
		foreach( $POLL['answers'] as $item ){  
			$num = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid` = ".intval($poll_id)." AND `item_id` = ".$item['id']));
			$W = ceil((($num/$pnum)*100));
			$N = (($num/$pnum)*100);
			$find = array('[ANSWER]', '[ANSWER-ID]', '[POLLBAR-BG]', '[POLLBAR-WIDTH]', '[POLLBAR-HEIGHT]', '[TOTAL-VOTERS]', '[V%]', '[V#]');
			if( $W == 0 ) $W = 1;
			///////////////////////////////////////////////////////////////////////////////////////////////////
			if( get_option('pppm_poll_bgtype') ){
				$bg = "url('".get_option('pppm_poll_bg_url')."');";
			}
			else{
				$bg = "#".get_option('pppm_poll_bg_color').";";
			}
			$H = trim(get_option('pppm_poll_height'));
			$replace = array( stripslashes($item['answer']),  $item['id'], $bg, $W.'%', $H.'px', $pnum, round($N,2).'%', $num); 
			///////////////////////////////////////////////////////////////////////////////////////////////////
			echo str_replace( $find, $replace, $TMP_LOOP );
		} 
		
		if( get_option('pppm_poll_onoff_next') && $next_button ) { 
			$_next = ' | <a href="javascript:UPM_next()" class="upm_next_poll">Next Poll</a>' ;
		}
		else{ 
			$_next = '';
		}
		$_find = array('[TOTAL-VOTERS]','[POLL-ID]','[NEXT-POLL]');
		$_replace = array($pnum, $poll_id, $_next); 
		if( $POLL == false ){
			return false;
		}
		else{
			echo str_replace( $_find, $_replace, $TMP_FOOTER );
		}

}

function pppm_get_polls( $type = 'general', $mode = 'default', $poll_id = 0, $extra = false, $full_access = false ){
		
		global $wpdb;
		$g = 0; $s = 0;
		global $post; 
		
		if( $poll_id  ) { 
			$q_sql = " WHERE `id` = $poll_id ";
		}
		else{
			$q_sql = "";
		}
		
		( $mode == "latest" ) ? $ad = 'DESC' : $ad = 'ASC';
		
		$upm_polls = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls` $q_sql ORDER BY `id` ".$ad);
		foreach( $upm_polls as $poll):
			$metaData = unserialize(stripslashes($poll->meta));
			if( $_GET['_page'] ){
				$_end = true;
				$_start = true;
			}
			else{
				if( (intval(time()) < intval($poll->end)) || $poll->end == 0 ) { $_end = true; } else { $_end = false; }
				if( (intval(time()) > intval($poll->start)) || $poll->start == 0 ){ $_start = true; } else { $_start = false; }
			}
			
			if( ($metaData['status'] && $_start && $_end) || $full_access ){
				
				if( $post->ID == $poll->post ){
						$POLLS['specific'][$s]['id'] = $poll->id;
						$POLLS['specific'][$s]['question'] = stripslashes($poll->question);
						$POLLS['specific'][$s]['start'] = $poll->start;
						$POLLS['specific'][$s]['start_date'] = date('d/n/Y',$poll->start);
						$POLLS['specific'][$s]['end'] = $poll->end;
						$POLLS['specific'][$s]['end_date'] = date('d/n/Y',$poll->end);
						$POLLS['specific'][$s]['post'] = $poll->post;
						$POLLS['specific'][$s]['text'] = stripslashes($poll->text);
						$POLLS['specific'][$s]['meta'] = unserialize($poll->meta);
						$upm_answers = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls_items` WHERE `qid` = ".$poll->id." ORDER BY `id` ASC");
						foreach( $upm_answers as $item):
							$POLLS['specific'][$s]['answers'][] = get_object_vars($item);
						endforeach;
						$s++;
				}
				elseif( $poll->post && $post->ID != $poll->post && !$full_access ){
					//nothing
				}
				else{
					$POLLS['general'][$g]['id'] = $poll->id;
					$POLLS['general'][$g]['question'] = stripslashes($poll->question);
					$POLLS['general'][$g]['start'] = $poll->start;
					$POLLS['general'][$g]['start_date'] = date('d/n/Y',$poll->start);
					$POLLS['general'][$g]['end'] = $poll->end;
					$POLLS['general'][$g]['end_date'] = date('d/n/Y',$poll->end);
					$POLLS['general'][$g]['text'] = stripslashes($poll->text);
					$POLLS['general'][$g]['meta'] = unserialize($poll->meta);
					$upm_answers = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls_items` WHERE `qid` = ".$poll->id." ORDER BY `id` ASC");
					foreach( $upm_answers as $item):
						$POLLS['general'][$g]['answers'][] = get_object_vars($item);
					endforeach;
					$g++;
				}
			}
		endforeach;
		#########################################################################
		global $user_ID;
		$UID = intval($user_ID);
		$IP = $_SERVER['REMOTE_ADDR'];
		$EXDATE = upm_get_seconds();
		$logging = get_option('pppm_poll_logging');
		
		switch( $logging ){
			case '2' : $Lsql = "`ip` = '$IP'  AND " ; break;
			case '3' : $Lsql = "`user_id` = '$UID'  AND " ; break;
			case '4' : $Lsql = "`ip` = '$IP'  AND " ; break;
			default: $Lsql = "" ; 
		}
		
		if( is_array($POLLS[$type]) && !empty($POLLS[$type]) ){
			foreach( $POLLS[$type] as $k => $pollData ){
			
				if( $logging == 2 || $logging == 3 ){
					$lastVDate = $wpdb->get_var("SELECT `time` FROM `".$wpdb->prefix."pppm_polls_votes` WHERE ".$Lsql." `qid` = '".$pollData['id']."' order by `time` DESC LIMIT 1");
					if( (time() - intval($lastVDate)) > $EXDATE ) { $_tmp[$k] = $pollData['id']; $_keys[] = $k; }
				}
				elseif( $logging == 1 ){
					if( $_COOKIE['_upm-polls-'.$pollData['id']] == 1 ){ $cAllow = false; } else { $_tmp[$k] = $pollData['id']; $_keys[] = $k; }
				}
				elseif( $logging == 4 ){
					if( $_COOKIE['_upm-polls-'.$pollData['id']] == 1 ){ $cAllow = false; } else { $cAllow = true; }
					$lastVDate = $wpdb->get_var("SELECT `time` FROM `".$wpdb->prefix."pppm_polls_votes` WHERE ".$Lsql." `qid` = '".$pollData['id']."' order by `time` DESC LIMIT 1");
					if( (time() - intval($lastVDate)) > $EXDATE ){ $ipAllow = true; } else { $ipAllow = false; }
					if( !$cAllow || !$ipAllow ) { $gl = false; } else { $_tmp[$k] = $pollData['id']; $_keys[] = $k; }
				}
				else{
					$_tmp[$k] = $pollData['id']; $_keys[] = $k;
				}
			}
		}
		else{
			return false;
		}
		
		#########################################################################
		//exit(print_r($_tmp));
		if( $mode == 'random' ){
			if( $logging == 0 || $extra ){
				return $POLLS[$type][@array_rand($POLLS[$type])];
			}
			else{
				return $POLLS[$type][@array_rand($_tmp)];
			}
			
		}
		elseif( $mode == 'all' ){
				return $_tmp;
		}
		else{
		
			if( $logging == 0 || $extra ){
				return $POLLS[$type][0];
			}
			else{
				return $POLLS[$type][$_keys[0]];
			}
		}
}

class PPPM_Poll_Widget extends WP_Widget {
    
    function PPPM_Poll_Widget() {
        parent::WP_Widget(false, $name = 'UPM Polls');	
    }
	
	function ACH_Widget_Function(){
		//
	}
	
    function widget($args, $instance) {	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		echo $before_widget; 
		if ( $title ) echo $before_title . $title . $after_title; 
		
		global $post;
		#######################
		upm_polls();
		#######################
		
	    echo $after_widget;
    }
    function update($new_instance, $old_instance) {	
        return $new_instance;
    }
    function form($instance) {		
        $title = esc_attr($instance['title']);
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
				name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				</label>
			</p>
            <?php
    }
} 


if(get_option('pppm_onoff_poll_manager')){
	add_action('widgets_init', create_function('', 'return register_widget("PPPM_Poll_Widget");'));
}
?>