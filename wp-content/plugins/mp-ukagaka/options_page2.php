    <div>
    
    	<h3><?php _e('创建新春菜', 'mp-ukagaka'); ?></h3>
    	
		<form method="post" name="create" id="create" action="<?php echo site_url(); ?>/wp-admin/options-general.php?page=<?php echo plugin_basename('mp-ukagaka/options.php'); ?>&cur_page=2">
		
			<p><label><input type="checkbox" name="ukagaka[show]" value="true" /><?php _e('可显示', 'mp-ukagaka'); ?></label></p>
		
    		<p><label><?php _e('名字：', 'mp-ukagaka'); ?><br/><input type="text" name="ukagaka[name]" value="" size="45"/></label></p>
    			
    		<p><label><?php _e('图片：', 'mp-ukagaka'); ?><br/><input type="text" name="ukagaka[shell]" value="http://" size="45"/></label></p>
    			
    		<p><label><?php _e('吐槽：', 'mp-ukagaka'); ?><br/><textarea name="ukagaka[msg]" rows="5" cols="40" class="resizable" style="line-height:130%;" ></textarea></label><br/><?php _e('每行一条吐槽，可使用HTML代码。', 'mp-ukagaka'); ?></p>
		
			<p><input name="submit3" class="button" value="<?php _e(' 创 建 ', 'mp-ukagaka'); ?>" type="submit" /></p>
    
		</form>
    
    </div>