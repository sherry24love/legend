@if( isset( $adv ) && !empty( $adv ) ) 
	<a href="{{$adv->link}}" target="_blank" style="margin-bottom: 10px;"><img src="{{asset( $adv->cover)}}" style="max-width: 100%;width: 100%;display: block;"></a>
@endif