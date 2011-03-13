<?php

	global $wpdb;
	
	$pppm_options = array(	'pppm_onoff_poll_manager',//version 1.0.1
							 'pppm_poll_bgtype',//version 1.0.1
							 'pppm_poll_onoff_next',//version 1.0.1
							 
							 'pppm_global_jquery_load'//version 1.0.2
							 );
	
	
	if( $_POST[ 'pppm_hidden' ] == 'x' ) {
	
		foreach( $pppm_options as $pppm ) {
		
			( $_POST[ $pppm ] == '' ) ? $pppm_op = 0 : $pppm_op = $_POST[ $pppm ];
			update_option( $pppm, $pppm_op );
		}
				
		?>
		<div class="updated"><p><strong><?php _e( 'Options saved.' ); ?></strong></p></div>
		<?php
		
		
		############################################################################POLL
		update_option('pppm_onoff_poll_manager', $_POST['pppm_onoff_poll_manager']);
		update_option('pppm_poll_bg_url', $_POST['pppm_poll_bg_url']);
		update_option('pppm_poll_bg_color', $_POST['pppm_poll_bg_color']);
		update_option('pppm_poll_bgtype', $_POST['pppm_poll_bgtype']);
		update_option('pppm_poll_height', $_POST['pppm_poll_height']);
		update_option('pppm_poll_voters', $_POST['pppm_poll_voters']);
		update_option('pppm_poll_logging', $_POST['pppm_poll_logging']);
		update_option('pppm_poll_logging_exdatenum', $_POST['pppm_poll_logging_exdatenum']);
		update_option('pppm_poll_logging_exdatetype', $_POST['pppm_poll_logging_exdatetype']);
		update_option('pppm_poll_first_poll', $_POST['pppm_poll_first_poll']);
		update_option('pppm_poll_onoff_next', $_POST['pppm_poll_onoff_next']);
		#################################################################################
	}



	foreach( $pppm_options as $pppm ) {
		
		if( get_option($pppm) ) {
			
			$pppm_checked['checkbox'][ $pppm ][ 'checked' ] = 'checked="checked"';
			$pppm_checked['radio'][ $pppm ][ 'on_check' ] = 'checked="checked"';
			$pppm_checked['radio'][ $pppm ][ 'off_check' ] = '';
		} 
		else {
		
			$pppm_checked['checkbox'][ $pppm ][ 'checked' ] = '';
			$pppm_checked['radio'][ $pppm ][ 'on_check' ] = '';
			$pppm_checked['radio'][ $pppm ][ 'off_check' ] = 'checked="checked"';
		}
	}

?>
<br />
<style type="text/css">
.pppm_option_table {
background-color:#CCCCCC;
}
.pppm_option_th {
background-color:#F9F9F9;
text-align:left;
font-weight:100;
padding:2px;
width:60%;
}
.pppm_option_td {
background-color:#F9F9F9;
text-align:left;
font-weight:100;
padding:2px;
width:40%;
}
.pppm_option_top_th {
background-color:#F0F0F0;
text-align:left;
font-weight:bold;
padding:2px;
width:60%;
}
.pppm_option_top_td {
background-color:#F0F0F0;
text-align:left;
font-weight:bold;
padding:2px;
width:40%;
}
ul li{
padding-left:0px;
font-size:13px;
}
.upm_yes{
	list-style-image:url(<?php echo PPPM_1_PATH ?>img/1.gif);
}
.upm_no{
	list-style-image:url(<?php echo PPPM_1_PATH ?>img/0.gif);
}
</style>

	<form name="form_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="pppm_hidden" value="x">
        <table width="100%" border="0">
          <tr>
            <td style="padding:10px; padding-left:0px;">
            	<table width="100%" border="0" cellspacing="1" class="widefat">
        <thead>
        <tr>
        <th>&nbsp;Information</th>
        </tr>
        </thead>
			<tr valign="top">
				<td style="background:#FFF; padding:5px; text-align:left; font-size:13px;">
				"UPM Polls" is a part of the "Universal Polst Manager" plugin and only polling feature is available here. If you want to use complete version of this plugin you should deactivate ( not uninstall ) "UPM Polls" then install latest version of <a href="http://wordpress.org/extend/plugins/universal-post-manager/" target="_blank">Universal Post Manager</a>.
                <br />Please DO NOT use both plugins together , one of them should be deactivated !
                <br /><br /><strong>All features of Universal Post Manager:</strong>
                <div style="margin-left:40px; margin-top:10px;">
                <ul>
                <li class="upm_no">HTML tag Manager ,</li>
    			 <li class="upm_no">Protocol Manager,</li>
                 <li class="upm_no">Phrase filtering and shortcut Manager</li>
                 <li class="upm_no">Long Phrase Manager</li>
                 <li class="upm_no">Post and page Saving Manager ( Save as Text, HTML, MS Word, PDF, XML )</li>
                 <li class="upm_no">Share Manager ( Social Bookmarks, Email, Subscribe )</li>
                 <li class="upm_yes">Poll Manager</li>
                 <li class="upm_no">Print Manager</li>
                </ul>
                </div>
                </td>
			</tr>
        </table>
            </td>
            <td valign="top" style="padding:10px; padding-right:0px;">
            	<table width="100%" border="0" cellspacing="1" class="widefat">
                    <thead>
                    <tr>
                    <th>&nbsp;Like UPM Polls plugin?</th>
                    </tr>
                    </thead>
                        <tr valign="top">
                            <td style="background:#FFF; padding:5px; text-align:left; font-size:13px;">
                            <ul>
                            <li>If you like UPM Polls and want to encourage us to develop and maintain it,why not do any or all of the following:</li>
                            <li><a href="http://profprojects.com/become-grateful-user/"><span style="color: rgb(255, 126, 40);"><strong>Donate</strong></span></a> via acquiring <a href="http://profprojects.com/become-grateful-user/">"Grateful User"</a> coupon with unique identification numbers, this will also help us to find out our  thankful users among thousands  consumers and do our best to provide support for any issues with UPM Polls.</li>
                            <li>- Link to it so other folks can find out about it.</li>
                            <li>- Give it a good rating on <a href="http://wordpress.org/extend/plugins/upm-polls/" target="_blank">WordPress.org.</a></li>
                            </ul>
                            </td>
                        </tr>
                    </table>
        	<br />
                <table width="100%" border="0" cellspacing="1" class="widefat">
                <thead>
                <tr>
                <th>&nbsp;Need Help?</th>
                </tr>
                </thead>
                    <tr valign="top">
                        <td style="background:#FFF; padding:5px; text-align:left; font-size:13px;">
                        If you need help with this plugin , add custom features, or if you want to make a suggestion, then please visit to our forum at <a href="http://www.profprojects.com/forum/" target="_blank">ProfProjects.com</a>
                        </td>
                    </tr>
            	</table>
            </td>
          </tr>
        </table>

        <br />
		<table width="100%" border="0" cellspacing="1" class="widefat">
        <thead>
			<tr valign="top">
				<th colspan="2">
				jQuery Settings for Universal Post Manager
				</th>
			</tr>
         </thead>
			<tr valign="top">
				<th class="pppm_option_th">
				<?php _e( 'Turn on/off UPM jQuery framework loading' ) ?>
                <br />
                ( If you have aready loaded jQuery framework in you theme you should turn this option off)
				</th>
				<td class="pppm_option_td">
				<input type="radio" id="pppm_global_jquery_load_1" name="pppm_global_jquery_load" value="1" 
				<?php echo $pppm_checked['radio'][ 'pppm_global_jquery_load' ][ 'on_check' ] ?>/> 
				<label for="pppm_global_jquery_load_1"><?php _e( 'On' ) ?></label> &nbsp;&nbsp; 
				<input type="radio" id="pppm_global_jquery_load_0" name="pppm_global_jquery_load" value="0" 
				<?php echo $pppm_checked['radio'][ 'pppm_global_jquery_load' ][ 'off_check' ] ?> />
				<label for="pppm_global_jquery_load_0"><?php _e( 'Off' ) ?></label>
				
				</td>
			</tr>
			</table>
        
        <br />
        
        <table width="100%" border="0" cellspacing="1" class="widefat">
        <thead>
			<tr valign="top">
				<th>
				<strong><?php _e( 'Turn On/Off Poll Manager' ) ?></strong>
				</th>
				<th>
				<input type="radio" id="pppm_onoff_poll_manager_1" name="pppm_onoff_poll_manager" value="1" 
				<?php echo $pppm_checked['radio'][ 'pppm_onoff_poll_manager' ]['on_check'] ?>/> 
				<label for="pppm_onoff_poll_manager_1"><?php _e( 'On' ) ?></label> &nbsp;&nbsp; 
				<input type="radio" id="pppm_onoff_poll_manager_0" name="pppm_onoff_poll_manager" value="0" 
				<?php echo $pppm_checked['radio'][ 'pppm_onoff_poll_manager' ]['off_check'] ?> />
				<label for="pppm_onoff_poll_manager_0"><?php _e( 'Off' ) ?></label>
				</th>
			</tr>
        </thead>
            <tr valign="top">
				<th class="pppm_option_th">
				<?php _e( 'Poll Bar Style' ) ?>
				</th>
				<td class="pppm_option_td">
				<!-- PBS -->
                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td>Background Image URL</td>
                    <td>
                    <div style="float:left;"><input type="text" name="pppm_poll_bg_url" value="<?php echo get_option('pppm_poll_bg_url') ?>" /></div>
                    <div style="background:url(<?php echo get_option('pppm_poll_bg_url') ?>); background-repeat:repeat-x; height:15px; width:30px; float:left; margin-top:5px; margin-left:10px;"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>Background Color</td>
                    <td><input type="text" name="pppm_poll_bg_color" value="<?php echo get_option('pppm_poll_bg_color') ?>" class="hexa"/> 
                    <a href="javascript:TCP.popup(document.forms['form_options'].elements['pppm_poll_bg_color'])">
                    <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="<?php echo PPPM_1_PATH ?>img/sel.gif">
                    </a>
                    </td>
                  </tr>
                  <tr>
                    <td>Background Type</td>
                    <td>
                    <input type="radio" id="pppm_poll_bgtype_1" name="pppm_poll_bgtype" value="1" 
					<?php echo $pppm_checked['radio'][ 'pppm_poll_bgtype' ]['on_check'] ?>/> 
                    <label for="pppm_poll_bgtype_1"><?php _e( 'image' ) ?></label> &nbsp;&nbsp; 
                    <input type="radio" id="pppm_poll_bgtype_0" name="pppm_poll_bgtype" value="0" 
                    <?php echo $pppm_checked['radio'][ 'pppm_poll_bgtype' ]['off_check'] ?> />
                    <label for="pppm_poll_bgtype_0"><?php _e( 'color' ) ?></label>
                    </td>
                  </tr>
                  <tr>
                    <td>Poll Bar Height</td>
                    <td><input type="text" name="pppm_poll_height" value="<?php echo get_option('pppm_poll_height'); ?>" /></td>
                  </tr>
                </table>
                <!-- PBS END -->
				</td>
			</tr>
			<tr valign="top">
				<th class="pppm_option_td">
				<?php _e( 'Allow To Vote' ) ?>
				</th>
				<td class="pppm_option_td">
				<select name="pppm_poll_voters">
                	<?php $_voters[get_option('pppm_poll_voters')] = 'selected="selected"' ?>
					<option value="0" <?php echo $_voters[0] ?>>Registered Users And Guests</option>
                    <option value="1" <?php echo $_voters[1] ?>>Registered Users Only</option>
                    <option value="2" <?php echo $_voters[2] ?>>Guests Only</option>
				</select>
				</td>
			</tr>
            <tr valign="top">
				<th class="pppm_option_td">
				<?php _e( 'Logging Method' ) ?>
				</th>
				<td class="pppm_option_td">
				<select name="pppm_poll_logging">
                	<?php $_logging[get_option('pppm_poll_logging')] = 'selected="selected"' ?>
					<option value="0" <?php echo $_logging[0] ?>>Logging is turned off</option>
					<option value="1" <?php echo $_logging[1] ?>>Logging by Cookie</option>
					<option value="2" <?php echo $_logging[2] ?>>Logging by IP</option>
                    <option value="3" <?php echo $_logging[3] ?>>Logging by Username</option>
					<option value="4" <?php echo $_logging[4] ?>>Logging by Cookie and IP</option>
				</select>
				</td>
			</tr>
             <tr valign="top">
				<th class="pppm_option_td">
				<?php _e( 'Expiry Time For Logging' ) ?>
				</th>
				<td class="pppm_option_td">
                <input type="text" name="pppm_poll_logging_exdatenum" value="<?php echo get_option('pppm_poll_logging_exdatenum') ?>" size="5" />
                <?php $_type[get_option('pppm_poll_logging_exdatetype')] = 'selected="selected"' ?>
				<select name="pppm_poll_logging_exdatetype">
					<option value="sec" <?php echo $_type['sec'] ?>>second(s)</option>
					<option value="min" <?php echo $_type['min'] ?>>minute(s)</option>
					<option value="hr" <?php echo $_type['hr'] ?>>hour(s)</option>
                    <option value="day" <?php echo $_type['day'] ?>>day(s)</option>
					<option value="mon" <?php echo $_type['mon'] ?>>month(s)</option>
                    <option value="yr" <?php echo $_type['yr'] ?>>year(s)</option>
				</select>
				</td>
			</tr>
             <tr valign="top">
				<th class="pppm_option_td">
				<?php _e( 'First displayed Poll' ) ?>
				</th>
				<td class="pppm_option_td">
				<select name="pppm_poll_first_poll">
                 	<?php $_first[get_option('pppm_poll_first_poll')] = 'selected="selected"' ?>
					<option value="random" <?php echo $_first['random'] ?>>Display Random Poll &nbsp;&nbsp;</option>
					<option value="latest" <?php echo $_first['latest'] ?>>Display Latest Poll</option>
                    <option value="earliest" <?php echo $_first['earliest'] ?>>Display Earliest Poll</option>
                   
                    <optgroup label="-------Certain Poll------">
                    <?php 
					global $wpdb;
					$poll_res = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."pppm_polls` WHERE `post`=0 ORDER BY `id` ASC ");
					foreach ( $poll_res as $_res ) :
						$meta = unserialize($_res->meta); if( $meta['status'] == 0 ) continue;
						echo '<option value="'.$_res->id.'" '.$_first[$_res->id].'>'.$_res->question.'</option>';
					endforeach;
					?>
                    </optgroup>
                    
				</select>
				</td>
			</tr>
            <tr valign="top">
				<th class="pppm_option_th">
				<?php _e( 'Show "Next Poll" button with poll result screen' ) ?>
				</th>
				<td class="pppm_option_td">
				<input type="radio" id="pppm_poll_onoff_next_1" name="pppm_poll_onoff_next" value="1" 
				<?php echo $pppm_checked['radio'][ 'pppm_poll_onoff_next' ][ 'on_check' ] ?>/> 
				<label for="pppm_poll_onoff_next_1"><?php _e( 'Yes' ) ?></label> &nbsp;&nbsp; 
				<input type="radio" id="pppm_poll_onoff_next_0" name="pppm_poll_onoff_next" value="0" 
				<?php echo $pppm_checked['radio'][ 'pppm_poll_onoff_next' ][ 'off_check' ] ?> />
				<label for="pppm_poll_onoff_next_0"><?php _e( 'No' ) ?></label>
				
				</td>
			</tr>
            <tr valign="top">
				<th class="pppm_option_th">
				<?php _e( 'Custom Location for UPM Polls.<br/> Put this code in template files wherever you want.' ) ?><br /> 
                <span style="color:#777777; font-style:italic">(<?php _e( 'Use this template tag only when UPM Poll widget is deactivated on your site sidebar' ) ?>)</span>
				</th>
				<td class="pppm_option_td">
				<p style="padding:10px 0px; margin:0px;"><code style="font-size:15px; font-weight:bold;">&nbsp; &lt;?php upm_polls() ?&gt; </code></p>
				</td>
			</tr>
		</table>
        
        
		<br />
			<p class="submit" align="right">
			<input type="submit" name="Submit" value="<?php _e( 'Update Options' ) ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			</p>
		</form>
		</div>
		
		
<script language="javascript">
var TCP = new TColorPicker();

function TCPopup(field, palette) {
	this.field = field;
	this.initPalette = !palette || palette > 3 ? 0 : palette;
	var w = 194, h = 240,
	move = screen ? 
		',left=' + ((screen.width - w) >> 1) + ',top=' + ((screen.height - h) >> 1) : '', 
	o_colWindow = window.open('<?php echo PPPM_1_PATH ?>js/picker.html', null, "help=no,status=no,scrollbars=no,resizable=no" + move + ",width=" + w + ",height=" + h + ",dependent=yes", true);
	o_colWindow.opener = window;
	o_colWindow.focus();
}

function TCBuildCell (R, G, B, w, h) {
	return '<td bgcolor="#' + this.dec2hex((R << 16) + (G << 8) + B) + '"><a href="javascript:P.S(\'' + this.dec2hex((R << 16) + (G << 8) + B) + '\')" onmouseover="P.P(\'' + this.dec2hex((R << 16) + (G << 8) + B) + '\')"><img src="pixel.gif" width="' + w + '" height="' + h + '" border="0"></a></td>';
}

function TCSelect(c) {
	this.field.value = c.toUpperCase();
	this.win.close();
}

function TCPaint(c, b_noPref) {
	c = (b_noPref ? '' : '') + c.toUpperCase();
	if (this.o_samp) 
		this.o_samp.innerHTML = '<font face=Tahoma size=2>' + c +' <font color=white>' + c + '</font></font>'
	if(this.doc.layers)
		this.sample.bgColor = c;
	else { 
		if (this.sample.backgroundColor != null) this.sample.backgroundColor = c;
		else if (this.sample.background != null) this.sample.background = c;
	}
}

function TCGenerateSafe() {
	var s = '';
	for (j = 0; j < 12; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i ++)
				s += this.bldCell(k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, i * 51, 8, 10);
		s += "</tr>";
	}
	return s;
}

function TCGenerateWind() {
	var s = '';
	for (j = 0; j < 12; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i++)
				s += this.bldCell(i * 51, k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, 8, 10);
		s += "</tr>";
	}
	return s	
}
function TCGenerateMac() {
	var s = '';
	var c = 0,n = 1;
	var r,g,b;
	for (j = 0; j < 15; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i++){
				if(j<12){
				s += this.bldCell( 255-(Math.floor(j / 2) * 51), 255-(k * 51 + (j % 2) * 51 * 3),255-(i * 51), 8, 10);
				}else{
					if(n<=14){
						r = 255-(n * 17);
						g=b=0;
					}else if(n>14 && n<=28){
						g = 255-((n-14) * 17);
						r=b=0;
					}else if(n>28 && n<=42){
						b = 255-((n-28) * 17);
						r=g=0;
					}else{
						r=g=b=255-((n-42) * 17);
					}
					s += this.bldCell( r, g,b, 8, 10);
					n++;
				}
			}
		s += "</tr>";
	}
	return s;
}

function TCGenerateGray() {
	var s = '';
	for (j = 0; j <= 15; j ++) {
		s += "<tr>";
		for (k = 0; k <= 15; k ++) {
			g = Math.floor((k + j * 16) % 256);
			s += this.bldCell(g, g, g, 9, 7);
		}
		s += '</tr>';
	}
	return s
}

function TCDec2Hex(v) {
	v = v.toString(16);
	for(; v.length < 6; v = '0' + v);
	return v;
}

function TCChgMode(v) {
	for (var k in this.divs) this.hide(k);
	this.show(v);
}

function TColorPicker(field) {
	this.build0 = TCGenerateSafe;
	this.build1 = TCGenerateWind;
	this.build2 = TCGenerateGray;
	this.build3 = TCGenerateMac;
	this.show = document.layers ? 
		function (div) { this.divs[div].visibility = 'show' } :
		function (div) { this.divs[div].visibility = 'visible' };
	this.hide = document.layers ? 
		function (div) { this.divs[div].visibility = 'hide' } :
		function (div) { this.divs[div].visibility = 'hidden' };
	// event handlers
	this.C       = TCChgMode;
	this.S       = TCSelect;
	this.P       = TCPaint;
	this.popup   = TCPopup;
	this.draw    = TCDraw;
	this.dec2hex = TCDec2Hex;
	this.bldCell = TCBuildCell;
	this.divs = [];
}

function TCDraw(o_win, o_doc) {
	this.win = o_win;
	this.doc = o_doc;
	var 
	s_tag_openT  = o_doc.layers ? 
		'layer visibility=hidden top=54 left=5 width=182' : 
		'div style=visibility:hidden;position:absolute;left:6px;top:54px;width:182px;height:0',
	s_tag_openS  = o_doc.layers ? 'layer top=32 left=6' : 'div',
	s_tag_close  = o_doc.layers ? 'layer' : 'div'
		
	this.doc.write('<' + s_tag_openS + ' id=sam name=sam><table cellpadding=0 cellspacing=0 border=1 width=181 align=center class=bd><tr><td align=center height=18><div id="samp"><font face=Tahoma size=2>sample <font color=white>sample</font></font></div></td></tr></table></' + s_tag_close + '>');
	this.sample = o_doc.layers ? o_doc.layers['sam'] : 
		o_doc.getElementById ? o_doc.getElementById('sam').style : o_doc.all['sam'].style

	for (var k = 0; k < 4; k ++) {
		this.doc.write('<' + s_tag_openT + ' id="p' + k + '" name="p' + k + '"><table cellpadding=0 cellspacing=0 border=1 align=center>' + this['build' + k]() + '</table></' + s_tag_close + '>');
		this.divs[k] = o_doc.layers 
			? o_doc.layers['p' + k] : o_doc.all 
				? o_doc.all['p' + k].style : o_doc.getElementById('p' + k).style
	}
	if (!o_doc.layers && o_doc.body.innerHTML) 
		this.o_samp = o_doc.all 
			? o_doc.all.samp : o_doc.getElementById('samp');
	this.C(this.initPalette);
	if (this.field.value) this.P(this.field.value, true)
}

</script>