    <div>
    
		<h3><?php _e('扩展', 'mp-ukagaka'); ?></h3>
		
		<p><?php _e('如果您不懂如何操作或编写代码，请不要更改此页。', 'mp-ukagaka'); ?></p>
		
		<form method="post" name="setting" id="setting" action="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename('mp-ukagaka/options.php'); ?>&cur_page=3">
		
			<div style="height:1px;background:#DFDFDF;width:400px;"></div>
			
			<h4><?php _e('JS区', 'mp-ukagaka'); ?></h4>
			
			<p>
				<?php _e('可在此填写JavaScript代码，为春菜自定义更多的响应事件。', 'mp-ukagaka'); ?>
				<br/>
				<?php _e('无需使用&lt;script&gt;标签，代码将写入到&lt;head&gt;部分。', 'mp-ukagaka'); ?>
			</p>
			
			<p><textarea rows="8" cols="40" id="js_area" name="extend[js_area]" class="resizable" style="line-height:130%;"><?php echo $mpu_opt['extend']['js_area']; ?></textarea></p>
			
			<p><input name="submit4" class="button" value="<?php _e(' 保 存 ', 'mp-ukagaka'); ?>" type="submit" /></p>
			
			<div style="height:1px;background:#DFDFDF;width:400px;"></div>
			
			<h4><?php _e('代码扩展', 'mp-ukagaka'); ?></h4>
			
			<p>
				<?php _e('你可以在春菜的信息框中使用特殊代码来显示特定的信息，例如日志列表。', 'mp-ukagaka'); ?>
				<br/>
				<?php _e('访问插件首页便可获得所有代码的信息：', 'mp-ukagaka'); ?>
				<a href="http://blog.lolily.com/wordpress-plugin-mp-ukagaka.html#extension" title="Lo极乐园"><?php _e('访问插件首页', 'mp-ukagaka'); ?></a>
			</p>
		
		</form>
		
	</div>