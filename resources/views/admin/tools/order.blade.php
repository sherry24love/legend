<div class="btn-group" data-toggle="buttons">
@foreach($options as $option => $label)
	<label class="btn btn-default btn-sm {{ \Request::get('tabcate', 'all') == $option ? 'active' : '' }}">
	<input type="radio" class="user-gender" value="{{ $option }}">{{$label}}
	</label>
@endforeach
</div>