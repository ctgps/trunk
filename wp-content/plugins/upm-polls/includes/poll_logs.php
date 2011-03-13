<?php 
require_once( '../../../../wp-load.php' );
wp();

$ref = parse_url( $_SERVER['HTTP_REFERER'] );
if( $_SERVER["HTTP_HOST"] != $ref['host'] ){
	exit('UPM Error:128');
}
global $wpdb;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Poll Logs</title>
<style type="text/css">
body{
	font-size:13px;
	margin:2px;
}
a{
font-size:14px;
text-decoration:none;
color:#21759B;}
td{
background:#FFFFFF;
text-align:center;
padding:2px;
}
th{
background:#F7F7F7;
text-align:center;
padding:3px;
}
.upm_poll_footer{
	font-size:14px;
	padding-left:25px;
}
.upm_polls{
	border:#CCCCCC 1px solid; 
	margin:2px; 
	padding-bottom:10px;
	padding-right:10px;
}
</style>
</head>
<body>
<?php 
$QID = $_GET['qid'];
$POLL = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."pppm_polls` WHERE `id` = $QID", ARRAY_A); 
if( $_GET['mode'] == 'general' ) { 
	$t = 'admin'; 
} 
else{ 
	$t = 'adminS';
	global $post; 
	$post = get_post( $POLL['post'] );
	$pTitle = ' &nbsp;| Post/Page Title: <span style="color:#000066">"'.$post->post_title.'"</span>';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
  <tr>
    <td colspan="2" style="background:#F5F5F5; text-align:left; font-size:14px; font-weight:bold; padding:2px;">
    &nbsp;Question:<span style="color:#006600"> <?php echo $POLL['question'] ?></span> <?php echo $pTitle ?></td>
  </tr>
  <tr>
    <td rowspan="2" style="text-align:left; padding:2px;background:#F5F5F5; vertical-align:top;">
    <?php upm_polls_result($QID,$t,false,true); ?>
    </td>
    <td style="padding:5px;">
    <!-- Logs -->
        <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#CCCCCC">
          <tr>
            <th style="text-align:left;">Answers</th>
            <th>User</th>
            <th>IP | Host</th>
            <th>Date</th>
          </tr>
        <?php 
        $i = 1;
        $item_res = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls_items` WHERE `qid`=$QID ORDER BY `id` ASC ");
        foreach( $item_res as $item ){
            $answers[$i] = $item->answer;
            $anum[$item->id] = $i;
            $i++;
        }
		
		if( $paged == '' ) $paged = $_GET['_page'];
			
		if( $paged == '' ) $paged = 1;
		$per_page = 10;
		$ach_num = mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid`=$QID"));
		$pages = ceil($ach_num/$per_page);
			
        $poll_res = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid`=$QID ORDER BY `id` DESC LIMIT ".( ($paged-1) * $per_page).",$per_page ");
        foreach ( $poll_res as $_res ) :
            //$host = @gethostbyaddr($_res->ip);
            $user = get_userdata($_res->user_id);
            ?>
            <tr>
            <td style="text-align:left" title="<?php echo 'Answer #'.$anum[$_res->item_id].' : '.$answers[$anum[$_res->item_id]] ?>">
            &nbsp;<?php echo $anum[$_res->item_id].'. &nbsp;'.$answers[$anum[$_res->item_id]] ?>
            </td>
            <td><?php ($user->user_login) ? print($user->user_login) : print('<span style="color:#999">guest</span>') ; ?></td>
            <td><?php echo $_res->ip ?></td>
            <td><?php echo date('d/n/Y H:i:s',$_res->time) ?></td>
            </tr>
            <?php
        endforeach;
        ?>
        </table>
    <!-- Logs END -->
    </td>
  </tr>
  <tr>
    <td style="background:#F5F5F5; padding:4px; font-size:14px">
    	<?php 
			if( $paged > 1 && $pages > 1 ): ?>
            &nbsp;&nbsp; 
            <a href="<?php echo PPPM_1_PATH .'includes/poll_logs.php?_page='.($paged-1).'&qid='.$QID.'&mode='.$_GET['mode'] ?>">&laquo; Previous</a>
			<?php endif; ?>
            
            <?php echo ' | '.$paged.' / '.$pages.' | '; ?>
            
			<?php
			if( $pages > $paged && $pages > 1 ):
			?>
            <a href="<?php echo PPPM_1_PATH .'includes/poll_logs.php?_page='.($paged+1).'&qid='.$QID.'&mode='.$_GET['mode'] ?>">Next  &raquo;</a>
			<?php endif;  ?>
    </td>
  </tr>
</table>
</body>
</html>
