@extends('wap.layout')

@section('content')

<div class="zx_det">
	<h3>{{$posts->title}}</h3>
	<div class="wordtitle">
		<span>{{$posts->category->name or ''}}</span><span>{{$posts->created_at}}</span>
	</div>
	<div class="zx_der_txt">
	@if( $posts->cover )
		<img src="{{asset( $posts->cover) }}" style="width: 100%;max-width: 100%;">
	@endif
		{!! $posts->content !!}
	</div>
</div>

@endsection

@section('footer')
@endsection