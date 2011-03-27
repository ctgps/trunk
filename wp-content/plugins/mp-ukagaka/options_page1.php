    <div>
    
    	<h3><?php _e('春菜们', 'mp-ukagaka'); ?></h3>
    	
    	<p>
    		<?php _e('图片栏中，请图片填写完整的URL，不要忘记http://开头。', 'mp-ukagaka'); ?>
    		<br/>
    		<?php _e('吐槽栏中，每行代表一条吐槽。可使用HTML代码。', 'mp-ukagaka'); ?>
    	</p>
    	
		<form method="post" name="ukagakas" id="ukagakas" action="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename('mp-ukagaka/options.php'); ?>&cur_page=1">
    	
    	<?php foreach ($mpu_opt['ukagakas'] as $key => $value) { ?>
    	
    		<div style="height:1px;background:#DFDFDF;width:400px;"></div>
    		
    		<div>
    		
    			<p>
    				#<?php echo $key; ?>
    				<?php if ($key==str_replace('default','',$key)) { ?>
    				 / <a href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename('mp-ukagaka/options.php'); ?>&cur_page=1&del=<?php echo $key; ?>">[<?php _e('删除', 'mp-ukagaka'); ?>]</a>
    				 <?php } ?>
    			</p>
    			
    			<p><label><input type="checkbox" name="ukagakas[<?php echo $key; ?>][show]" value="true"<?php if ($value['show']) { echo ' checked="checked"'; } ?> /><?php _e('可显示', 'mp-ukagaka'); ?></label></p>
    			
    			<p><label><?php _e('名字：', 'mp-ukagaka'); ?><br/><input type="text" name="ukagakas[<?php echo $key; ?>][name]" value="<?php echo mpu_output_filter($value['name']); ?>" size="45"/></label></p>
    			
    			<p><label><?php _e('图片：', 'mp-ukagaka'); ?><br/><input type="text" name="ukagakas[<?php echo $key; ?>][shell]" value="<?php echo mpu_output_filter($value['shell']); ?>" size="45"/></label></p>
    			
    			<p><label><?php _e('吐槽：', 'mp-ukagaka'); ?><br/><textarea name="ukagakas[<?php echo $key; ?>][msg]" rows="3" cols="40" class="resizable" style="line-height:130%;" ><?php echo mpu_array2str($value['msg']); ?></textarea></label></p>
    			
    		</div>
    	
    	<?php } ?>
    	
    	<p><input name="submit2" class="button" value="<?php _e(' 保 存 ', 'mp-ukagaka'); ?>" type="submit" /></p>
    	
		</form>
    
    </div>