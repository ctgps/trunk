    <div>
    
		<h3><?php _e('会话', 'mp-ukagaka'); ?></h3>
		
		<form method="post" name="setting" id="setting" action="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename('mp-ukagaka/options.php'); ?>&cur_page=4">
		
			<div style="height:1px;background:#DFDFDF;width:400px;"></div>
			
			<h4><label for="auto_msg"><?php _e('固定信息：', 'mp-ukagaka'); ?></label></h4>
			<p>
				<textarea cols="40" rows="3" id="auto_msg" name="auto_msg" class="resizable" style="line-height:130%;"><?php echo $mpu_opt['auto_msg']; ?></textarea><br/>
				<?php _e('此信息将显示在每条会话的后面，支持HTML代码。', 'mp-ukagaka'); ?>
			</p>
			
			<div style="height:1px;background:#DFDFDF;width:400px;"></div>
			
			<h4><label for="common_msg"><?php _e('通用会话：', 'mp-ukagaka'); ?></label></h4>
			<p>
				<textarea cols="40" rows="3" id="common_msg" name="common_msg" class="resizable" style="line-height:130%;"><?php echo $mpu_opt['common_msg']; ?></textarea><br/>
				<?php _e('所有春菜共用的会话内容。', 'mp-ukagaka'); ?><br/>
				<?php _e('一旦填写此栏，通用会话将取代每个春菜的自定义会话。清空此栏则使用各春菜的默认自定义会话。', 'mp-ukagaka'); ?>
			</p>
			
			<p><input name="submit5" class="button" value="<?php _e(' 保 存 ', 'mp-ukagaka'); ?>" type="submit" /></p>
		
		</form>
		
	</div>