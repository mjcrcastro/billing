


<div class="form-group @if ($errors->has('description')) has-error @endif">
    {{ Form::label('description', 'Description:') }}
    {{ Form::text('description', null, array('class="form-control"')) }}
    @if ($errors->has('description')) 
    <div class="small">
        {{ $errors->first('description', ':message') }} 
    </div>
    @endif
    <p></p>
    {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-6')) }}
    {{ link_to_route('storages.index', 'Cancel', [],array('class'=>'btn btn-default col-xs-6')) }}
</div>
