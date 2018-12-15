<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>富裕通</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('js/skin/layer.css')}}" />
    <link rel="stylesheet" href="{{asset('css/main.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('packages/select2/css/select2.min.css')}}" />
    <link rel="stylesheet" href="//at.alicdn.com/t/font_z9hurkd8gzo7p66r.css" />
    <script type="text/javascript" src="{{asset('js/jquery-1.10.2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/layer.js')}}"></script>
    <script src="{{asset('packages/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('packages/clipboard/clipboard.min.js')}}"></script>
    @section('style')
    <style>

    </style>
    @show
</head>
<body>
<div class="head">
	<div class="center_content head_content">
		<div class="logo_box">
			<span class="pull-right">
      <span class="log_tel"><i class="iconfont icon-dianhua1"></i>&nbsp;0596-6859322</span>
      <span class="log_email"><i class="iconfont icon-youxiang"></i>&nbsp;sales@legend56.com</span>


      </span>
			<img src="{{asset('img/logo-l-y.png')}}"/>
		</div>
	</div>
</div>
<div id="header">
    @include('block.topnav')
</div>
@yield('content')
@include('block.footer')

@section('script')
<script>
$(".track-search").click(function(){
    var no = $('#waybill').val();
    if( !no ) {
        layer.msg("请输入运单号或者柜号");
        return false ;
    }
    $("#trackform").submit();
});

$('.quick-order').click( function(){
    var from = $('#shipment').val()  ;
    var to = $('#destinationport').val()  ;
    if( !from ) {
        layer.msg("请选择出发港");
        return false ;
    }
    if( !to ) {
        layer.msg("请选择到达港");
        return false ;
    }
    if( from == to ) {
        layer.msg("出发港和目的港不能一样");
        return false ;
    }

    $('#demandaddform').submit();

} );
$('select').select2({
	matcher: function(term, text) {
	       if ( typeof term.term == 'undefined' ) {
				return text ;
		   }
		   var attr = $(text.element).attr('alt');
		   attr = attr ? attr : '' ;
	       return text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
				attr.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ? text : null ;
	   }
});
</script>
@show
<!-- WPA Button Begin -->
<script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzgwMDgzNTE2OF80Nzk4NzlfODAwODM1MTY4Xw"></script>
<!-- WPA Button End -->
<script>
$(document).ready( function(){
BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: 'foot-qq-serve'});
});
</script>

</body>
</html>
