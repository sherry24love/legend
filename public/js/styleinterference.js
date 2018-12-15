!function(w,d,undefined){
	var dom = d.getElementsByTagName('html')[0],pgw = dom.offsetWidth,em = (pgw > 640)?20:pgw/32,css = document.createElement('style');
	css.type="text/css";
	css.innerHTML = "html {font-size:"+em+"px}";
	d.getElementsByTagName('head')[0].appendChild(css);
	w.em_basic = em;
}(window,document);