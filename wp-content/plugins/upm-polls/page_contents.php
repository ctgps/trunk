<?php 
global $wpdb ;

switch( $_GET['page'] ) {
	
	####################################################################################################
	#######  PAGE  #####################################################################################
	####################################################################################################
	case 'upm_polls' : 
	{
	
		switch ( $cb ) {
		
			////////////////////////////////////////////////////////////////////////////////////////////
			//////// CBOX //////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////
			case 'poll_overview' : 
			{
				?>
				<table width="100%" class="pppm_box_table" border="0" cellspacing="1">
                  
                  <!-- General Polls -->
                   <tr>
                      <td colspan="2" class="pppm_box_td" style="font-weight:bold;">
                        <?php _e( 'General Polls' ) ?>
                      </td>
				   </tr>
                   <tr>
					<td colspan="2" style="background:#F7F7F7; padding:0px;">
                    <table width="100%"class="pppm_box_table" border="0" cellspacing="1">
				   		<tr>
                            <td class="pppm_box_td_cell">Question</td>
                            <td class="pppm_box_td_cell" style="width:10%">Total Votes</td>
                            <td class="pppm_box_td_cell" style="width:10%">Start Date</td>
                            <td class="pppm_box_td_cell" style="width:10%">End Date</td>
                            <td class="pppm_box_td_cell" style="width:10%">Status</td>
                            <td class="pppm_box_td_cell" style="width:24%">Management</td>
                            <td class="pppm_box_td_cell" style="width:1%">&nbsp;</td>
                        </tr>
                    </table>
                    <script language="javascript">
                    function UPM_Logs(mode){
						if( mode == 'open' ){ document.getElementById('upm_poll_logs').style.display = "block"; } else {  document.getElementById('upm_logs').src = '<?php echo PPPM_1_PATH ?>js/blank.html'; document.getElementById('upm_poll_logs').style.display = "none"; }
					}
					function UPM_sLogs(mode){
						if( mode == 'open' ){ document.getElementById('upm_poll_slogs').style.display = "block"; } else {  document.getElementById('upm_slogs').src = '<?php echo PPPM_1_PATH ?>js/blank.html'; document.getElementById('upm_poll_slogs').style.display = "none"; }
					}
                    </script>
                    <div style="overflow-y:scroll; height:132px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php 
						global $wpdb;
						$poll_res = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls` WHERE `post`=0 ORDER BY `id` ASC ");
						foreach ( $poll_res as $_res ) :
						$meta = unserialize(stripslashes($_res->meta));
						$pnum = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid` = ".intval($_res->id) ));
						if( (intval(time()) < intval($_res->end)) || $_res->end == 0 ) { $_end = ''; } else { $_end = '<span style="color:#FF0000; font-size:11px;cursor:pointer;" title="Poll Is Expired"><strong>(i)</strong></span>'; }
						?>
                          <tr>
                            <td class="pppm_box_td_subcell" style="text-align:left;">&nbsp;<?php echo stripslashes($_res->question) ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php echo $pnum ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php echo date('d/n/Y',$_res->start) ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php ($_res->end)?print(date('d/n/Y',$_res->end)):print('unexpired'); ?> <?php echo $_end; ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php ($meta['status'])?print('Open'):print('Closed') ?></td>
                            <td class="pppm_box_td_subcell" style="width:25%">
                            <?php if($pnum): ?>
                            <a href="<?php echo PPPM_1_PATH .'includes/poll_logs.php?_page=1&qid='.$_res->id ?>&mode=general" class="_polls" target="upm_logs" onclick="UPM_Logs('open')">Logs</a> | 
                            <?php endif; ?>
                            <a href="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&do=edit&id=<?php echo $_res->id ?>#add_edit" class="_polls">Edit</a> | 
                            <a href="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&do=<?php ($meta['status'])?print('close'):print('open') ?>&id=<?php echo $_res->id ?>" class="_polls"><?php ($meta['status'])?print('Close'):print('Open') ?></a> | 
                            <a href="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&do=delete&id=<?php echo $_res->id ?>" onClick="a=confirm('Are you sure you want to DELETE this poll ?'); if(!a) return(false);" class="_polls">Delete</a></td>
                          </tr>
						  <?php endforeach; ?>
                        </table>
                     </div>
					</td>
				  </tr>
                  <!-- General Polls -->
                  <tr>
                      <td style="background:#FFFFFF;">
                      <div id="upm_poll_logs" style="display:none; position:relative;">
                      <div style="position:absolute;color:#DD0000; width:100%; top:7px; font-weight:bold; text-align:right;"><span style="cursor:pointer;" onclick="UPM_Logs('close')" title="close">x</span> &nbsp;</div>
                  	  <iframe src="<?php echo PPPM_1_PATH ?>js/blank.html" name="upm_logs" id="upm_logs" frameborder="0" style="width:100%; height:300px;margin:0px; border:0px; overflow:hidden; outline:0px;" scrolling="no"></iframe>
                      </div>
                      </td>
                  </tr>
                  <!-- Post Specific Polls -->
                   <tr>
                      <td colspan="2" class="pppm_box_td" style="font-weight:bold;">
                        <?php _e( 'Post Specific Polls' ) ?>
                      </td>
				   </tr>
                   <tr>
					<td colspan="2" style="background:#F7F7F7; padding:0px;">
                   <table width="100%"class="pppm_box_table" border="0" cellspacing="1">
				   		<tr>
                            <td class="pppm_box_td_cell">Question</td>
                            <td class="pppm_box_td_cell" style="width:10%">Total Votes</td>
                            <td class="pppm_box_td_cell" style="width:10%">Start Date</td>
                            <td class="pppm_box_td_cell" style="width:10%">End Date</td>
                            <td class="pppm_box_td_cell" style="width:10%">Status</td>
                            <td class="pppm_box_td_cell" style="width:24%">Management</td>
                            <td class="pppm_box_td_cell" style="width:1%">&nbsp;</td>
                        </tr>
                    </table>
                    <div style="overflow-y:scroll; height:132px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <?php 
						  global $wpdb;
						  $poll_res = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls` WHERE `post`!=0 ORDER BY `id` ASC ");
						  foreach ( $poll_res as $_res ) :
						  $meta = unserialize(stripslashes($_res->meta));
						  $pnum = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."pppm_polls_votes` WHERE `qid` = ".intval($_res->id) ));
						  if( (intval(time()) < intval($_res->end)) || $_res->end == 0 ) { $_end = ''; } else { $_end = '<span style="color:#FF0000; font-size:11px;cursor:pointer;" title="Poll Is Expired"><strong>(i)</strong></span>'; }
						  ?>
                          <tr>
                            <td class="pppm_box_td_subcell" style="text-align:left;">&nbsp;<?php echo stripslashes($_res->question) ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php echo $pnum ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php echo date('d/n/Y',$_res->start) ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php ($_res->end)?print(date('d/n/Y',$_res->end)):print('unexpired'); ?> <?php echo $_end; ?></td>
                            <td class="pppm_box_td_subcell" style="width:10%"><?php ($meta['status'])?print('Open'):print('Closed') ?></td>
                            <td class="pppm_box_td_subcell" style="width:25%">
                            <?php if($pnum): ?>
                            <a href="<?php echo PPPM_1_PATH .'includes/poll_logs.php?_page=1&qid='.$_res->id ?>&mode=specific" class="_polls" target="upm_slogs" onclick="UPM_sLogs('open')">Logs</a> | 
                            <?php endif; ?>
                            <a href="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&do=edit&id=<?php echo $_res->id ?>#add_edit" class="_polls">Edit</a> | 
                            <a href="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&do=<?php ($meta['status'])?print('close'):print('open') ?>&id=<?php echo $_res->id ?>" class="_polls"><?php ($meta['status'])?print('Close'):print('Open') ?></a> | 
                            <a href="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&do=delete&id=<?php echo $_res->id ?>" onClick="a=confirm('Are you sure you want to DELETE this poll ?'); if(!a) return(false);" class="_polls">Delete</a>
                            </td>
                          </tr>
						  <?php endforeach; ?>
                        </table>
                     </div>
					</td>
				  </tr>
                  <!-- General Polls -->
                  <tr>
                      <td style="background:#FFFFFF;">
                      <div id="upm_poll_slogs" style="display:none; position:relative;">
                      <div style="position:absolute;color:#DD0000; width:100%; top:7px; font-weight:bold; text-align:right;"><span style="cursor:pointer;" onclick="UPM_sLogs('close')" title="close">x</span> &nbsp;</div>
                  	  <iframe src="<?php echo PPPM_1_PATH ?>js/blank.html" name="upm_slogs" id="upm_slogs" frameborder="0" style="width:100%; height:300px;margin:0px; border:0px; overflow:hidden; outline:0px;" scrolling="no"></iframe>
                      </div>
                      </td>
                  </tr>
				</table>
				
				<?php
			} break ;
			////////////////////////////////////////////////////////////////////////////////////////////
			//////// CBOX //////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////
			case 'poll_add' : 
			{
				if( $_GET['do'] == 'edit' ):
				global $wpdb;
				$_poll = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."pppm_polls` WHERE `id`=".$_GET['id'], ARRAY_A );
				endif;
				?>
                <a name="add_edit"></a>
                <form method="post" action="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>">
				<input type="hidden" name="pppm_hidden" value="pppm_poll_<?php ($_GET['do']=='edit')?print('edit'):print('add'); ?>">
                <input type="hidden" name="poll_id" value="<?php echo $_GET['id'] ?>" />
				<table width="100%" class="pppm_box_table" border="0" cellspacing="1">
                   <tr>
                      <td class="pppm_box_td" style="font-weight:bold;">
                      Question
                      </td>
                      <td class="pppm_box_td">
                      <input name="pppm_poll_question" value="<?php echo $_poll['question'] ?>" type="text" size="80" />
                      </td>
				   </tr>
                   <tr>
                      <td class="pppm_box_td" style="font-weight:bold;">
                      Poll Answers
                      </td>
                      <td class="pppm_box_td">
                      <!-- Poll Answers -->
                      <fieldset>
                         <?php 
						 if( $_GET['do'] == 'edit' ):
					     $poll_res = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls_items` WHERE `qid`=".$_GET['id']." ORDER BY `id` ASC");
					  	 foreach( $poll_res as $item ):
					     ?>
                      	 <fieldset id="duplicate" style="margin:0px;width:100%; background:#F7F7F7;">
                                    <p>
                                    <label for="pb3g">Answer : </label>
                                    <br />
                                    <input id="pb3g" type="text" name="pppm_poll_answers[<?php echo $item->id ?>]" value="<?php echo $item->answer ?>" size="78"> | <label for="pppm_poll_answers_remove_<?php echo $item->id ?>">Remove:</label> <input type="checkbox" id="pppm_poll_answers_remove_<?php echo $item->id ?>" name="pppm_poll_answers_remove[<?php echo $item->id ?>]" value="1" />
                                    </p>
                         </fieldset>
                      	 <?php endforeach; endif; ?>
                         <fieldset id="duplicate3g" style="margin:0px;width:100%; background:#F7F7F7;">
                                    <p>
                                    <label for="pb3g">New Answer : </label>
                                    <br />
                                    <input id="pb3g" type="text" name="pppm_poll_answers[]" value="" size="78">
                                    </p>
                          </fieldset>
                          <p><span><a id="minus3g" href="" title="Add new field" style="text-decoration:none; font-size:14px;">[-]</a> 
                          <a id="plus3g" href="" title="Add new field" style="text-decoration:none; font-size:14px;">[+]</a></span><br /><br /></p>
                      </fieldset>
                      <!-- Poll Answers END -->
                      </td>
				   </tr>
                   <tr>
                      <td class="pppm_box_td" style="font-weight:bold;">
                      Poll Start &amp; End Date
                      </td>
                      <td class="pppm_box_td">
                      <!-- Poll Date -->
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>&nbsp;Start: </td>
                            <td><input type="text" name="pppm_poll_date_start" id="date1" class="date-pick" value="<?php if($_GET['do'] == 'edit' && $_poll['start']) echo date('d/n/Y',$_poll['start']) ?>" /> </td>
                            <td>&nbsp;&nbsp;&nbsp;End: </td>
                            <td><input type="text" name="pppm_poll_date_end" id="date2" class="date-pick" value="<?php if($_GET['do'] == 'edit' && $_poll['end']) echo date('d/n/Y',$_poll['end']) ?>" /></td>
                          </tr>
                        </table>
                        <!-- Poll Date END -->
                      </td>
				   </tr>
                    <tr>
                      <td class="pppm_box_td" style="font-weight:bold; width:30%">
                      Poll Type<br />
                      <span style="color:#777777; font-style:italic; font-weight:100; font-size:12px;">
					  <?php _e( 'You can choose "Post Specific" option to show this poll only on certain post widget.' ) ?>
                      </span>
                      </td>
                      <td class="pppm_box_td">
                        <!-- Poll Date -->
                        <label for="pppm_poll_type_0">General:</label> 
                        <input id="pppm_poll_type_0" type="radio" name="pppm_poll_type" value="0" <?php ( $_poll['post'] && $_GET['do'] == 'edit' )?print(''):print('checked="checked"'); ?> />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="pppm_poll_type_1">Post Specific:</label> 
                        <input id="pppm_poll_type_1" type="radio" name="pppm_poll_type" value="1" <?php ( $_poll['post'] && $_GET['do'] == 'edit' )?print('checked="checked"'):print(''); ?> /> 
                        <select name="pppm_poll_post" id="pppm_poll_post" >
                        <option value="">--Choose the post--</option>
                        <?php
                        $args=array('orderby' => 'name', 'order' => 'ASC' ,'hide_empty' => false);
						  
						$categories = get_categories($args);
						foreach( $categories as $category ) { 
						
							( $category->cat_ID == get_option('ach_r_article_cat') ) ? $_selected = 'selected="selected"' :  $_selected = '' ;
						
							$CID = $category->cat_ID;
							if( $category->parent == 0 ) { 
								$ws = '' ;
								echo '<optgroup label="'.$category->name.'" style="font-size:14px;">'; 
								///////////////////////////////////////////////////////////////////////////
								$recentPosts = new WP_Query();
								$recentPosts->query('showposts=-1&cat='.$CID.'orderby=post_title&order=ASC' );
								while ($recentPosts->have_posts()) : $recentPosts->the_post(); 
								( $_poll['post'] == get_the_ID() ) ? $_selected = 'selected="selected"' : $_selected = '';
								?>
                                <option value="<?php the_ID(); ?>" <?php echo $_selected; ?>><?php the_title(); ?></option>; 
								<?php 
								endwhile; 
                                ///////////////////////////////////////////////////////////////
								echo '</optgroup>';
							}
							
						}
						?>
                        </select>
                        <!-- Poll Date END -->
                      </td>
				   </tr>
                   <tr>
					<td class="pppm_box_th" colspan="2"> 
                        <p class="submit">
                        <input type="submit" name="Submit" value="<?php _e( 'Update Options' ) ?>" />
                        </p>
					</td>
				  </tr>
                </table>
                </form>
                <?php
			} break ;
			////////////////////////////////////////////////////////////////////////////////////////////
			//////// CBOX //////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////
			case 'poll_template' : 
			{
				?>
                <a name="poll_template"></a>
                <form method="post" action="<?php echo preg_replace('|\&do=\w+\&id=\d+|i','',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>#poll_template">
				<input type="hidden" name="pppm_hidden" value="pppm_poll_template">
				<table width="100%" class="pppm_box_table" border="0" cellspacing="1">
                  <!-- Poll's Template -->
                   <tr>
                      <td colspan="2" class="pppm_box_td" style="font-weight:bold; background:#F4F4F4;">
                        <?php _e( 'Voting Form' ) ?>
                      </td>
				   </tr>
                   <tr>
                      <td class="pppm_box_td" style="width:40%">
                        <strong><?php _e( 'Movable Variables:' ) ?></strong>
                        <br />[QUESTION] - Poll question<br /><br />
                        <strong><?php _e( 'Immovable Variables:' ) ?></strong>
                        <br />[ANSWERS-START] - Start creating answers list,
                        <br />[ANSWERS-END] - End of answers list,
                        <br />[ANSWER-ID] - Answer's ID,
                        <br />[ANSWER] - Answer's text.
                        
                      </td>
                      <td class="pppm_box_td" style="text-align:center;">
                        <textarea name="pppm_poll_form_template" style="width:98%; background:#F8F8F8; color:#009900; font-size:12px;" rows="10"><?php echo stripslashes(get_option('pppm_poll_form_template')) ?></textarea>
                      </td>
				   </tr>
                   <tr>
					<td class="pppm_box_th" colspan="2">&nbsp; 
                        
					</td>
				  </tr>
                  <!-- Poll's Template END -->
                  
                  <!-- Poll's Result Template -->
                   <tr>
                      <td colspan="2" class="pppm_box_td" style="font-weight:bold; background:#F4F4F4;">
                        <?php _e( 'Voting Results' ) ?>
                      </td>
				   </tr>
                   <tr>
                      <td class="pppm_box_td" style="width:40%">
                        <strong><?php _e( 'Movable Variables:' ) ?></strong>
                        <br />[QUESTION] - Poll question
                        <br />[POLL-ID] - Poll ID
                        <br />[NEXT-POLL] - Next poll button
                        <br />[TOTAL-VOTERS] - Number of total voters
                        <br /><br />
                        <strong><?php _e( 'Immovable Variables:' ) ?></strong>
                        <br />[ANSWERS-START] - Start creating answers list,
                        <br />[ANSWERS-END] - End of answers list,
                        <br />[ANSWER-ID] - Answer ID,
                        <br />[ANSWER] - Answer's text.
                        <br />[POLLBAR-BG] - Pollbar background style
                        <br />[POLLBAR-WIDTH] - Pollbar width
                        <br />[POLLBAR-HEIGHT] - Pollbar height
                        <br />[V%] - Percent of votes
                        <br />[V#] - Number of votes
                      </td>
                      <td class="pppm_box_td" style="text-align:center;">
                        <textarea name="pppm_poll_results_template" style="width:98%; background:#F8F8F8; color:#009900; font-size:12px;" rows="15"><?php echo stripslashes(get_option('pppm_poll_results_template')) ?></textarea>
                      </td>
				   </tr>
                   <tr>
					<td class="pppm_box_th" colspan="2"> 
                        <p class="submit">
                        <input type="submit" name="Submit" value="<?php _e( 'Update Options' ) ?>" />&nbsp;&nbsp;&nbsp;<input type="submit" name="Default"  onClick="a=confirm('Are you sure you want to RESTORE DEFAULT both ( Poll and Voting ) templates ?'); if(!a) return(false);" value="<?php _e( 'Restore Default' ) ?>" />
                        </p>
					</td>
				  </tr>
                  <!-- Poll's Result  Template END -->
				</table>
				</form>
				<?php
			} break ;
			////////////////////////////////////////////////////////////////////////////////////////////
			//////// CBOX //////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////
			
		}
		
		
		
	}
	####################################################################################################
	#######  PAGE  #####################################################################################
	####################################################################################################
	case 'bycat' : 
	{
	
		switch ( $cb ) {
		
			////////////////////////////////////////////////////////////////////////////////////////////
			//////// CBOX //////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////
			case 'cat_list' : 
			{
				?>
				<form id="pppm_form_bycat" name="pppm_form_bycat" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<input type="hidden" name="pppm_hidden" value="pppm_bycat">
					<table width="100%" class="pppm_box_table" border="0" cellspacing="1">
					<tr>
						<td rowspan="2" class="pppm_box_td" style="background:#F9F9F9; font-weight:bold;" >
						<?php _e( 'Category Name' ) ?>
						</td>
						<td class="pppm_box_th" style="background:#F9F9F9; font-weight:bold; text-align:center">
						<?php _e( 'Managers' ) ?>
						</td>
				  	</tr>
					<tr>
						<td class="pppm_box_th" style="background:#F9F9F9; font-weight:100; text-align:left; padding:0px;">
						
						<table width="100%" border="0" cellspacing="1" class="pppm_box_table">
						  <tr bgcolor="#F9F9F9">
							<td class="pppm_box_th" style="text-align:center; font-weight:bold" width="25%">
							<?php _e( 'Saving' ) ?></td>
							<td class="pppm_box_th">&nbsp;</td>
							<td class="pppm_box_th">&nbsp;</td>
							<td class="pppm_box_th">&nbsp;</td>
						  </tr>
						</table>

						</td>
				  	</tr>
					<?php
					$args=array(
					  'orderby' => 'name', 'order' => 'ASC' ,'hide_empty' => false);
					  
					$categories = get_categories($args);
					foreach( $categories as $category ) { 
						$CID = $category->cat_ID;
						?>
						<tr>
						<td class="pppm_box_td" style="width:40%">
						<?php 
						if( $category->parent == 0 ) { 
							$ws = '' ;
							$bold = 'font-weight:bold';
						}
						else {
							$bold = '';
							$cats_str = get_category_parents($category->cat_ID, false, '%#%');
							$cats_array = explode('%#%', $cats_str);
							$cat_depth = sizeof($cats_array)-2;
							$cat_depth; $nbsp = '';
							for( $i = 0; $i < $cat_depth; $i++ ){
								$nbsp .= '-';
							}
							$ws = ' '.$nbsp.' ';
						}
						echo $ws.$category->name; 
						?>
						</td>
						<td class="pppm_box_th" style="padding:0px;">
						
							<table width="100%" border="0" cellspacing="1">
							  <tr bgcolor="#F9F9F9">
								<td class="pppm_box_th" style="text-align:center;<?php echo $bold ?>" width="25%">
								
								<?php 
								( get_option( 'pppm_bycat_saving_'.$CID ) ) ? 
								$pppm_bycat_saving_checked[$CID]['off'][ 'pppm_bycat_saving' ] = 'checked="checked"' : 
								$pppm_bycat_saving_checked[$CID]['on'][ 'pppm_bycat_saving' ] = 'checked="checked"';
								?>
								<label for="pppm_bycat_saving_0_<?php echo $CID ?>"><?php _e( 'On' ) ?> </label>
								<input type="radio" id="pppm_bycat_saving_0_<?php echo $CID ?>" 
								<?php echo $pppm_bycat_saving_checked[$CID]['on'][ 'pppm_bycat_saving' ] ?> 
								name="pppm_bycat_saving[<?php echo $CID ?>]" value="0" /> &nbsp;&nbsp;
								
								<label for="pppm_bycat_saving_1_<?php echo $CID ?>"><?php _e( 'Off' ) ?> </label>
								<input type="radio" id="pppm_bycat_saving_1_<?php echo $CID ?>" 
								<?php echo $pppm_bycat_saving_checked[$CID]['off'][ 'pppm_bycat_saving' ] ?> 
								name="pppm_bycat_saving[<?php echo $CID ?>]" value="1" />
								
								</td>
								<td class="pppm_box_th">&nbsp;</td>
								<td class="pppm_box_th">&nbsp;</td>
								<td class="pppm_box_th">&nbsp;</td>
							  </tr>
							</table>
						
						</td>
						</tr>
						<?php
						
					} 
					?>
				  <tr>
					<td class="pppm_box_th" colspan="2">
					<p class="submit">
					<input type="submit" name="Submit" value="<?php _e( 'Update Options' ) ?>" />
					</p>
					</td>
				  </tr>
				</table>
				</form>
				<?php
			}break ;
			////////////////////////////////////////////////////////////////////////////////////////////
			//////// CBOX //////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////
		}
		
	}
	
	####################################################################################################
	#######  PAGE  #####################################################################################
	####################################################################################################
	
}

?>
			