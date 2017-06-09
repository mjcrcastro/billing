
<div class="form-group @if ($errors->has('description')) has-error @endif">
    {{ Form::label('short_description', 'Short Description:') }}
    {{ Form::text('short_description', null, array('class="form-control"')) }}
    @if ($errors->has('short_description')) 
    <div class="small">
        {{ $errors->first('short_description', ':message') }} 
    </div>
    @endif
    {{ Form::label('description', 'Description:') }}
    {{ Form::text('description', null, array('class="form-control"')) }}
    @if ($errors->has('description')) 
    <div class="small">
        {{ $errors->first('description', ':message') }} 
    </div>
    @endif
    
    {{ Form::label('effect_inv', 'Inventory Effect:') }}
    {{ Form::select('effect_inv', array("0"=>"Sin effecto","1"=>"Suma","-1"=>"Resta"), null, array('class="form-control"')) }}
    @if ($errors->has('effect_inv')) 
    <div class="small">
        {{ $errors->first('effect_inv', ':message') }} 
    </div>
    @endif
    
    {{ Form::label('req_qty', 'Requires Quantity:') }}
    {{ Form::checkbox('req_qty', 1, True, array('class="checkbox"')) }}
    @if ($errors->has('req_qty'))
    <div class="small">
        {{ $errors->first('req_qty', ':message') }} 
    </div>
    @endif
    
    {{ Form::label('req_val', 'Requires Value:') }}
    {{ Form::checkbox('req_val', 1, True, array('class="checkbox"')) }}
    @if ($errors->has('req_val')) 
    <div class="small">
        {{ $errors->first('req_qty', ':message') }} 
    </div>
    @endif
    <p></p>
    
    {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-6')) }}
    {{ link_to_route('transTypes.index', 'Cancel', [],array('class'=>'btn btn-default col-xs-6')) }}
</div>
