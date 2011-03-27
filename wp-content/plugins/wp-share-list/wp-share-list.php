<?php
/*
Plugin Name: wp-share-list
Plugin URI: http://www.36ria.com/2217
Description: 在文章末尾生成一个文章分享站点列表，你可以自由的控制分享站点显示数量、顺序等，同时不再经过第三方跳转，点击分享站点直接到达分享站点页面。
Version: 1.6.1.2
Requires at least: 2.1
Tested up to: 3.0.1
Author: 明河共影
Author URI: http://www.36ria.com/
*/
/* Copyright 2010  明河共影  (email:riahome@126.com)*/
include("include/ShareList.php");
//实例化类
if(class_exists("ShareList")){
	$SL = new ShareList();
}
//生成管理页面
if(!function_exists('add_share_list_options_page')){
	function add_share_list_options_page(){
		global $SL;
		if(!isset($SL)){
			return ;
		}
		if(function_exists('add_options_page')){
			add_options_page('wp-share-list','收藏分享列表',9,basename ( __FILE__ ),array (&$SL, 'printAdminPage' ));
		}
	}
}
function wp_share_list(){
	global $SL;
	echo $SL->printContainer();
}
if(isset($SL)){
	add_action("wp_head",array (&$SL, 'addJs' ));
	add_action('the_content', array (&$SL, 'addContent' ));
	add_action ('wp-share-list/wp-share-list.php ' , array (&$SL , 'init' )) ;
	add_action('admin_menu','add_share_list_options_page');
}

?>