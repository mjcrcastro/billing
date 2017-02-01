@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

    <h1> All descriptor types </h1>

    <p> {{ link_to_route('descriptorTypes.create', 'Add new descriptor type') }} </p>

    @if ($descriptor_types->count())
        <table class="table table-striped table-ordered">
            <thead>
                <tr>
                    <th>Descriptor Type Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($descriptor_types as $descriptor_type)
                <tr>
                    <td> {{ $descriptorType->description }}  </td>

                    <td> {{ link_to_route('descriptorTypes.edit', 'Edit', array($descriptor_type->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }}  </td>
                    
                    <td> {{ link_to_route('descriptors.index', 'Descriptors', array('descriptorType_id'=>$descriptorType->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }} </td>
                    
                    <td>
                        {{ Form::open(array('method'=>'DELETE', 'route'=>array('descriptorTypes.destroy', $descriptorType->id))) }}
                        {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $descriptorTypes->links() }}  {{-- code at the left is for breadcrumbes --}}
    @else
        There are no descriptor types
    @endif
    
@stop {{-- End of section main --}}