<?php
/*
Plugin Name: CTGPS Course Post Type
Description: add custom post type course for CTGPS
Author: Dinosoft dinosoft@qq.com
Version: 0.1
*/

add_action( 'init', 'ctgps_create_course_post_type' );
function ctgps_create_course_post_type() {
	register_post_type( 'course',
		array(
		'labels' =>  array( 
		      'name' => '课程',		
	 'add_new' =>'添加课程',
 'add_new_item' =>'添加新课程',
 'edit_item' => '编辑课程',  
 
# 'new_item' - the new item text. Default is New Post/New Page
# 'view_item' - the view item text. Default is View Post/View Page
# 'search_items' - the search items text. Default is Search Posts/Search Pages
# 'not_found' - the not found text. Default is No posts found/No pages found
# 'not_found_in_trash' - the not found in trash text. Default is No posts found in Trash/No pages found in Trash
# 'parent_item_colon' - the parent text. This string isn't used on non-hierarchical types. In hierarchical ones the default is Parent Page
# 'menu_name' - the menu name text. This string is the name to give menu items. Defaults to value of name 	      
		 ),
	    'menu_position' => 5,
		'public' => true,
		'has_archive' => false,
		'rewrite' => array('slug' => 'courses'),
		'map_meta_cap' => true,
		'supports' => array( 'title', 'custom-fields','comments')
		)
	);
}
 
