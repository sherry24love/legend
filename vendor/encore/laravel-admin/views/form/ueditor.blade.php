<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('admin::form.error')
		<textarea id="{{$class}}" class="{{$class}}" name="{{$name}}" type="text/plain">{!! old($column, $value) !!}</textarea>
        @include('admin::form.help-block')

    </div>
</div>
