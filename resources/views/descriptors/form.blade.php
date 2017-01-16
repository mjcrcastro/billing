

<div class="form-group @if ($errors->has('description')) has-error @endif">

    {{ Form::label('description', 'Description:') }}
    {{ Form::text('description', null, array('class="form-control"')) }}
    @if ($errors->has('description')) 
    <div class="small">
        {{ $errors->first('description', ':message') }} 
    </div>
    @endif
</div>
<div class="form-group @if ($errors->has('descriptorType_id')) has-error @endif">
    {{ Form::label('DescriptorType', 'Descriptor Type:') }}
    {{ Form::select('descriptorType_id', $descriptorTypes, $descriptorType_id, array('class="form-control"')) }}
    @if ($errors->has('descriptorType_id')) 
    <div class="small">
        {{ $errors->first('descriptorType_id', ':message') }} 
    </div>
    @endif
    <p></p>
    <p></p>
    {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-6')) }}
    {{ link_to_route('descriptors.index', 'Cancel', [],array('class'=>'btn btn-default col-xs-6')) }}
</div>

