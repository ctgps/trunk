/**
 * $.shareList
 * @extends jquery.1.4.2
 * @fileOverview 创建一个分享按钮列表
 * @author 明河共影
 * @email mohaiguyan12@126.com
 * @site wwww.36ria.com
 * @version 1.6.1
 * @update 2010-12-13
 * Copyright (c) 2010-2010 明河共影
 * @example
 *    $("#share").shareList();
 */
var ShareJquery = jQuery; 
(function($){
	$.fn.shareList = function(options){
		var opts;
		var DATA_NAME = "shareList";
		//返回API
		if(typeof options == 'string'){
			 if(options == 'api'){
			 	return $(this).data(DATA_NAME).interfaces[$(this).data(DATA_NAME).current];
			 }else if(options == 'interfaces'){
			 	return $(this).data(DATA_NAME).interfaces;
			 }
		}
		else{
			var options = options || {};
			//覆盖参数
			opts = $.extend(true,{},$.fn.shareList.defaults,options);
			if(options.shareSites)  opts.shareSites = options.shareSites;
		}
		
		return $(this).each(function(){
			//调用方法
			if(typeof options == 'string'){
				
			}
			//创建
			else{
				var _shareList = new yijs.ShareList(opts);
				_shareList.$applyTo = $(this);
				_shareList.render();
				$(this).data(DATA_NAME,_shareList);
			}
		})
	}
	var yijs = yijs || {};
	yijs.ShareList = function(options){
		this.options = $.extend($.fn.shareList.defaults,options);
		this.effect = this.options.effect;
		//组件所起作用的对象
		this.$applyTo = options.applyTo && $(options.applyTo) || null;
		this.$li = null;
		this.width = 0;
		this.liWidth = 0;
		this.tpl = this.options.tpl;
		this.getListAjaxOptions = this.options.getListAjaxOptions;
		this.pageUrl = encodeURIComponent(window.location.href);
		this.pageTitle = encodeURIComponent(document.title.substring(0,76));
		if($.browser.msie&&($.browser.version == "6.0")&&!$.support.style) this.options.showShadow = false;
	}
	yijs.ShareList.prototype = {
		/**
		 * 运行
		 */
		render : function(){
			var _that = this,_cls = this.options.cls,smallIcon = '';
			if(this.$applyTo != null && this.$applyTo.size() > 0){
				if(_that.options.smallIcon) smallIcon = 'share-small-icon';
				this.$applyTo.html("<div class='"+_cls.shareList+" "+smallIcon+"'></div>");
				this.$applyTo.css({"position":"relative","overflow":"hidden"});
				this.options.style && this.$applyTo.css(this.options.style);
				this.$applyTo = this.$applyTo.children("."+_cls.shareList);

				$.ajax({
					url : this.getListAjaxOptions.url,
					type : this.getListAjaxOptions.type,
					dataType : this.getListAjaxOptions.dataType,
					success : function(data){
						_that._getListSuccess(data);
					},
					beforeSend : function(){
						_that._getListBeforeSend();
					}
				});
			}
		},
		/**
		 * 成功获取分享列表后执行的回调函数
		 * @param {Object} data json数据源
		 */
		_getListSuccess : function(data){
			var _that = this;
			var _cls = this.options.cls;
			_that._createList(data,function(){
				_that.$li = _that.$applyTo.children();
				_that.options.showShadow && _that.$li.children("."+_cls.text).hide();
				_that._setLocalBookmark();
				_that._addShadow();
				_that._setUlWidth();
				_that.options.follow  && _that._followMouseScroll();
				//鼠标滑过li
				_that.$li.hover(function(){
					var index = $(this).index();
					_that.iconToTop(index);
				},function(){
					var index = $(this).index();
					_that.iconTopDefault(index);					
				})
				_that.getListAjaxOptions.success && _that.getListAjaxOptions.success.call(this,data);					
			});			
		},
		/**
		 * 触发ajax请求前的回调函数
		 */
		_getListBeforeSend : function(){
			this.$applyTo.html("<img src='"+this.options.preloadImgSrc+"' class='"+this.options.cls.preload+"' />");
			this.getListAjaxOptions.beforeSend && this.getListAjaxOptions.beforeSend.call(this,data);			
		},		
		/**
		 * 创建分享按钮列表
		 * @param {Object} data json数据源
		 * @param {function} afterCreate 回调函数
		 */
		_createList : function(data,afterCreate){
				var _that = this;
				var _li;
				var _list = _that.options.shareSites;
				var _ali = []; 
				$.each(_list,function(a){
					$.each(data,function(i){
						if(data[i].name == _list[a]){
							_li = _that.tpl.TFtpl(data[i]);
							_li = _li.TFtpl({url:_that.pageUrl,title:_that.pageTitle});
							_ali.push(_li);
							return true;
						}
					})							
				})
				_that.$applyTo.children().fadeOut("slow",function(){
					_that.$applyTo.html(_ali.join(""));
					afterCreate && afterCreate.call(this);
				})			
				
		},
		/**
		 * 给本地收藏夹按钮绑定特殊事件
		 */
		_setLocalBookmark : function(){
			var _that = this;
			this.$li.children("."+this.options.cls.icon+"[name=favorite]").click(function(){
				_addBookmark(window.location.href,document.title.substring(0,76));
				return false;
			}).next().click(function(){return false});
			//添加到本地收藏夹
			function _addBookmark(url,title) {
			    if (window.sidebar) {
			        window.sidebar.addPanel(title,url,"");
			    } 
			    else if(document.all) {
			        window.external.AddFavorite( url, title);
			    }
			    else if( window.opera && window.print ) {
			        return true;
			    }
			}			
		},
		/**
		 * 给每个图标下加个阴影图片
		 */
		_addShadow : function(){
			var _opt = this.options,src;
			if(_opt.showShadow){
				src = _opt.smallIcon && _opt.smallShadowSrc || _opt.shadowSrc; 
				$("<div class='"+_opt.cls.iconShadow+"'><img src='"+src+"' /></div>").appendTo(this.$li);
			}
		},
		/**
		 * 设置列表宽度
		 */
		_setUlWidth : function(){
			var _$liFirst = this.$li.eq(0);
			this.liWidth = _$liFirst.width()+parseInt(_$liFirst.css("marginLeft"))+parseInt(_$liFirst.css("marginRight"));
			this.$applyTo.width(this.liWidth * this.$li.size());
			this.width = this.$applyTo.width();
		},
		/**
		 * 列表随鼠标滚动
		 */
		_followMouseScroll : function(){
			var _that = this;
			var _opts = this.options;
			var _parentWidth = this.$applyTo.parent().width();
			var _totScroll = this.width-_parentWidth;
			if(this.width > _parentWidth){
				this.$applyTo.mousemove(function(e){
					if(_opts.allowSroll){
						var _pageX = e.pageX;
						var _pos = _that.$applyTo.offset();
						_that.$applyTo.css({marginLeft:'-'+_totScroll*(Math.max(_pageX-_pos.left,0)/_that.width)+'px'});	
					}
				})
				this.$applyTo.mouseleave(function(e){
					if(!jQuery.browser.msie || jQuery.browser.version != "7.0"){
						var _marginLeft = Math.abs(Math.floor(parseInt(_that.$applyTo.css("marginLeft"))));
						var _is = _marginLeft % _that.liWidth;
						if (_is != 0) {
							var _d = Math.floor(_marginLeft / _that.liWidth);
							_that.$applyTo.animate({"marginLeft": "-"+_d * _that.liWidth},"fast")
						}						
					}
					
				})
			}
			
		},
		/**
		 * 鼠标滑过时图标向上移动，阴影发生变化
		 */
		iconToTop : function(index){
			var _that = this;
			var _lihoverEffect = _that.effect.liHover;
			var _speed = this.options.speed;
			var _$currentLi = this.$li.eq(index);
			var _icon_cls = this.options.cls.icon;
			var shadowImgAnim = this.options.smallIcon && _lihoverEffect.smallShadowImg || _lihoverEffect.shadowImg; 
			if(_$currentLi.size() > 0){
				_$currentLi.children("."+_icon_cls).stop().animate(_lihoverEffect.icon,_speed,function() {
					$(this).animate(_lihoverEffect.iconBack,_speed);
				});
				//改变阴影大小和透明度
				if(this.options.showShadow){
					var _$shadowDiv = _$currentLi.children("."+this.options.cls.iconShadow);
					_$shadowDiv.children("img").css('display','block');
					_$shadowDiv.children("img").stop().animate(shadowImgAnim,_speed);
					_$shadowDiv.stop().animate(_lihoverEffect.shadowDiv,_speed);
				}				
			}			
		},
		/**
		 * 鼠标离开时图标复位，阴影复位
		 */
		iconTopDefault : function(index){
			var _that = this;
			var _liMouseoutEffect = _that.effect.liMouseout;
			var _speed = this.options.speed;
			var _$currentLi = this.$li.eq(index);
			var _icon_cls = this.options.cls.icon;
			var shadowImgAnim = this.options.smallIcon && _liMouseoutEffect.smallShadowImg || _liMouseoutEffect.shadowImg; 
			if (_$currentLi.size() > 0) {
				_$currentLi.children("."+_icon_cls).stop().animate(_liMouseoutEffect.icon, _speed, function() {
					$(this).animate(_liMouseoutEffect.iconBack, _speed);
				});
				//复位阴影大小和透明度
				if(this.options.showShadow){
					var _$shadowDiv = _$currentLi.children("."+this.options.cls.iconShadow);
					_$shadowDiv.children("img").stop().animate(shadowImgAnim,_speed);
					_$shadowDiv.stop().animate(_liMouseoutEffect.shadowDiv,_speed);
				}				
		    }			
		}			
		
	}
	//接口数组
	$.fn.shareList.interfaces = [];
	//样式
	$.fn.shareList.classes = {
		defaults : {
				shareList   : "share-list",
				shareListLi : "share-list-item",
				icon        : "share-list-icon",
				text        : "share-list-text",
				iconShadow  : "share-list-icon-shadow",
				preload     : "share-list-preload"
		}
	}
	//模板
	$.fn.shareList.tpl = {
		defaults : '<div class="share-list-item">'+
                       '<a href="{href}" name="{name}" localName="{localName}" class="share-list-icon icon-{name}" target="_blank"></a>'+
					   '<a href="{href}" class="share-list-text">{localName}</a>'+
                   '</div> '
	}		
	//默认参数
	$.fn.shareList.defaults = {
				/**想要显示的按钮列表*/
				shareSites : ["9dian","feerbook","chouti","diglog","sinaminiblog","renren","zhuaxia","xianguo","greader","qqshuqian",
				              "douban","twitter","favorite","kaixin001","baiducang","gbuzz","digu","zuosa",
							  "renjian","sohubai"],
				/**ajax配置*/			  
				getListAjaxOptions : {
					type: "GET",
					url : "js/shareListData.js",
					dataType : "json"
				},
				/**是否使用小图标模式*/
				smallIcon : false,
				/**是否允许列表随鼠标滚动*/
				allowSroll   : true,
				/**是否显示阴影*/
				showShadow	 : true,
				/**是否跟随鼠标*/
				follow      : true,
				/**阴影图片路径*/
				shadowSrc	 : "style/images/icon-shadow.png",
				smallShadowSrc : "style/images/icon-shadow-small.png",
				/**预加载动画图片*/
				preloadImgSrc : "style/images/loading.gif",
				/**动画速度*/
				speed        : 250,	
				/**样式*/
				style : null,				
				/**动画效果设置*/			
				effect       : {
					/**鼠标滑过li时的动画*/	
					liHover : {
						icon 	 : {marginTop: "-6px"},
						iconBack : {marginTop: "-6px"}, 
						shadowImg  : { width: "65%", height: "10px", marginLeft: "10px"},
						smallShadowImg : { width: "65%", height: "10px", marginLeft: "8px"},
						shadowDiv : {opacity: 0.25}						
					},
					/**鼠标离开li时的动画*/	
					liMouseout : {
						icon     : {marginTop: "4px"},
						iconBack : { marginTop: "0px" },
						shadowImg   : { width: "100%", height: "21px", marginLeft: "0"},
						smallShadowImg : { width: "100%", height: "15px", marginLeft: "0"},
						shadowDiv : {opacity: 1}		
					}
				},
				/**样式名集合*/ 	
				cls          : $.fn.shareList.classes.defaults,
				/**模板*/
				tpl          : $.fn.shareList.tpl.defaults
				
	};	
})(jQuery);

//简单的转换模板函数
String.prototype.TFtpl = function(o){   
    return this.replace(/{([^{}]*)}/g,   
            function (a,b){   
                var r = o[b];   
                return typeof r==='string'?r:a;   
            }   
    );   
       
}; 
