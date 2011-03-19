<?php

function ctgps_init_jquery_ui(){
   /*if (is_single() )*/
?>
  	<link rel="stylesheet" href="http://jqueryui.com/demos/demos.css">
  <link rel="stylesheet" href="http://jqueryui.com/themes/base/jquery.ui.all.css">
<script type='text/javascript' src='<?php echo get_home_url()?>/wp-includes/js/jquery/ui.core.js'></script> 
<script type='text/javascript' src='<?php echo get_home_url()?>/wp-includes/js/jquery/ui.tabs.js'></script>
   <script type='text/javascript'>
       /* didn't work for jquery UI ???
        jQuery(document).ready(function(){
            jQuery("#edit_ticket").click( function(){
            	jQuery("#book").toggle(300);
            });
            JQuery( "#tabs" ).tabs();
        });*/
        (function($) { 
        	  $(function() {
        		  $("#edit_ticket").click( function(){
                  	$("#book").toggle(300);
                  });
                  
                  <?php  if ( is_user_logged_in() ): ?>
                     <?php  if ( $_POST['submit_type']!='submit_notloginmod' ):  ?>
                       var $tabs = $( "#tabs" ).tabs();
                     <?php  else:?>
                      var $tabs = $( "#tabs" ).tabs( {  selected: 1 } ); 
                     <?php  endif; ?>
                  <?php else : ?>                
                  var $tabs = $( "#tabs" ).tabs( { disabled: [0], selected: 1 } );  
                  <?php endif?>
                  
                  $('#switch2notlogin').click(function() { // bind click event to link
                      $tabs.tabs('select', 1); // switch to second tab
                      return false;
                  });
        	  });
        })(jQuery);
    </script>
    <style type="text/css">
    #book input.text, #book2 input.text{
    width:150px;
    }
    </style>
    
<?php
/* didn't work, so strange ??
    wp_enqueue_script('jquery');
    wp_enqueue_script( "jquery-ui-core");	
    wp_enqueue_script( "jquery-ui-tabs");
*/
}
add_action('wp_head', 'ctgps_init_jquery_ui');


function ctgps_ticket_post_handle( &$msg ){
    global $wpdb;
    $msg = "";  
    $result= ctgps_ticket_get_ticket_info_from_uid( get_current_user_id() );
    $data =array();
    $data['ticketnum']=$_POST['ticketnum'];
    $data['tel']=$_POST['tel'];
    $data['short_tel']=$_POST['short_tel'];
    $data['ps']=$_POST['ps'];
    $data['name']=$_POST['fullname'];
        
    if ( is_user_logged_in() && $_POST['submit_type']=='submit_loginmod' ){
        
        if ( $result->ticketnum ){
            return $wpdb->update( "{$wpdb->prefix}ctgps_ticket_book", $data, array('uid' => get_current_user_id() ) );
        }else{
            $data['uid']= get_current_user_id();
            return $wpdb->insert("{$wpdb->prefix}ctgps_ticket_book", $data );
        }
                  
    }else if ( $_POST['submit_type']=='submit_notloginmod' ) {
        if ( $_POST['code']!=$_SESSION['ctgps_code'] ) {
            $msg="验证码错误!";  
            return false; 
        }
        $data['uid'] = 0;
        return $wpdb->insert("{$wpdb->prefix}ctgps_ticket_book", $data );
        
    }else if ( $_POST['submit_type']=='cancel_ticket'  ) {   
         $uid = get_current_user_id();   
         return $wpdb->query("DELETE FROM {$wpdb->prefix}ctgps_ticket_book WHERE uid = {$uid} ");
    }
    
    if ( $_POST["submit_type"] )
       $msg="系统异常~请重试!";
    return false;
    
}		
function ctgps_ticket_get_ticket_info_from_uid( $uid ){
    global $wpdb;
    $result = $wpdb->get_results( $wpdb->prepare(
             "select * from `{$wpdb->prefix}ctgps_ticket_book` where uid=%d", $uid) );
    return $result[0];
    
}

function  ctgps_ticket_frontend($atts, $content = null) {
    $msg="";  
    ctgps_ticket_post_handle( $msg );
    $tag_login = is_user_logged_in();
    $tag_submit =    isset( $_POST['submit_loginmod'] ) 
                  || isset( $_POST['submit_notloginmod'] ) ;
    $result= ctgps_ticket_get_ticket_info_from_uid( get_current_user_id() );

    $user_info = ctgps_bp_get_user_profile_array( get_current_user_id() );

   
    
    
    if ( !$result->ticketnum ) {
        $result->name = $user_info['姓名'];
        $result->tel = $user_info['手机'];
        $result->short_tel = $user_info['短号'];
    }
    
    
?>

<div id="content">

	
<div style="float:left; margin-left:30px;  width: 280px; clear: none;">
<?php ctgps_ticket_show_info(); ?>
</div>

<div id="tabs" style="float:left; margin-left:30px;  width: 350px; clear: none;">

    <ul >
        <li><a href="#ticket_booking_loginmod"><span>已登陆会员</span></a></li>
        <li><a href="#ticket_booking_notloginmod"><span>未登陆用户</span></a></li>
    </ul>
    <div id="ticket_booking_loginmod"  >
    <?php if ( $tag_login ): ?>
    <p>您现在已登陆，系统自动锁定用户姓名<br/>
    如需替他人订票，请切换到<a href='#' id="switch2notlogin">未登陆用户模式</a></p>
    

    <form method="post" >
    <?php if ( $result->ticketnum ) :?>
        <div id="booked"  >
        您已订票<?php  echo $result->ticketnum; ?>张.
        <button  type="submit" name="submit_type" value="cancel_ticket" 
          onclick="return window.confirm('确认取消订票?')" >取消订票</button> 
        <button  type="button" id="edit_ticket" name="edit_ticket">修改订票</button>  
        </div>
    <?php if ( $_POST['submit_type']=='submit_loginmod' ){
              unset($_POST);
          }
          endif; ?> 
        <div id="book" style="width:320px; <?php if ( $result->ticketnum) echo 'display:none'?>"  >
            
			<table cellspacing="0"> 
            <caption>订票信息</caption>
              <tr>
                <td width="40px">姓名：</td>
                <td >
                <?php echo $result->name?><input type="hidden" class="text" name="fullname" value="<?php echo $result->name?>" />
                </td>
              </tr>
              <tr>
                <td>票数：</td>
                <td><input type="text" name="ticketnum" class="text"  maxlength=20  value="<?php echo $result->ticketnum ? $result->ticketnum : 1; ?>"/></td>
              </tr>
              <tr>
                <td>手机：</td>
                <td><input type="text" name="tel"  class="text" maxlength=20 value='<?php echo $result->tel?>' /></td>
              </tr>
              <tr>
                <td>短号：</td>
                <td><input type="text" name="short_tel" class="text" maxlength=20 value='<?php echo $result->short_tel?>'/>(选填)</td>
              </tr>
                <tr>
                <td>附言：</td>
                <td><textarea name="ps"  class="text"><?php echo $result->ps?></textarea></td>
              </tr>
            
              <tr>
              <td colspan=2 style="text-align:center">
              <button  type="submit"   name="submit_type" value='submit_loginmod' >&nbsp;&nbsp;提交&nbsp;&nbsp;</button>
              </td>
              </tr>
              </table>    
        </div>
    </form>
    <?php   endif;   ?>
    </div>
    
    <div id="ticket_booking_notloginmod"  >
    <p>注意:未登陆用户无法修改订票信息.</p>
    <?php if ( $_POST['submit_type']=='submit_notloginmod' && $msg==""): ?>
    <div id="booked2" style="width:320px">
    <p>订票成功! 以下是您的订票信息.</p>
   		    <table cellspacing="0" > 
   		    <caption>订票信息</caption>
            <thead> </thead>
            <tbody>            
              <tr>
                <td width="40px">姓名：</td>
                <td ><?php echo $_POST['fullname']?>  </td>
              </tr>
              <tr>
                <td>票数：</td>
                <td><?php echo $_POST['ticketnum']?></td>
              </tr>
              <tr>
                <td>手机：</td>
                <td><?php echo $_POST['tel']?></td>
              </tr>
              <tr>
                <td>短号：</td>
                <td><?php echo $_POST['short_tel']?></td>
              </tr>           
                <tr>
                <td>附言：</td>
                <td><?php echo $_POST['ps']?></td>
              </tr>          
              </tbody>
              </table>
    </div>
    
    <?php else: ?>
        <?php  if ( $msg!="" ):?>
        <p class='error'><?php echo $msg;?></p>
        <?php  endif; ?>
    <div id="book2" style="width:320px">
        <form method="post">
        		<table cellspacing="0"> 
                <caption>订票信息</caption>
                  <tr>
                    <td width="50px">姓名：</td>
                    <td >
                    <input type="text" class="text" name="fullname"   />
                    </td>
                  </tr>
                  <tr>
                    <td>票数：</td>
                    <td><input type="text" name="ticketnum" class="text"  maxlength=20 value="1" /></td>
                  </tr>
                  <tr>
                    <td>手机：</td>
                    <td><input type="text" name="tel"  class="text" maxlength=20 ' /></td>
                  </tr>
                  <tr>
                    <td>短号：</td>
                    <td><input type="text" name="short_tel" class="text" maxlength=20 />(选填)</td>
                  </tr>
                  <tr>
                    <td>验证码：</td>
                    <td><img style="margin:0" src="<?php echo( home_url() )?>/wp-content/plugins/ctgps/antispam.php"/><input type="text" name="code"  size=4 maxlength=4/></td>
                  </tr>             
                    <tr>
                    <td>附言：</td>
                    <td><textarea name="ps"  class="text"></textarea></td>
                  </tr>
                
                  <tr>
                  <td colspan=2 style="text-align:center">
                  <button type="submit"  name="submit_type" value='submit_notloginmod'>&nbsp;&nbsp;订票&nbsp;&nbsp;</button> 
                  </td>
                  </tr>
                  </table>
         </form>
    </div>    
    <?php endif; ?>
    
    </div><!--  end of ticket_booking_notloginmod -->
</div>

</div><!--  end of content -->
<?php 
 unset($_POST);
}
add_shortcode("ctgps-ticket-system", "ctgps_ticket_frontend");