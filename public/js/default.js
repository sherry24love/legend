!function($,W,D,undefined){
W.df = {
	$id:$('.wrap'), //ID节点
	pg:[0,0], //存放页面宽高数值
	em:0, //基础值;
	mob:/Mobile|Browser/i.test(navigator.userAgent), //判断是否为移动浏览器
	wechat:/MicroMessenger/i.test(navigator.userAgent), //是否运行在微信中
	ready:function(fn){ //页面装载可执行JS方法
		$(function(){setTimeout(function(){$.type(fn) === 'function' && fn();return;},300);});
	},
	size:function(){ //尺寸初始化或重构
		df.pg[0] = W.innerWidth; //获取窗口宽度
		if(df.pg[0] > 640) df.pg[0] = 640; //640以上的宽度处理
		df.pg[1] = W.innerHeight; //获取窗口高度
		var rem = df.em = df.pg[0]/32;
		if (em_basic != rem) {
			$('html').css({'font-size':rem+'px'}); // 字体大小初始化
			em_basic = rem;
		}
		df.$id.css({'height':'auto','min-height':df.pg[1]+'px'}); //主容器高度初始化
		return;
	},
	init:function(){ //初始化
		var $load = $('#loading');
		df.size(); //初始化数值
		$load.hide();$load.remove(); //去掉加载过渡层
		$(W).on("onorientationchange" in W?"orientationchange":"resize",function(){setTimeout(function(){if(df.pg[0] != W.innerWidth && !df.wechat) location.replace(location.href)},100)}); //手机转屏或屏莫尺寸变更处理
		return;
	}
};
$(function(){df.init()});
}(jQuery,window,document);