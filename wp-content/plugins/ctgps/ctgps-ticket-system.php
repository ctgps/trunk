<?php

/*
Plugin Name: CTGPS Ticket System
Description: Coach ticket booking system fot CTGPS
Version: 0.0.1
Author: Dinosoft dinosoft@qq.com
*/

session_start();

require_once( dirname(__FILE__)."/ctgps-ticket-system-frontend.php");

add_action('admin_menu', 'ctgps_ticket_system');

function ctgps_ticket_system() {
	add_dashboard_page('票务管理', '票务管理', 'read', 'ctgps_ticket_manage', 'ctgps_ticket_login');
}

function ctgps_ticket_show_login_panel()
{
?>
<?php  if ( isset($_POST['ctgps_login']) && isset($_POST['ctgps_password']) ) :?>
<div id="message" class="updated fade" ><p>登录失败~~</p></div>
<?php endif;?>
<div class="wrap" style="width:300px; margin-left:auto; margin-right:auto;" >
<div class="icon32" id="icon-ms-admin"><br></div>

<h2>登陆票务后台管理系统</h2>
<div >
<form method="post" >
<table   class="widefat">

<thead>
<tr>
<th>用户名：</th>
<td><input type="text" name="ctgps_login"/></td>
</tr>

<tr>
<th>密码：</th>
<td><input type="password" name="ctgps_password"/></td>
</tr>
<tr>

<td colspan=2 style="text-align:center">
<input type="submit" value="&nbsp;&nbsp;登录&nbsp;&nbsp;" />
</td>
</tr>
</thead>
</table>
</form>
</div>

</div>
<?php    
}

function ctgps_ticket_show_info(){
    global $wpdb;
    
    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    $sql = "CREATE TABLE  `{$wpdb->prefix}ctgps_ticket_info` (
              `coachname` varchar(30) NOT NULL,
              `setofftime` varchar(20) NOT NULL,
              `price` varchar(10) NOT NULL,
              `ticketnum` varchar(10) NOT NULL,
              `deadline` varchar(20) NOT NULL,
              `coachnum` varchar(20) NOT NULL,
              `getonadd` varchar(30) NOT NULL,
              `getoffadd` varchar(30) NOT NULL,
              `linkman` varchar(10) NOT NULL,
              `phone` varchar(20) NOT NULL,
              `ps` varchar(300) NOT NULL
            ){$charset_collate} ";
    
 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    $result = $wpdb->get_results("select * from `{$wpdb->prefix}ctgps_ticket_info`");
    if ( !$result[0] ) {
        $sql = "INSERT INTO `{$wpdb->prefix}ctgps_ticket_info` 
        (`coachname`, 
        `setofftime`, 
        `price`, 
        `ticketnum`, 
        `deadline`, 
        `coachnum`, 
        `getonadd`, 
        `getoffadd`, 
        `linkman`, 
        `phone`, 
        `ps`) VALUES
		('20XXX假XX包车', 
		'20XX年X月X日上午9:00', 
		'120元', 
		'未知', 
		'20XX年X月X日', 
		'未知', 
		'澄海花园酒店', 
		'东校区饭堂十字路口', 
		'XX', 
		'150175XXXXX', 
		'附言')";

        $wpdb->query( $sql );
    }
    
    $result = $wpdb->get_results("select * from `{$wpdb->prefix}ctgps_ticket_info`");
    $result = $result[0];
?>
<h3>车次信息</h3>
<table width="200" border="0"  class="widefat fixed">
  <thead>
  <tr>
    <th width="90">车次名称：</th>
    <td  ><?php echo $result->coachname?></td>
  </tr>
  <tr>
    <th>出发时间：</th>
    <td><?php echo $result->setofftime?></td>
  </tr>
  <tr>
    <th>车票价格：</th>
    <td><?php echo $result->price?></td>
  </tr>
  <tr>
    <th>车票数量：</th>
    <td><?php echo $result->ticketnum?></td>
  </tr>
  <tr>
    <th>截止时间：</th>
    <td><?php echo $result->deadline?></td>
  </tr>
  <tr>
    <th>车牌号码：</th>
    <td><?php echo $result->coachnum?></td>
  </tr>
  <tr>
    <th>上车地点：</th>
    <td><?php echo $result->getonadd?></td>
  </tr>
  <tr>
    <th>下车地点：</th>
    <td><?php echo $result->getoffadd?></td>
  </tr>
  <tr>
    <th>联系人：</th>
    <td><?php echo $result->linkman?></td>
  </tr>
  <tr>
    <th>核实手机：</th>
    <td><?php echo $result->phone?></td>
  </tr>
  <tr>
    <th>附言：</th>
    <td><?php echo $result->ps?></td>
  </tr>
</thead>
</table>  
<?php  
return $result;  
}


function ctgps_ticket_check_login()
{
    if ( isset($_SESSION['ctgps_login']) && isset($_SESSION['ctgps_password'])
        && $_SESSION['ctgps_login'] =="sysu" && $_SESSION['ctgps_password']=="chenghai" ){
           return true;
    }
    return false;
}

function ctgps_ticket_login()
{
    if ( isset($_POST['ctgps_login']) && isset($_POST['ctgps_password']) ) {
            $_SESSION['ctgps_login'] =$_POST['ctgps_login'];
            $_SESSION['ctgps_password']=$_POST['ctgps_password'];
    }
    
    if ( ctgps_ticket_check_login() ) {
        ctgps_ticket_manage();
    }else{
        ctgps_ticket_show_login_panel();
    }
}

function ctgps_ticket_manage() {
    /*
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	*/

    global $wpdb;
    
    if ( $_POST["modify_coach_info"]){
        
         $sql=$wpdb->prepare( "UPDATE `{$wpdb->prefix}ctgps_ticket_info`
         SET `coachname` = '%s',
        `setofftime` = '%s',
        `price` = '%s',
        `ticketnum` = '%s',
        `deadline` = '%s',
        `coachnum` = '%s',
        `getonadd` = '%s',
        `getoffadd` = '%s',
        `linkman` = '%s',
        `phone` = '%s',
        `ps` = '%s' WHERE `coachname` like '%%' ",
         $_POST[coachname],$_POST[setofftime], $_POST[price],$_POST[ticketnum],
         $_POST[deadline],$_POST[coachnum], $_POST[getonadd],$_POST[getoffadd],
         $_POST[linkman],  $_POST[phone], $_POST[ps] );
         
         $modify_coach_info=$wpdb->query($sql);
        
    }
    
    if ( $_POST["reset_booking"]){
     $wpdb->query("truncate table `{$wpdb->prefix}ctgps_ticket_book`");
     $reset_booking = true;
    }

?> 

    


<div class="wrap" >
<div class="icon32" id="icon-users"><br></div>

<h2>票务管理</h2>
<?php if ( $modify_coach_info ):?>
<div id="message" class="updated fade"><p>修改车次信息成功!</p></div>
<?php endif?>
<?php if ( $reset_booking ):?>
<div id="message" class="updated fade"><p>清除所有订票成功!</p></div>
<?php endif?>

<div style="float:left; margin-left:30px;  width: 400px; clear: none;">
<?php $result = ctgps_ticket_show_info(); ?>
</div>
  
  
<div  style="float:left; margin-left:30px; width: 400px; clear: none;" >
<h3>信息修改</h3> 
<form  method="post"   >
<table width="300" border="0"  cellspacing="0" class="widefat fixed">
<thead>
  <tr>
    <th width="100">车次名称：</th>
    <td ><input type='text' name="coachname" value='<?php echo $result->coachname ?>' /> </td>
  </tr>
  <tr>
    <th>出发时间：</th>
    <td><input type='text' name="setofftime" value='<?php echo $result->setofftime ?>'/></td>
  </tr>
  <tr>
    <th>车票价格：</th>
    <td><input type='text' name="price" value='<?php echo $result->price ?>'/></td>
  </tr>
  <tr>
    <th>车票数量：</th>
    <td><input type='text' name="ticketnum" value='<?php echo $result->ticketnum ?>'/></td>
  </tr>
  <tr>
    <th>截止时间：</th>
    <td><input type='text' name="deadline" value='<?php echo $result->deadline ?>'/></td>
  </tr>
  <tr>
    <th>车牌号码：</th>
    <td><input type='text' name="coachnum" value='<?php echo $result->coachnum ?>'/></td>
  </tr>
  <tr>
    <th>上车地点：</th>
    <td><input type='text' name="getonadd" value='<?php echo $result->getonadd ?>'/></td>
  </tr>
  <tr>
    <th>下车地点：</th>
    <td><input type='text' name="getoffadd" value='<?php echo $result->getoffadd ?>'/></td>
  </tr>
  <tr>
    <th>联系人：</th>
    <td><input type='text' name="linkman" value='<?php echo $result->linkman ?>'/></td>
  </tr>
  <tr>
    <th>核实手机：</th>
    <td><input type='text' name="phone" value='<?php echo $result->phone ?>'/></td>
  </tr>
  <tr>
    <th>附言：</th>
    <td><textarea name="ps" ><?php echo $result->ps?></textarea></td>
  </tr>
  <tr>
  <td colspan=2 style="text-align:center">
  <input type="submit" id='sub' name="modify_coach_info" value="修改车次信息" />
  </td>
  </tr>
</thead>
</table>
</form>
</div>
<?php 
    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    $sql = "CREATE TABLE  `{$wpdb->prefix}ctgps_ticket_book` (
              `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `uid` int(11) DEFAULT NULL,
              `name` varchar(20) NOT NULL,
              `password` varchar(100),
              `ticketnum` int(11) NOT NULL,
              `tel` varchar(200),
              `short_tel` varchar(10),
              `ps` varchar(300)
            ){$charset_collate} ";
    
    dbDelta( $sql );
    $peoples = $wpdb->get_results("select * from `{$wpdb->prefix}ctgps_ticket_book`");
    //peoples = $peoples ? count($peoples)  : 0;
    
    $num_ticket = $wpdb->get_results("select sum(ticketnum)as sum from `{$wpdb->prefix}ctgps_ticket_book`");
    //$num_ticket = $num_ticket[0]->num ? $num_ticket[0]->sum : 0;
    
?>
<form method="post" onsubmit="return window.confirm('确定要清除所有订票吗?&nbsp;该操作无法撤销,请先确定相关资料已保存.')">
<h2 style="clear:both">订票详细信息
<input class="button add-new-h2"  name="reset_booking" type="submit" value="清除所有订票" />
</h2>
</form>

<div style="clear:both; margin:0 0 30px 0" >
<p>总订票数: (<?php echo( $num_ticket[0]->sum )?>) | 订票人数: (<?php print_r(count($peoples)) ?>)</p>
<table cellspacing="0" class="widefat fixed" >
<thead>
<tr>
<th width="4%">序号</th>
<th width="10%">姓名</th>
<th width="5%">票数</th>
<th width="15%">手机</th>
<th width="10%">短号</th>
<th>附言</th>
</tr>
<?php 



$i=1;
foreach ($peoples as $people)
{
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $people->name ?></td>
<td><?php echo $people->ticketnum ?></td>
<td><?php echo $people->tel ?></td>
<td><?php echo $people->short_tel ?></td>
<td><?php echo $people->ps ?></td>
</tr>
<?php 
}
?>
<tr>
</tr>
</thead>
</table>
</div>

</div> <!-- end of wrap -->
<?php 
}
