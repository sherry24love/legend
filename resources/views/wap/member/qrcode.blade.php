@extends('wap.layout')

@section('content')
<div class="aui-content-padded aui-margin-b-15" >
    <div class="aui-row aui-row-padded">
        <div class="aui-col-xs-12" style="text-align: center;">
            {!! QrCode::size(130)->margin(0)->generate( route('wap.index' , ['rec_id' => auth()->guard()->user()->id ]) )!!}
        </div>
    </div>
    <div class="aui-content aui-text-danger" style="text-align: center;">
          我的推广码：{{str_pad( auth()->guard()->user()->id , 4 , '0' , STR_PAD_LEFT )}}
    </div>
    
    <p class="aui-font-size-16 aui-margin-b-15 aui-margin-t-15" style="line-height: 1.5rem;">
    	<strong>推广地址：</strong>{{route('wap.index' , ['rec_id' => str_pad( auth()->guard('wap')->user()->id , 4 , '0' , STR_PAD_LEFT ) ])}}
    </p>
    @if( session('wechat'))
	    <p>
	    	点击右上角分享本页面吧！
	    </p>
    @else
    <p>
    	<div class="aui-btn btn aui-btn-info aui-btn-block aui-btn-danger aui-btn-outlined" data-clipboard-text="{{route('wap.register' , ['rec_id' => str_pad( auth()->guard()->user()->id , 4 , '0' , STR_PAD_LEFT ) ])}}">点击复制专属推广链接</div>
    </p>
    @endif

</div>
@endsection

@section('script')

@endsection