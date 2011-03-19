<?php
/*
Plugin Name: CTGPS Sohu Photo Album
Description: Allows CTGPS logined user to login sohu photo album automatically for photo upload/manager. Adds the corresponding items at bp adminbar.
Author: Dinosoft dinosoft@qq.com
Version: 0.1
*/

function ctgps_sohu_photo_album_auto_login(){
   if ( !is_user_logged_in() ){?> 
       <div id="message"><p>无权访问该页!</p></div>
   <?php 
   }else{?>

            <script language="JavaScript" type="text/javascript">
            window.onload=function()
            {
            
            	  window.location="http://pp.sohu.com/member/redheadship1";
            
            };
            
            </script>    
            <iframe id="login" style="display:none " src="http://passport.sohu.com/sso/login.jsp?userid=redheadship2%40sohu.com&password=c53ff13d9d038438d11717ed46c5fff6&appid=1019&persistentcookie=0&isSLogin=1&s=1299483499657&b=2&w=1280&pwdtype=1&v=26" ></iframe>
            
           
   <?php 
   }
}
 
add_shortcode("ctgps-photo-ablum", "ctgps_sohu_photo_album_auto_login");



// **** "Photo Album" Menu *********
function ctgps_bp_adminbar_photo_album_menu() {
	global $bp;

	

		 

    ?>
    
  
 
    <li id="bp-adminbar-photo-album-menu" >
    <a href="http://pp.sohu.com/member/redheadship1">搜狐相册</a>
	<ul>

	<li><a href="http://pp.sohu.com/member/redheadship" target="_blank">相册1(旧)</a></li>
	<li><a href="http://pp.sohu.com/member/redheadship1" target="_blank">相册2(新)</a></li>
	<?php	if ( is_user_logged_in() ): ?>
	<li><a href="<?php echo get_home_url() ?>/photo-upload-guideline">上传指南</a></li>
	<li><a href="<?php echo get_home_url() ?>/connect-to-sohu-photo-album" target="_blank">我要上传</a></li>	
    <?php endif; ?>
	</ul>
	</li>
<?php 
}

add_action( 'bp_adminbar_menus', 'ctgps_bp_adminbar_photo_album_menu',   6  );
