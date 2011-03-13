<?php
	global $wpdb;
	
	######################################################################################################
	if( $_POST[ 'pppm_hidden' ] == 'pppm_poll_add' ) {
	
		$_start = @explode( '/', $_POST['pppm_poll_date_start'] ); $sec_start = @mktime( 0, 0, 0, $_start[1], $_start[0],$_start[2]);
		$_end = @explode( '/', $_POST['pppm_poll_date_end'] ); $sec_end = @mktime( 0, 0, 0, $_end[1], $_end[0],$_end[2]);
		( $_POST['pppm_poll_type'] ) ? $post = intval($_POST['pppm_poll_post']) : $post = 0 ;
		$metaData = array( 'status' => 1 );
		$meta = serialize( (array)$metaData );
		$wpdb->query("INSERT INTO `".$wpdb->prefix."pppm_polls` VALUES( NULL, '". $wpdb->escape($_POST['pppm_poll_question']) ."', 
																			  '". $sec_start ."',
																			  '". $sec_end ."',
																			  '". $post ."',
																			  '". $wpdb->escape($meta) ."' )");
		$QID = mysql_insert_id();
		foreach( $_POST['pppm_poll_answers'] as $answer){
			$wpdb->query("INSERT INTO `".$wpdb->prefix."pppm_polls_items` VALUES( NULL, '". intval($QID) ."', '". $wpdb->escape($answer) ."', '' )");
		}
		
		echo '<div class="updated"><p><strong>'. __( 'Done!' ) .'</strong></p></div>';
	}
	######################################################################################################
	if( $_POST[ 'pppm_hidden' ] == 'pppm_poll_edit' ) {
	
		$_start = @explode( '/', $_POST['pppm_poll_date_start'] ); $sec_start = @mktime( 0, 0, 0, $_start[1], $_start[0],$_start[2]);
		$_end = @explode( '/', $_POST['pppm_poll_date_end'] ); $sec_end = @mktime( 0, 0, 0, $_end[1], $_end[0],$_end[2]);
		( $_POST['pppm_poll_type'] ) ? $post = intval($_POST['pppm_poll_post']) : $post = 0 ;
		$wpdb->query("UPDATE `".$wpdb->prefix."pppm_polls` SET `question` = '". $wpdb->escape($_POST['pppm_poll_question']) ."', 
															   `start` = '". $sec_start ."',
															   `end` = '". $sec_end ."',
															   `post` = '". $post ."' 
															    WHERE `id` = ".intval($_POST['poll_id']));
		$QID = $_POST['poll_id'];
		foreach( $_POST['pppm_poll_answers'] as $id => $answer ){
			$_item = $wpdb->get_row("SELECT `id` FROM `".$wpdb->prefix."pppm_polls_items` WHERE `id` = ". intval($id) ." AND `qid` = ".intval($QID) , ARRAY_A );
			if( $_item['id'] ){
				if( $_POST['pppm_poll_answers_remove'][$id] == 1 ){
					$wpdb->query("DELETE FROM `".$wpdb->prefix."pppm_polls_items` WHERE `id` = ". intval($id) ." AND `qid` = ".intval($QID));
				}
				else{
					$wpdb->query("UPDATE `".$wpdb->prefix."pppm_polls_items` SET `answer` = '". $wpdb->escape($answer) ."' WHERE `id` = ". intval($id) ." AND `qid` = ".intval($QID));
				}
			}
			elseif( $answer !='' ){
				$wpdb->query("INSERT INTO `".$wpdb->prefix."pppm_polls_items` VALUES( NULL, '". intval($QID) ."', '". $wpdb->escape($answer) ."', '' )");
			}
		}
		
		echo '<div class="updated"><p><strong>'. __( 'Done!' ) .'</strong></p></div>';
	}
	######################################################################################################
	if( ($_GET[ 'do' ] == 'close' || $_GET[ 'do' ] == 'open') && $_GET[ 'id' ] != ''  ) {
		( $_GET[ 'do' ] == 'close' ) ? $status = 0 : $status = 1 ;
		$_metaData = $wpdb->get_var("SELECT `meta` FROM `".$wpdb->prefix."pppm_polls` WHERE `id` = ". intval($_GET[ 'id' ]) );
		$metaData = unserialize(stripslashes($_metaData));
		$metaData['status'] = $status ;
		$meta = serialize( (array)$metaData );
		$wpdb->query( "UPDATE `".$wpdb->prefix."pppm_polls` SET `meta` = '". $wpdb->escape($meta) ."' WHERE `id` = ".intval($_GET[ 'id' ]) );
		echo '<div class="updated"><p><strong>'. __( 'Done!' ) .'</strong></p></div>';
	}
	######################################################################################################
	if( $_GET[ 'do' ] == 'delete' && $_GET[ 'id' ] != '' ){
		$wpdb->query("DELETE FROM `".$wpdb->prefix."pppm_polls` WHERE `id` = ". intval($_GET['id']) );
		$wpdb->query("DELETE FROM `".$wpdb->prefix."pppm_polls_items` WHERE `qid` = ". intval($_GET['id']) );
		$wpdb->query("DELETE FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid` = ". intval($_GET['id']) );
		echo '<div class="updated"><p><strong>'. __( 'Done!' ) .'</strong></p></div>';
	}
	######################################################################################################
	if( $_POST[ 'pppm_hidden' ] == 'pppm_poll_template' ) {
		if( $_POST['Default'] != '' ){
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
</div> ';

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
		}
		else{
			update_option('pppm_poll_form_template', $_POST['pppm_poll_form_template'] );
			update_option('pppm_poll_results_template', $_POST['pppm_poll_results_template'] );
		}
	}
	######################################################################################################
?>
<style type="text/css">
.pppm_box_td_cell{
	padding:5px; 
	text-align:center;
}
.pppm_box_td_subcell{
	border:solid 1px #EAEAEA;
	background:#FFFFFF;
	border-collapse:collapse;
	padding:2px; 
	text-align:center;
}
._polls{
	text-decoration:none;
}
</style>	
<script type="text/javascript" src="<?php echo PPPM_1_PATH ?>js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo PPPM_1_PATH ?>js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo PPPM_1_PATH ?>js/jquery-dynamic-form.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#duplicate3g").dynamicForm("#plus3g", "#minus3g", {limit:10, createColor: 'yellow',removeColor: 'red'});
    });
</script>
<script type="text/javascript" src="<?php echo PPPM_1_PATH ?>js/date.js"></script>
<script type="text/javascript" src="<?php echo PPPM_1_PATH ?>js/jquery.datePicker.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo PPPM_1_PATH ?>css/datePicker.css">
<style type="text/css">
a.dp-choose-date {float: left;width: 16px;height: 16px;padding: 0;margin: 5px 3px 0; display: block;text-indent: -2000px;overflow: hidden;background: url(<?php echo PPPM_1_PATH ?>img/list.png) no-repeat;}
a.dp-choose-date.dp-disabled { background-position: 0 -20px;cursor: default;}
input.dp-applied {width: 140px;float: left;}
</style>
<script type="text/javascript" charset="utf-8">
    $(function(){
        $('#date1').datePicker(); $('#date1').dpSetStartDate('01/01/2000'); 
								  $('#date1').dpSetSelected('<?php echo date("d/n/Y") ?>');
								  $('#date2').datePicker(); $('#date2').dpSetStartDate('01/01/2000'); 
    });
</script>
<br />
<table width="100%" border="0" cellspacing="1" class="pppm_option_table">
  <tr>
    <td class="pppm_table_td">
	<div class="pppm_top_desc">
		<?php _e('Here you can manage polling options of your posts, pages specific polls and site ( general ) polls .') ?>
	</div>
	</td>
  </tr>
</table> 
<br />
