<?php
$base_name = plugin_basename('mp-ukagaka/options.php');
$base_page = 'options-general.php?page='.$base_name;
$text = '';

//echo $base_name.' - '.$base_page.' - '.$_SERVER['PHP_SELF'];die();
//print_r($mpu_opt);die();
//echo '<br/>';
//print_r($_POST);

// 获取当前页面
$cur_page = $_GET['cur_page'];
if (!is_numeric($cur_page) or ($cur_page<0 or $cur_page>4) or $cur_page == '') { $cur_page = 0; }

if ($_GET['del']!='') {

	// 删除春菜
	$del = $_GET['del'];
	if ($del==str_replace('default','',$del)) {
		if (isset($mpu_opt['ukagakas'][$del])) {
			$name = $mpu_opt['ukagakas'][$del]['name'];
			unset($mpu_opt['ukagakas'][$del]);
			update_option('mp_ukagaka', $mpu_opt);
			$text .= (($name=='')?__('春菜', 'mp-ukagaka'):$name).__('已离你而去…', 'mp-ukagaka');
		} else {
			$text .= __('不存在此春菜哟', 'mp-ukagaka');
		}
	} else {
		// 不允许删除默认春菜
		$text .= __('不允许赶走默认春菜哟', 'mp-ukagaka');
	}
	
} elseif (isset($_POST['submit1'])) {

	// 一般设置
	$show_ukagaka = $_POST['show_ukagaka'];
	$show_msg = $_POST['show_msg'];
	$default_msg = $_POST['default_msg'];
	$next_msg = $_POST['next_msg'];
	$click_ukagaka = $_POST['click_ukagaka'];
	$cur_ukagaka = $_POST['cur_ukagaka'];
	$no_style = $_POST['no_style'];
	$no_page = $_POST['no_page'];
	if ($show_ukagaka) { $show_ukagaka = true; } else { $show_ukagaka = false; }
	if ($show_msg) { $show_msg = true; } else { $show_msg = false; }
	if ($no_style) { $no_style = true; } else {$no_style = false; }
	//if (!$cur_ukagaka) { $cur_ukagaka = 'default_1'; }
	$mpu_opt['show_ukagaka'] = $show_ukagaka;
	$mpu_opt['show_msg'] = $show_msg;
	$mpu_opt['default_msg'] = $default_msg[0];
	$mpu_opt['next_msg'] = $next_msg[0];
	$mpu_opt['click_ukagaka'] = $click_ukagaka[0];
	$mpu_opt['cur_ukagaka'] = $cur_ukagaka;
	$mpu_opt['no_style'] = $no_style;
	$mpu_opt['no_page'] = mpu_input_filter($no_page);
	if (isset($_POST['insert_html'])) {
		$insert_html = $_POST['insert_html'];
		$mpu_opt['insert_html'] = (int)$insert_html[0];
	}
	update_option('mp_ukagaka', $mpu_opt);
	$text .= __('设定已保存', 'mp-ukagaka');
	
} elseif (isset($_POST['submit2'])) {

	// 更改春菜
	$ukagakas = $_POST['ukagakas'];
	foreach ($ukagakas as $key => $value) {
		$ukagakas[$key]['msg'] = mpu_str2array($ukagakas[$key]['msg']);
		$ukagakas[$key]['name'] = mpu_input_filter($ukagakas[$key]['name']);
		$ukagakas[$key]['shell'] = mpu_input_filter($ukagakas[$key]['shell']);
		if ($ukagakas[$key]['show']) { $ukagakas[$key]['show'] = true; } else { $ukagakas[$key]['show'] = false; }
	}
	$mpu_opt['ukagakas'] = $ukagakas;
	update_option('mp_ukagaka', $mpu_opt);
	$text .= __('春菜们已经焕然一新啦', 'mp-ukagaka');
	
} elseif (isset($_POST['submit3'])) {

	// 创建春菜
	$ukagaka = $_POST['ukagaka'];
	$ukagaka['msg'] = mpu_str2array($ukagaka['msg']);
	$ukagaka['name'] = mpu_input_filter($ukagaka['name']);
	$ukagaka['shell'] = mpu_input_filter($ukagaka['shell']);
	$mpu_opt['ukagakas'][] = $ukagaka;
	if (is_array($mpu_opt['ukagakas'][0])) {
		// 去除键名为0的元素
		$mpu_opt['ukagakas'][] = $mpu_opt['ukagakas'][0];
		unset($mpu_opt['ukagakas'][0]);
	}
	update_option('mp_ukagaka', $mpu_opt);
	$text .= __('春菜创建成功～', 'mp-ukagaka');
	
} elseif (isset($_POST['submit4'])) {

	// 扩展
	$extend = $_POST['extend'];
	$extend['js_area'] = mpu_input_filter($extend['js_area']);
	$mpu_opt['extend'] = $extend;
	update_option('mp_ukagaka', $mpu_opt);
	$text .= __('设定已保存', 'mp-ukagaka');
	
} elseif (isset($_POST['submit5'])) {

	// 会话
	$auto_msg = $_POST['auto_msg'];
	$common_msg = $_POST['common_msg'];
	$mpu_opt['auto_msg'] = mpu_input_filter($auto_msg);
	$mpu_opt['common_msg'] = mpu_input_filter($common_msg);
	update_option('mp_ukagaka', $mpu_opt);
	$text .= __('设定已保存', 'mp-ukagaka');
	
} elseif (isset($_POST['submit_reset'])) {

	// 重置设定
	if ($_POST['reset_mpu']) {
		unset($mpu_opt);
		update_option('mp_ukagaka', $mpu_opt);
		mpu_default_opt();
		$text .= __('设定已重置', 'mp-ukagaka');
	} else {
		$text .= __('设定没有被重置', 'mp-ukagaka');
	}

}
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url('mp-ukagaka/jquery.textarearesizer.compressed.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('textarea.resizable:not(.processed)').TextAreaResizer();
		$('iframe.resizable:not(.processed)').TextAreaResizer();
	});
</script>
<style type="text/css">
			div.grippie {
				background:#EEEEEE url(<?php echo plugins_url('mp-ukagaka/images/grippie.png'); ?>) no-repeat scroll center 2px;
				border-color:#DDDDDD;
				border-style:solid;
				border-width:0pt 1px 1px;
				cursor:s-resize;
				height:9px;
				overflow:hidden;
			}
			.resizable-textarea textarea {
				display:block;
				margin-bottom:0pt;
				height: 20%;
			}
</style>
<div class="wrap">

	<?php screen_icon(); ?>
	
    <h2><?php _e('MP Ukagaka 选项', 'mp-ukagaka'); ?></h2>
    
    <?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
    
    <div style="font-weight:bold;">
    
    	<a style="text-decoration:none;<?php if ($cur_page==0) { echo 'color:#000000;'; } ?>" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename(__FILE__); ?>&cur_page=0"><?php _e('通用设置', 'mp-ukagaka'); ?></a> | 
    	<a style="text-decoration:none;<?php if ($cur_page==4) { echo 'color:#000000;'; } ?>" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename(__FILE__); ?>&cur_page=4"><?php _e('会话', 'mp-ukagaka'); ?></a> | 
    	<a style="text-decoration:none;<?php if ($cur_page==1) { echo 'color:#000000;'; } ?>" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename(__FILE__); ?>&cur_page=1"><?php _e('春菜们', 'mp-ukagaka'); ?></a> | 
    	<a style="text-decoration:none;<?php if ($cur_page==2) { echo 'color:#000000;'; } ?>" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename(__FILE__); ?>&cur_page=2"><?php _e('创建新春菜', 'mp-ukagaka'); ?></a> | 
    	<a style="text-decoration:none;<?php if ($cur_page==3) { echo 'color:#000000;'; } ?>" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename(__FILE__); ?>&cur_page=3"><?php _e('扩展', 'mp-ukagaka'); ?></a>
    
    </div>
    
	<?php if ($cur_page==0) { // 通用设置 ?>
	
		<?php require_once('options_page0.php'); ?>

    <?php } ?>

    <?php if ($cur_page==1) { // 春菜们 ?>

		<?php require_once('options_page1.php'); ?>

    <?php } ?>
    
    <?php if ($cur_page==2) { // 创建新春菜 ?>
    
    	<?php require_once('options_page2.php'); ?>

    <?php } ?>
    
	<?php if ($cur_page==3) { // 扩展 ?>

		<?php require_once('options_page3.php'); ?>
	
	<?php } ?>
	
	<?php if ($cur_page==4) { // 会话 ?>

		<?php require_once('options_page4.php'); ?>
	
	<?php } ?>
    
</div><!-- END wrap -->