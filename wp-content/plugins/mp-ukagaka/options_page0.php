    <div>
    
		<h3><?php _e('通用设置', 'mp-ukagaka'); ?></h3>
		
		<form method="post" name="setting" id="setting" action="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename('mp-ukagaka/options.php'); ?>&cur_page=0">
		
			<p>
				<label for="cur_ukagaka"><?php _e('默认春菜：', 'mp-ukagaka'); ?></label>
				<select id="cur_ukagaka" name="cur_ukagaka">
					<?php foreach ($mpu_opt['ukagakas'] as $key => $value) { ?>
						<option value="<?php echo $key; ?>"<?php if ($key==$mpu_opt['cur_ukagaka']) { echo ' selected="selected"'; } ?>><?php echo mpu_output_filter($value['name']); ?></option>
					<?php } ?>
				</select>
			</p>
		
			<p><label for="show_ukagaka"><input id="show_ukagaka" name="show_ukagaka" type="checkbox" value="true"<?php if ($mpu_opt['show_ukagaka']) { echo ' checked="checked"'; } ?> /><?php _e('默认显示春菜', 'mp-ukagaka'); ?></label></p>
			
			<p><label for="show_msg"><input id="show_msg" name="show_msg" type="checkbox" value="true"<?php if ($mpu_opt['show_msg']) { echo ' checked="checked"'; } ?> /><?php _e('默认显示对话框', 'mp-ukagaka'); ?></label></p>
			
			<p>
				<?php _e('默认会话：', 'mp-ukagaka'); ?>
				<label><input name="default_msg[]" type="radio" value="0" <?php if ($mpu_opt['default_msg']==0) { echo ' checked="checked"'; } ?> /><?php _e('随机吐槽', 'mp-ukagaka'); ?></label>
				<label><input name="default_msg[]" type="radio" value="1" <?php if ($mpu_opt['default_msg']==1) { echo ' checked="checked"'; } ?> /><?php _e('第一条吐槽', 'mp-ukagaka'); ?></label>
			</p>
			
			<p>
				<?php _e('会话顺序：', 'mp-ukagaka'); ?>
				<label><input name="next_msg[]" type="radio" value="0" <?php if ($mpu_opt['next_msg']==0) { echo ' checked="checked"'; } ?> /><?php _e('顺序吐槽', 'mp-ukagaka'); ?></label>
				<label><input name="next_msg[]" type="radio" value="1" <?php if ($mpu_opt['next_msg']==1) { echo ' checked="checked"'; } ?> /><?php _e('随机吐槽', 'mp-ukagaka'); ?></label>
			</p>
			
			<p>
				<?php _e('点击春菜：', 'mp-ukagaka'); ?>
				<label><input name="click_ukagaka[]" type="radio" value="0" <?php if ($mpu_opt['click_ukagaka']==0) { echo ' checked="checked"'; } ?> /><?php _e('下一条吐槽', 'mp-ukagaka'); ?></label>
				<label><input name="click_ukagaka[]" type="radio" value="1" <?php if ($mpu_opt['click_ukagaka']==1) { echo ' checked="checked"'; } ?> /><?php _e('无操作', 'mp-ukagaka'); ?></label>
			</p>
			
			<p><label><input type="checkbox" id="no_style" name="no_style" value="true"<?php if ($mpu_opt['no_style']) { echo ' checked="checked"'; } ?>/><?php _e('使用自定义样式', 'mp-ukagaka'); ?></label></p>
			
			<?php if (isset($_GET['mpu_mode'])) { ?>
			
			<div style="height:1px;background:#DFDFDF;width:400px;"></div>
			
			<p>
				<?php _e('HTML生成位置：', 'mp-ukagaka'); ?> (<?php _e('一般情况下请不要更改它们', 'mp-ukagaka'); ?>)
				<br/>
				<label><input type="radio" name="insert_html[]" value="0"<?php if ($mpu_opt['insert_html']==0) { echo ' checked="checked"'; } ?> /><?php _e('在< / body>前。', 'mp-ukagaka'); ?></label> <?php _e('当春菜无法显示（主题尾部无wp_footer()函数）或wp_footer()所在之处使得春菜样式无法正常表示，请勾选此项', 'mp-ukagaka'); ?>
				<br/>
				<label><input type="radio" name="insert_html[]" value="1"<?php if ($mpu_opt['insert_html']==1) { echo ' checked="checked"'; } ?> /><?php _e('在wp_footer()处。', 'mp-ukagaka'); ?></label> <?php _e('这是WP常用的方式，但如果你的主题尾部没有wp_footer()函数那春菜将无法显示', 'mp-ukagaka'); ?>
			</p>
			<?php } ?>
			
			<div style="height:1px;background:#DFDFDF;width:400px;"></div>
			
			<p>
				<label for="no_page"><?php _e('不在以下页面显示春菜', 'mp-ukagaka'); ?></label><br/>
				<textarea cols="40" rows="3" id="no_page" name="no_page" class="resizable" style="line-height:130%;"><?php echo $mpu_opt['no_page']; ?></textarea><br/>
				<?php _e('输入不显示春菜的页面的URL，每行一条。', 'mp-ukagaka'); ?><br/>
				<?php _e('在地址尾部加入(*)可进行模糊匹配。', 'mp-ukagaka'); ?>
			</p>
			

			
			<p><input name="submit1" class="button" value="<?php _e(' 保 存 ', 'mp-ukagaka'); ?>" type="submit" /></p>
    
		</form>
		
		<div style="height:1px;background:#DFDFDF;width:550px;"></div>
		
		<h3><?php _e('插件信息', 'mp-ukagaka'); ?></h3>
		
		<p><?php _e('版本：', 'mp-ukagaka'); ?> <?php echo MPU_VERSION; ?></p>
		
		<p><?php _e('春菜数：', 'mp-ukagaka'); ?> <?php echo count($mpu_opt['ukagakas']); ?></p>
		
		<p><?php _e('平均会话：', 'mp-ukagaka'); ?> <?php echo round(mpu_count_total_msg()/count($mpu_opt['ukagakas']),1); ?></p>
		
		<p><?php _e('插件首页：', 'mp-ukagaka'); ?> <a href="http://blog.lolily.com/wordpress-plugin-mp-ukagaka.html" title="Lo极乐园"><?php _e('访问插件首页', 'mp-ukagaka'); ?></a></p>
		
		<div style="height:1px;background:#DFDFDF;width:550px;"></div>
		
		<form method="post" name="setting" id="setting" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo plugin_basename(__FILE__); ?>&cur_page=0">
		
			<h3><?php _e('重置设定', 'mp-ukagaka'); ?></h3>
		
			<p><label><input id="reset_mpu" name="reset_mpu" type="checkbox" value="true" /><?php _e('确认重置', 'mp-ukagaka'); ?></label></p>
			
			<p><?php _e('重置设定将还原插件的所有配置为默认设定，插件内的所有设定及春菜将被删除。该操作无法撤销，请慎重操作。', 'mp-ukagaka'); ?></p>
			
			<p><input name="submit_reset" class="button" value="<?php _e(' 重 置 ', 'mp-ukagaka'); ?>" type="submit" /></p>
			
		</form>
		
    </div>