<?php 
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }


if (isset($_POST['uninstall'])) {	
			
	pppm_1_uninstall();
	$message = '<div class="updated"><p><strong>'. __( 'Uninstall sucessful ! Now You can deactivate or delete the plugin .' ).'</strong></p></div>';
}
else {
$message = '';
}


echo $message;
?>
	
	
<table width="90%" border="0" cellspacing="1" cellpadding="2">
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" name="pppm_1_uninstall" >
  <tr>
    <td>
	<p><?php _e('You don\'t like  UPM Polls ?') ;?></p>
	<p><?php _e('No problem, before you deactivate this plugin press the Uninstall Button, because deactivating UPM Polls does not remove any data that may have been created. ') ;?></p>
	</td>
  </tr>
  <tr>
    <td style="color:#FF0000"><strong><?php _e('WARNING:') ;?></strong><br />
	<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to backup all the tables first.') ;?>
	<input type="submit" name="uninstall" value="<?php _e('Uninstall plugin') ?>" onclick="javascript:check=confirm('<?php _e('You are about to Uninstall this plugin from WordPress.\nThis action is not reversible.\n\nChoose [Cancel] to Stop, [OK] to Uninstall.\n'); ?>');if(check==false) return false;"/></td>
  </tr>
  </form>
</table>
</div>

	