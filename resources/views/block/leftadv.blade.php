@if( isset( $leftadv ) && !empty( $leftadv ) ) 
	<a href="{{$leftadv->link}}" target="_blank" style="margin-bottom: 10px;"><img src="{{asset( $leftadv->cover)}}" style="max-width: 100%;width: 100%;display: block;"></a>
@endif