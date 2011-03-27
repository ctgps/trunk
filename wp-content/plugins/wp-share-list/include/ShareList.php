<?php
/**
*分享列表类
*@autor 明河共影
*@version 1.6.1.2
*@site http://www.36ria.com/	
*/
if(!class_exists("ShareList")){
	class ShareList{
		var $adminOptionsName = " ShareListAdminOptions " ;		  
		//构造函数
		function ShareList(){
		
		}
		//初始化
		function init(){
			$this->getAdminOptions();
		}
		//获取参数配置
		function getAdminOptions(){
		    $defaults = array('addJquery' => 'true',
							  'autoShow' => 'false',
							  'listWidth' => 660,	
		                      'shareSites' => array("favorite" => "本地收藏夹","qqzone" => "QQ空间","sinaminiblog" => "新浪微博","tao" => "淘江湖","9dian" => "豆瓣9点","feerbook" => "feerbook","chouti" =>"抽屉","diglog" => "奇客发现","renren" => "人人网","kaixin001" => "开心网","xianguo" => "鲜果","qqshuqian"=>"QQ书签","baiducang" => "百度收藏","gbuzz" => "gbuzz","digu" => "嘀咕","sohubai" => "搜狐白社会","tsohu" => "搜狐微博","vtqq" => "腾讯微博"),
							  'showShadow' => 'true',
							  'allowSroll' => 'true',
							  'follow' => 'true');	
			$this->defaults = $defaults;				  				
			//获取参数
			$options = get_option($this->adminOptionsName);
			//覆盖默认参数
			if(!empty($options)){
				foreach($options as $key => $option){
					 $defaults[$key] = $option;
				}
			}
			//更新参数
			update_option($this->adminOptionsName,$defaults);
			return $defaults;
		}
		//输出jquery引用
		function addJs(){
			$options = $this->getAdminOptions();
			if(is_single() || is_page()){					
				echo '<link type="text/css" rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/style/css/shareList.css" />' . "\n";	
				if($options['addJquery'] == 'true'){
					echo '<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/js/jquery-1.4.2.min.js"></script>'."\n";
				} 	
				echo '<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/js/jquery.shareList.js"></script>'."\n";
				$this->addShareList(); 		
			}		
		}
		function addShareList(){
				$options = $this->getAdminOptions();
				
				$dataUrl = get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/js/shareListData.js';
				$shadowSrc = get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/style/images/icon-shadow.png';
				$smallShadowSrc = get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/style/images/icon-shadow-small.png';
				$preloadImgSrc = get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list/style/images/loading.gif';
				$style = '{width:'.$options['listWidth'].'}';
				$showShadow = $options['showShadow'];
				$smallIcon = $options['smallIcon'];
				if(isset($options['smallIcon'])){
					$smallIcon = $options['smallIcon'];
				}else{
					$smallIcon = 'false';
				}  
				$follow = $options['follow'];
				$sites = "[";
				$_a = array();
				foreach($options['shareSites'] as $key => $value){
				 	$_a[] = '"'.$key.'"';
				}
				$sites .= implode(',',$_a);
				$sites .="]";
				
				echo '<script type="text/javascript">'.
					 	 'ShareJquery(function(){'.
								'ShareJquery("#wp-share-list-container").shareList({getListAjaxOptions:{url:"'.$dataUrl.'"},shadowSrc : "'.$shadowSrc.'",smallShadowSrc : "'.$smallShadowSrc.'",preloadImgSrc:"'.$preloadImgSrc.'",shareSites:'.$sites.',style:'.$style.',showShadow:'.$showShadow.',follow:'.$follow.',smallIcon:'.$smallIcon.'});'.
						  '})'.
					 '</script>';				
		}
		//将js调用添加到content
		function addContent($content){
			$options = get_option($this->adminOptionsName);
			$c = $content;
			if($options['autoShow'] == 'true'){
				if(is_single() || is_page()){
					$h = '<div id="wp-share-list-container"></div>';
					$c = $c.$h; 
				}				
			}
			return $c;
		}
		function printContainer(){
			echo '<div id="wp-share-list-container"></div>';
		} 
		//输出管理页面
		function printAdminPage(){
			//获取参数
			$options = $this->getAdminOptions();
			$pluginUri = get_bloginfo('wpurl').'/wp-content/plugins/wp-share-list';
			if(isset($_POST['update-options']) || isset($_POST['reset-options'])){
				if(isset($_POST['hidden-share-site-names'])){
					$names = explode(',',$_POST['hidden-share-site-names']);
					$localNames = explode(',',$_POST['hidden-share-site-localNames']);
					$shareSites = array();
					for($i = 0;$i<count($names);$i++){
						$shareSites[$names[$i]] = $localNames[$i];
					}
					$options['shareSites'] = $shareSites;
					
				}
				//引入jquery
				if(isset($_POST['addJquery'])){
					$options['addJquery'] = $_POST['addJquery'];
				}else{
					$options['addJquery'] = 'false';
				}
				//自动在文章尾部显示
				if(isset($_POST['autoShow'])){
					$options['autoShow'] = $_POST['autoShow'];
				}else{
					$options['autoShow'] = 'false';
				}
				//列表宽度
				if(isset($_POST['listWidth'])){
					$options['listWidth'] = $_POST['listWidth'];
				}	
				//是否显示阴影
				if(isset($_POST['showShadow'])){
					$options['showShadow'] = $_POST['showShadow'];
				}else{
					$options['showShadow'] = 'false';
				}
				//是否跟随
				if(isset($_POST['follow'])){
					$options['follow'] = $_POST['follow'];
				}else{
					$options['follow'] = 'false';
				}
				//小图
				if(isset($_POST['smallIcon'])){
					$options['smallIcon'] = $_POST['smallIcon'];
				}else{
					$options['smallIcon'] = 'false';
				}					
				if($_POST['reset-options'] == 'true'){
					update_option($this->adminOptionsName,$this->defaults);
					$pluPageUrl = get_bloginfo('wpurl').'/wp-admin/options-general.php?page=wp-share-list.php';
					echo '<div style="margin:100px auto;font-size:14px;width:350px;padding:20px;border:2px dashed #E3E3E3;background-color:#ffffff;text-align:center;">配置重置成功！<a href="'.$pluPageUrl.'">点此返回配置页面</a></div>';
			    }else{
					update_option($this->adminOptionsName,$options);
					$pluPageUrl = get_bloginfo('wpurl').'/wp-admin/options-general.php?page=wp-share-list.php';
					echo '<div style="margin:100px auto;font-size:14px;width:350px;padding:20px;border:2px dashed #E3E3E3;background-color:#ffffff;text-align:center;">配置更新成功！<a href="'.$pluPageUrl.'">点此返回配置页面</a></div>';				
				}																
			}
			else{
			?>
            <script type="text/javascript" src="<?php echo $pluginUri;?>/js/jquery-1.4.2.min.js"></script>
            <link rel="stylesheet" href="<?php echo $pluginUri;?>/style/css/base.css" type="text/css" media="screen" />
            <link rel="stylesheet" href="<?php echo $pluginUri;?>/style/css/admin-options.css" type="text/css" media="screen" />
            <link rel="stylesheet" href="<?php echo $pluginUri;?>/style/css/shareList.css" type="text/css" media="screen" />
            <script type="text/javascript">
				$(function(){
					//用户选择的分享站点
					var sites = [];
				    <?php foreach($options['shareSites'] as $name => $localName) {?>
						sites.push("<?php echo $name;?>");
				    <?php } ?>					
					//读取json
					$.getJSON("<?php echo $pluginUri.'/js/shareListData.js'?>",function(data){
						var _a = [];
						$.each(data,function(i){
							var _h = '<li class="l share-list-item">'+
							         '<span class="share-list-icon icon-'+data[i].name+'"></span>'+
									 '<div><input type="checkbox" class="share-list-checkbox" name="sites[]" value="'+data[i].name+'" localName ="'+data[i].localName+'" />&nbsp;&nbsp;<label>'+data[i].localName+'</label></div>'+
							         '</li>';
							_a.push(_h);		 
						});
						//将站点插入列表
						$("#sites").html(_a.join(''));
						//选中用户选择的分享站点
						$("#sites").find(".share-list-checkbox").each(function(){
							var _$this = $(this);
							$.each(sites,function(i){
								if(_$this.val() == sites[i]) _$this.attr("checked",true);
							})
						}).click(function(){
							var _val = $(this).val();
							var _checked = $(this).attr("checked");
							var _option = '<option value="'+_val+'">'+$(this).next("label").text()+'</option>';						
							//选中多选框
							if(_checked){
								$("#shareSites").append(_option);
							}else{
								$("#shareSites").children().each(function(){
									if($(this).val() == _val) $(this).remove();
								})								
							} 
						})
					})
					
					var $btns = $("#share-list-admin-btns");
					var noSelectMsg = "请选中多选框的选择项！";
					var $shareSites = $("#shareSites");
					//上移
					$btns.children("#to-up").click(function(){
						var $selected = getSelected();
						moveSelect($selected,"up")
					})
					//下移
					$btns.children("#to-down").click(function(){
						var $selected = getSelected();
						moveSelect($selected,"down");
					})	
					//删除
					$btns.children("#remove").click(function(){
						var $selected = getSelected();
						moveSelect($selected,function($obj){
							var _name = $obj.val();
							$(".share-list-checkbox").each(function(){
								if(_name == $(this).val()) $(this).removeAttr("checked");
							})
						});
					})
					//全部删除
					$btns.children("#remove-all").click(function(){
						$shareSites.children().remove();
						$(".share-list-checkbox").removeAttr("checked");
					})	
					
					//表单提交
					$("#share-list-form").submit(function(){
						var _names = [];
						var _localNames = [];
						$("#shareSites").children().each(function(){
							_names.push($(this).val());
							_localNames.push($(this).text());
						})
						$("#hidden-share-site-names").val(_names.join(','));
						$("#hidden-share-site-localNames").val(_localNames.join(','));	
					})	
					
					$('.reset-btn').bind('click',function(){
						$("#reset-options").val('true');
						$("#share-list-form").submit();
						return false;
					})																	
				})
				//获取多选框的选中项
				function getSelected(){
					var $shareSites = $("#shareSites");
					var $selected = null;
					$shareSites.children().each(function(){
						if($(this).attr("selected")) $selected = $(this);
					})
					return $selected;
				}
				//移动选中项
				function moveSelect($selected,up2down){
					if($selected != null){
						var _$clone = $selected.clone();
						_$clone.attr("selected",true);
						selectOption(_$clone);
						if(up2down == "up"){
							//上移
							var _$prev = $selected.prev();
							if(_$prev.size() > 0 ){
								_$prev.before(_$clone);
								$selected.remove();
								return true;
							}							
						}else if(up2down == "down"){
							//下移
							var _$next = $selected.next();
							 _$next.after(_$clone);
							if(_$next.size() > 0 ){
								_$next.after(_$clone);
								$selected.remove();
								return true;
							}								 
						}else{
							selectOption($selected.next().size() > 0 && $selected.next() || $selected.prev());
							if(typeof up2down == "function"){
								up2down.call(this,$selected);
							}
							$selected.remove();
						}
						
						
					}else{
						alert(noSelectMsg);
					}
				}
				//选中指定项
				function selectOption($obj){
					$obj.attr("selected",true);
				}
			</script>
            <div class="wrap">
            	<form method="post" id="share-list-form" action="<?php echo $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI']; ?>">
                	<h2>收藏分享插件(wp-share-list)1.6.1设置</h2>
                    <p>作者：<a href="http://www.36ria.com">明河共影</a>,欢迎访问我的<a href="http://www.36ria.com">博客</a>。</p>
                    <p class="bor-b-b">使用插件遇到问题？请<a href="http://www.36ria.com/2672" target="_blank">猛击这里进入插件问答页面</a>，如果依旧没解决问题，请给明河留言，明河的email：riahome@126.com，微博：<a href="http://t.sina.com.cn/36ria">http://t.sina.com.cn/36ria</a>。<p>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mar-t-10">
                     <tr>   
                        <td>&nbsp;</td>
                        <td colspan="2"><input type="checkbox" id="addJquery" name="addJquery" value="true"  <?php if($options['addJquery'] == 'true') echo 'checked="checked"';  ?> />
                        	&nbsp;&nbsp;<label>引入插件的jquery1.4.2库（如果你的wordpress主题已经有的jquery1.4.2，可以去掉）</label></td>
                      </tr> 
                     <tr>   
                        <td>&nbsp;</td>
                        <td colspan="2"><input type="checkbox" id="autoShow" name="autoShow" value="true"  <?php if($options['autoShow'] == 'true') echo 'checked="checked"';  ?> />
                        	&nbsp;&nbsp;<label>在文章尾部自动显示（推荐手动插入，语法是：&lt;?php if(function_exists('wp_share_list')) wp_share_list() ?&gt;）   </label></td>
                      </tr>
                     <tr>   
                        <td>分享列表宽度</td>
                        <td colspan="2"><input type="text" id="listWidth" class="input-text" name="listWidth" value="<?php echo $options['listWidth'];?>" /></td>
                      </tr>  
                     <tr>   
                        <td>&nbsp;</td>
                        <td colspan="2"><input type="checkbox" id="showShadow" name="showShadow" value="true"  <?php if($options['showShadow'] == 'true') echo 'checked="checked"';  ?> />
                        	&nbsp;&nbsp;<label>是否显示阴影,(不显示阴影时显示分享站点名)</label></td>
                      </tr>                                                                                       
                      <tr>
                     <tr>   
                        <td>&nbsp;</td>
                        <td colspan="2"><input type="checkbox" id="follow" name="follow" value="true"  <?php if($options['follow'] == 'true') echo 'checked="checked"';  ?> />
                        	&nbsp;&nbsp;<label>是否跟随鼠标移动</label></td>
                      </tr>
                     <tr>   
                        <td>&nbsp;</td>
                        <td colspan="2"><input type="checkbox" id="smallIcon" name="smallIcon" value="true"  <?php if($options['smallIcon'] == 'true') echo 'checked="checked"';  ?> />
                        	&nbsp;&nbsp;<label>使用小图标版（小图标版的图标大小为25*25，大图版的图标大小为40*40）</label></td>
                      </tr>                                                                                                              
                      <tr>                      
                        <td width="10%" valign="top">
							分享站点显示                       
                        </td>
                        <td width="28%">
                                <select name="shareSites" multiple="multiple" id="shareSites" class="multiple-select">
                                  <?php foreach($options['shareSites'] as $name => $localName) {?>
                                  <option value="<?php echo $name;?>"><?php echo $localName;?></option>
                                  <?php } ?>
                                </select>    
                                <div class="mar-t-10" id="share-list-admin-btns">
                                	<input type="button" value="↑上移↓" id="to-up" />
                                    <input type="button" value="↓下移" id="to-down" />
                                    <input type="button" value="删除" id="remove" />
                                    <input type="button" value="全部删除" id="remove-all" />
                                </div>                         
                        </td>
                        <td width="62%" valign="top">
                        	<h3 id="add-site-header">添加（选中即往多选框追加站点）</h3>
							<ul class="clearfix share-list" id="sites">
									<img src="<?php echo $pluginUri;?>/style/images/loading.gif" />
                            </ul>
                        </td>
                     </tr>
                     <tr>   
                        <td>&nbsp;</td>
                        <td  colspan="2">
                        	<input type="hidden" value="" id="hidden-share-site-names" name="hidden-share-site-names" />
                            <input type="hidden" value="" id="hidden-share-site-localNames" name="hidden-share-site-localNames" />	
							<div class="mar-t-10"><input type="submit" value="提交新的配置" class="submit-btn" name="update-options" />
                                                  &nbsp;&nbsp;&nbsp;<a href="" class="reset-btn">重置配置</a><input type="hidden" value="" name="reset-options" id="reset-options" />
                            </div>
                        </td>                    
                      </tr>
                    </table>

                </form>
            </div>
            <?php
			}
		}
	}
}
?>