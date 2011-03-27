<?php
/*
Plugin Name: MP Ukagaka
Plugin URI: http://blog.lolily.com/wordpress-plugin-mp-ukagaka.html
Plugin Description: Create your own ukagakas.
Version: 1.5.1
Author: Ariagle
Author URI: http://blog.lolily.com/
*/

/**
 * 载入语言文件
 */
load_plugin_textdomain('mp-ukagaka', "/wp-content/plugins/mp-ukagaka/languages/");

/**
 * 定义
 */
define('MPU_VERSION', '1.5.1');

/**
 *获取配置
 */
$mpu_opt = get_option('mp_ukagaka');
if (!is_array($mpu_opt)) {
	// 若不存在，则创建默认配置
	mpu_default_opt();
}

/**
 * 创建默认配置
 */
function mpu_default_opt() {
	global $mpu_opt;
	$mpu_opt = array(
		'cur_ukagaka' => 'default_1',
		'show_ukagaka' => true,
		'show_msg' => true,
		'default_msg' => 0,
		'next_msg' => 0,
		'click_ukagaka' => 0,
		'no_style' => false,
		'insert_html' => 0,
		'auto_msg' => '',
		'common_msg' => '',
		'no_page' => '',
		'ukagakas' => array(
			'default_1' => array(
				'name' => __('初音', 'mp-ukagaka'),
				'shell' => plugins_url('mp-ukagaka/images/shell/shell_1.png'),
				'msg' => array(__('欢迎光临～', 'mp-ukagaka')),
				'show' => true
			)
		),
		'extend' => array(
			'js_area' => ''
		)
	);
	update_option('mp_ukagaka', $mpu_opt);
}

/**
 * AJAX响应部分
 */
function mpu_ajax() {
	global $mpu_opt;
	if($_GET['action'] == 'mpu_nextmsg') {
	
		// 下一条信息
		$msg = mpu_get_next_msg($_GET['cur_num'], $_GET['cur_msgnum']);
		$msgnum = mpu_get_msg_key($_GET['cur_num'], $msg);
		$arr = array('msg'=>$msg, 'msgnum'=>$msgnum);
		echo json_encode($arr);
		die();
		
	} elseif($_GET['action'] == 'mpu_extend') {
	
		// 扩展列表
		echo '<a onclick="mpuChange(\'\')" href="javascript:void(0);">'.__('更换春菜','mp-ukagaka').'</a>';
		die();
		
	} elseif($_GET['action'] == 'mpu_change') {
		
		// 切换春菜
		
		if (!isset($_GET['mpu_num'])) {
			// 获取春菜列表
			$ukagaka_list = mpu_ukagaka_list();
			echo $ukagaka_list;
		} else {
			// 获取指定春菜
			if (isset($mpu_opt['ukagakas'][$_GET['mpu_num']])) { $mpu_num = $_GET['mpu_num']; } else { $mpu_num = 'default_1'; }
			$temp['msglist'] = mpu_get_msg_arr($mpu_num);
			$temp['shell'] = $mpu_opt['ukagakas'][$mpu_num]['shell'];
			$temp['msg'] = $temp['msglist']['msg'][0];
			$temp['name'] = $mpu_opt['ukagakas'][$mpu_num]['name'];
			$temp['num'] = $mpu_num;
			$temp['msglist'] = json_encode($temp['msglist']);
			// 写入cookie
			setcookie('mpu_ukagaka_'.COOKIEHASH, $mpu_num);
			echo json_encode($temp);
		}
		die();
	
	}
	
}
add_action('init', 'mpu_ajax');

/**
 * 判断写入HTML的方式
 */
if (mpu_is_show_page()) {
	// 处理通用会话。通用会话只有在显示春菜时（前台）应用，在后台（不显示春菜）时不覆盖各春菜的自定义信息。
	mpu_common_msg();
	if ($mpu_opt['insert_html']==0) {
		// 通过ob_start
		insert_html_by_bo();
	} elseif ($mpu_opt['insert_html']==1) {
		// 通过wp_footer
		insert_html_by_wpfooter();
	}
} else {
	remove_filter('wp_footer', 'mpu_echo_html');
}

/**
 * 判断是否为可显示春菜的页面
 */
function mpu_is_show_page() {
	global $mpu_opt;
	$show = TRUE;
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	//print_r($_SERVER);
	$arr = mpu_str2array($mpu_opt['no_page']);
	if (is_admin()) { $show = false; }
	if (is_feed()) { $show = false; }
	if (str_replace('wp-login.php', '', $_SERVER["PHP_SELF"])!=$_SERVER["PHP_SELF"]) { $show = false; }
	foreach ($arr as $value) {
		if (substr($value,-3,3)=='(*)') {
			$value = str_replace('(*)', '', $value);
			if (str_replace($value, '', $url)!=$url) { $show = false;break; }
		} else {
			if ($value==$url) { $show = false;break; }
		}
	}
	// 移动设备
	if (mpu_is_browser('iphone') or mpu_is_browser('blackberry') or mpu_is_browser('symbian') or mpu_is_browser('windows ce') or mpu_is_browser('sonyericsson')) {
		$show = false;
	}
	return $show;
}

/**
 * 在wp_footer处插入春菜
 */
function insert_html_by_wpfooter() {
	add_filter('wp_footer', 'mpu_echo_html');
}

/**
 * 在页面底部插入春菜
 */
function insert_html_by_bo() {
	//if (!is_admin() and !is_feed() and str_replace('wp-login.php', '', $_SERVER["PHP_SELF"])==$_SERVER["PHP_SELF"]) {
		ob_start('mpu_ob_callback');
		register_shutdown_function('mpu_shutdown_callback');
	//}
}

function mpu_ob_callback($buffer) {
	$html = mpu_html();
	$buffer = preg_replace('/<\/body>/', $html."\n</body>", $buffer);
	return $buffer;
}

function mpu_shutdown_callback() {
	ob_end_flush();
	flush();
}

/**
 * 获取可用春菜列表
 */
function mpu_ukagaka_list() {
	global $mpu_opt;
	foreach ($mpu_opt['ukagakas'] as $key => $value) {
		if ($value['show']) {
			$ukagakas[] = $key;
		}
	}
	if ($ukagakas) {
		foreach ($ukagakas as $key => $value) {
			$html .= '<span><a onclick="mpuChange(\''.$value.'\')" href="javascript:void(0);">'.mpu_output_filter($mpu_opt['ukagakas'][$value]['name']).'</a></span> ';
		}
		$html = '<div class="ukagaka-list"><p>'.__('春菜们', 'mp-ukagaka').':</p>'.$html.'</div>';
	} else {
		$html = __('没有可供选择的春菜', 'mp-ukagaka');
	}
	return $html;
}

/**
 * 获取指定春菜的完整信息
 */
function mpu_get_ukagaka($num = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	if (!isset($mpu_opt['ukagakas'][$name])) {
		$ukagaka = $mpu_opt['ukagakas'][$mpu_opt['cur_ukagaka']];
	} else {
		$ukagaka = $mpu_opt['ukagakas'][$name];
	}
	return $ukagaka;
}

/**
 * 获取春菜图片
 * $num : 若为false则使用默认春菜，否则使用以$num为键名的春菜
 */
function mpu_get_shell($num = false, $echo = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	$shell = $mpu_opt['ukagakas'][$name]['shell'];
	if ($echo==false) {
		return $shell;
	} else {
		echo $shell;
	}
}

/**
 * 获取春菜的一条信息
 */
function mpu_get_msg($msgnum = 0, $num = false, $echo = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	$msg = $mpu_opt['ukagakas'][$name]['msg'][$msgnum];
	if ($echo==false) {
		return $msg;
	} else {
		echo $msg;
	}
}

/**
 * 随机获取春菜的一条信息
 */
function mpu_get_random_msg($num = false, $echo = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	$total = count($mpu_opt['ukagakas'][$name]['msg']);
	srand();
	$msgnum = ceil(rand(1, $total) - 1);
	$msg = mpu_get_msg($msgnum, $num, false);
	if ($echo==false) {
		return $msg;
	} else {
		echo $msg;
	}
}

/**
 * 依据设定读取默认信息
 */
function mpu_get_default_msg($num = false, $echo = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	if ($mpu_opt['default_msg']==0) {
		// 随机
		$msg = mpu_get_random_msg($num, false);
	} else {
		// 第一条
		$msg = mpu_get_msg(0, $num, false);
	}
	if ($echo==false) {
		return $msg;
	} else {
		echo $msg;
	}
}

/**
 * 设置通用会话
 */
function mpu_common_msg() {
	global $mpu_opt;
	// 如果通用会话不为空，那么将通用会话取代所有春菜的自定义会话
	if ($mpu_opt['common_msg']!='') {
		foreach ($mpu_opt['ukagakas'] as $key => $value) {
			$mpu_opt['ukagakas'][$key]['msg'] = mpu_str2array($mpu_opt['common_msg']);
		}
	}
}

/**
 * 获取排列顺序的信息
 */
function mpu_get_msg_arr($num = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	$ukagaka = mpu_get_ukagaka($num);
	$msgall = mpu_count_msg($num);
	$arr = array(
		'msgall' => $msgall,
		'auto_msg' => $mpu_opt['auto_msg'],
		'msg' => array()
	);
	$arr['msg'][0] = mpu_get_default_msg($num, false);
	if ($mpu_opt['default_msg']==0) {
		// 第一条随机
		if ($mpu_opt['next_msg']==0) {
			// 下一条按顺序
			$cur_key = array_search($arr['msg'][0], $ukagaka['msg']);
			foreach ($ukagaka['msg'] as $key => $value) {
				if ($key<$cur_key) {
					$arr['msg'][$msgall+1-($cur_key-$key)] = $value;
				}
				if ($key>$cur_key) {
					$arr['msg'][$key-$cur_key] = $value;
				}
			}
		} else {
			// 下一条随机
			while (true) {
				$temp = mpu_get_random_msg($num, false);
				if (!in_array($temp, $arr['msg'])) {
					$arr['msg'][] = $temp;
				}
				if (count($arr['msg'])>=$msgall+1) { break; }
			}
		}
	} else {
		// 第一条按顺序
		if ($mpu_opt['next_msg']==0) {
			// 下一条按顺序
			$arr['msg'] = $mpu_opt['ukagakas'][$num]['msg'];
		} else {
			// 下一条随机
			while (true) {
				$temp = mpu_get_random_msg($num, false);
				if (!in_array($temp, $arr['msg'])) {
					$arr['msg'][] = $temp;
				}
				if (count($arr['msg'])>=$msgall+1) { break; }
			}
		}
	}
	$arr['msg'] = mpu_msg_code($arr['msg']);
	$arr['auto_msg'] = implode(' ', mpu_msg_code(array($arr['auto_msg'])));
	foreach ($arr['msg'] as $key => $value) {
		$arr['msg'][$key] = mpu_js_filter($value);
	}
	$arr['auto_msg'] = mpu_js_filter($arr['auto_msg']);
	return $arr;
}

/**
 * 获取春菜的下一条信息
 */
function mpu_get_next_msg($num = false, $msgnum = 0) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	if ($mpu_opt['next_msg']==0) {
		// 下一条
		$next = $msgnum + 1;
		if (isset($mpu_opt['ukagakas'][$name]['msg'][$next])) {
			$msg = $mpu_opt['ukagakas'][$name]['msg'][$next];
		} else {
			$msg = $mpu_opt['ukagakas'][$name]['msg'][0];
		}
	} else {
		// 随机
		$msg = mpu_get_random_msg($num, false);
	}
	return $msg;
}

/**
 * 替换信息中的特殊代码
 * 同一行中有多于一个的n个代码的，会将信息分成n条，每条作用一个代码
 * recentpost  : 最新日志列表，每篇日志在独立信息框中显示
 * recentposts : 最新日志列表，在信息框中显示整个日志列表
 * randompost  : 随机日志列表，每篇日志在独立信息框中显示
 * randomposts : 随机日志列表，在信息框中显示整个日志列表
 */
function mpu_msg_code($msglist = array()) {
	$templist = array();
	foreach ($msglist as $key => $value)
	{
		$do_replace = false;
		while (true) {
			if (preg_replace('/\(:(recentpost|recentposts|randompost|randomposts)\[(\d*)\]\)/', '', $value)!=$value) {
				// 日志类
				// 获取须显示的日志数量、显示的类型并将其赋值给$num,$type，默认为5,recent
				$num = array();
				preg_match('/\(:(recentpost|recentposts|randompost|randomposts)\[(\d*)\]\)/', $value, $num);
				//print_r($num);
				$type = $num[1];
				$num = $num[2];
				if ($type=='randompost' or $type=='randomposts') { $orderby = 'rand'; } else { $orderby = 'ID'; }
				if ($num<=0) { $num = 5; }
				// 配置参数并获取日志
				$args = array(
					'numberposts' => $num,
					'orderby' => $orderby
				);
				$posts = get_posts($args);
				$postlist = array();
				$html = '';
				foreach ($posts as $post) {
					$post = (array)$post;
					$temp = '<a href="'.get_permalink($post['ID']).'" title="'.get_the_title($post['ID']).'">'.get_the_title($post['ID']).'</a>';
					$postlist[] = $temp;
					$html .= ($html=='')?$temp:'<br/>'.$temp;
				}
				// 获取只含当前运行代码的$value1，即去除非当前代码
				$value1 = '(:'.$type.'['.$num.'])';
				$value1 = str_replace($value1, '(:no_replace)', $value);
				$value1 = preg_replace('/\(:(recentpost|recentposts|randompost|randomposts)\[(\d*)\]\)/', '', $value1);
				$value1 = str_replace('(:no_replace)', '(:'.$type.'['.$num.'])', $value1);
				// 写入信息
				if ($type=='recentpost' or $type=='randompost') {
					// 每篇日志在单独信息框中显示
					foreach ($postlist as $post) {
						$templist[] = preg_replace('/\(:('.$type.')\['.$num.'\]\)/', $post, $value1);
					}
				} else {
					// 在单独信息框中显示日志列表
					$templist[] = preg_replace('/\(:('.$type.')\['.$num.'\]\)/', $html, $value1);
				}
				// 消除$value中已生效的code
				$value = preg_replace('/\(:('.$type.')\['.$num.'\]\)/', '', $value);
				$do_replace = true;
			} elseif (preg_replace('/\(:recentcomments\[(\d*)\]\)/', '', $value)!=$value) {
				// 评论类
				if ($do_replace==false) { $templist[] = $value; }
				break;
			} else {
				if ($do_replace==false) { $templist[] = $value; }
				break;
			}
		}
	}
	return $templist;
}

/**
 * 寻找当前信息的位置
 */
function mpu_get_msg_key($num = false, $msg = '') {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	$msgnum = array_search($msg, $mpu_opt['ukagakas'][$name]['msg']);
	if ($msgnum===false) {
		// 找不到，则返回0
		$msgnum = 0;
	}
	return $msgnum;
}

/**
 * 统计春菜信息数量
 */
function mpu_count_msg($num = false) {
	global $mpu_opt;
	if ($num===false) { $name = $mpu_opt['cur_ukagaka']; } else { $name = $num; }
	return count($mpu_opt['ukagakas'][$name]['msg']) - 1;
}

/**
 * 统计所有惊喜数量
 */
function mpu_count_total_msg() {
	global $mpu_opt;
	$n = 0;
	foreach ($mpu_opt['ukagakas'] as $key => $value) {
		$n += count($value['msg']);
	}
	return $n;
}

/**
 * 生成HTML代码
 */
function mpu_html($num = false) {
	global $mpu_opt;
	if ($num===false) {
		// 从cookie中读取默认春菜
		if ( isset($_COOKIE['mpu_ukagaka_'.COOKIEHASH]) ) {
			$num = $_COOKIE['mpu_ukagaka_'.COOKIEHASH];
			if (!isset($mpu_opt['ukagakas'][$num])) { $num = false; }
		}
	}
	$ukagaka_num = (($num===false)?$mpu_opt['cur_ukagaka']:$num);
	$msglist = mpu_get_msg_arr($ukagaka_num);
	$ukagaka = mpu_get_ukagaka($ukagaka_num);
	$html = '
<!-- START mpu -->
<div id="mp_ukagaka">
	<div id="ukagaka_shell">
		<div id="ukagaka">
			<div id="ukagaka_msgbox">
				<div id="ukagaka_msg">'.mpu_html_decode($msglist['msg'][0].$msglist['auto_msg']).'</div>
				<div id="ukagaka_msgnum" style="display:none;">0</div>
				<div id="ukagaka_msglist" style="display:none;">'.json_encode($msglist).'</div>
				<div class="ukagaka-msgbox-border"></div>
			</div>
			<div id="ukagaka_img"><img id="cur_ukagaka" title="'.mpu_output_filter($ukagaka['name']).'" alt="'.mpu_output_filter($ukagaka['name']).'" src="'.mpu_get_shell($ukagaka_num, false).'" /></div>
			<div id="ukagaka_num" style="display:none;">'.$ukagaka_num.'</div>
		</div>
		<div class="mpu-clear"></div>
		<div class="ukagaka-dock">
			<a id="show_ukagaka" href="javascript:void(0);">'.__('隐藏春菜 ▼', 'mp-ukagaka').'</a> | 
			<a id="show_msg" href="javascript:void(0);">'.__('隐藏会话 ▼', 'mp-ukagaka').'</a> | 
			<a id="mpu_extend" href="javascript:void(0);">'.__('扩展', 'mp-ukagaka').'</a>
		</div>
	</div>
</div>';
	$html .= "<script type=\"text/JavaScript\">\n";
	$html .= '
	var showRobot = mpu_getCookie("mpuRobot");
	var showMsg = mpu_getCookie("mpuMsg");
	//alert(showRobot);
	if (showRobot==null) {';
	if ($mpu_opt['show_ukagaka']!=1) {
		$html .= '
		jQuery("#show_ukagaka").html(mpuInfo["robot"][0]);
		jQuery("#ukagaka").fadeOut(500);';
	}
	$html .= '
	} else if (showRobot=="hidden") {
		jQuery("#show_ukagaka").html(mpuInfo["robot"][0]);
		jQuery("#ukagaka").fadeOut(500);
	}
	if (showMsg==null) {';
	if ($mpu_opt['show_msg']!=1) {
		$html .= '
		jQuery("#show_msg").html(mpuInfo["msg"][0]);
		jQuery("#ukagaka_msgbox").fadeOut(500);';
	}
	$html .= '
	} else if (showMsg=="hidden") {
		jQuery("#show_msg").html(mpuInfo["msg"][0]);
		jQuery("#ukagaka_msgbox").fadeOut(500);
	}
	//mpu_delCookie("mpuRobot");
	//mpu_delCookie("mpuMsg");
	';
	$html .= "\n</script>";
	$html .= "\n<!-- END mpu -->";
	return $html;
}


/**
 * 输出HTML
 */
function mpu_echo_html() {
	$html = mpu_html();
	echo $html;
}

/**
 * 将春菜的所有信息由数组转成文本
 */
function mpu_array2str($arr = array()) {
	$n = 0;
	if ($arr) {
		foreach ($arr as $key => $value) {
			if ($n == count($arr)-1) {
				$str .= $value;
			} else {
				$str .= $value . "\n\n";
			}
			$n++;
		}
	}
	return $str;
}

/**
 * 将春菜的所有信息由文本转成数组
 */
function mpu_str2array($str = '') {
	$arr = array();
	if ($str) {
		$str = preg_split("/\r|\n/",stripslashes( $str ));
		foreach ($str as $key => $value) {
			if (trim($value)!='') {
				$arr[] = $value;
			}
		}
	}
	return $arr;
}

/**
 * 输出前过滤特殊字符
 */
function mpu_output_filter($str) {
	// 将特殊字符转换成HTML代码
	$str = htmlspecialchars($str);
	// 将换行转换成HTML代码
	$str = nl2br($str);
	// 将两个以上的空格转换成HTML代码
	$str = str_replace('  ', '&nbsp;&nbsp;', $str);
	return $str;
}

/**
 * JS特殊字符过滤
 */
function mpu_js_filter($str) {
	$str = htmlspecialchars($str);
	$str = str_replace('&quot;', 'quot;', $str);
	return $str;
}

/**
 * 输入前过滤字符
 */
function mpu_input_filter($str) {
	// 去除多余的反斜杠
	$str = str_replace('\\"', '"', $str);
	$str = str_replace("\\'", "'", $str);
	return $str;
}

/**
 * 还原HTML代码
 */
function mpu_html_decode($str) {
	$str = str_replace('&amp;', '&', $str);
	$str = str_replace('&quot;', '"', $str);
	$str = str_replace('quot;', '"', $str);
	$str = str_replace('&#039;', '\'', $str);
	$str = str_replace('&lt;', '<', $str);
	$str = str_replace('&gt;', '>', $str);
	return $str;
}

/**
 * 判断浏览器类型
 */
function mpu_is_browser($target = '') {
	$ua = strtolower( $_SERVER['HTTP_USER_AGENT'] );
	if (str_replace($target,'',$ua)!=$ua) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * 载入顶部文件
 */
function mpu_head() {
	global $mpu_opt;
	echo "<!-- START MP-Ukagaka -->\n";
	echo "<script type=\"text/JavaScript\">
		var mpuurl = '".get_bloginfo('siteurl')."';
		var mpuInfo = new Array();
		mpuInfo['robot'] = new Array('".__('显示春菜 ▲', 'mp-ukagaka')."', '".__('隐藏春菜 ▼', 'mp-ukagaka')."');
		mpuInfo['msg'] = new Array('".__('显示会话 ▲', 'mp-ukagaka')."', '".__('隐藏会话 ▼', 'mp-ukagaka')."');";
	if ($mpu_opt['click_ukagaka']==0) {
		echo '	var mpuClick = "next"; ';
	} elseif ($mpu_opt['click_ukagaka']==1) {
		echo '	var mpuClick = "no"; ';
	}
	echo "\n".$mpu_opt['extend']['js_area']."\n";
	echo "</script>\n";
	echo "<script src=\"".plugins_url('mp-ukagaka/ukagaka.js')."?v=".MPU_VERSION."\" type=\"text/JavaScript\"></script>\n";
	if (!$mpu_opt['no_style']) {
		echo "<link href=\"" . plugins_url('mp-ukagaka/mpu_style.css') . "?v=".MPU_VERSION."\" rel=\"stylesheet\" type=\"text/css\" />\n";
	}
	echo "<!-- END MP-Ukagaka -->\n";
}
add_filter('wp_head', 'mpu_head');
wp_enqueue_script('jquery');

/**
 * 加载后台选项
 */
function mpu_options() {
	if (function_exists('add_options_page')) {
		add_options_page(__('MP Ukagaka 选项', 'mp-ukagaka'), 'MP-Ukagaka', 9, 'mp-ukagaka/options.php');
	}
}
add_action('admin_menu', 'mpu_options');

?>