@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

    <h1> All storages</h1>

    <p> {{ link_to_route('storages.create', 'Add new storage') }} </p>

    @if ($storages->count())
        <table class="table table-striped table-ordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($storages as $storage)
                <tr>
                    <td> {{ $storage->description }}  </td>

                    <td> {{ link_to_route('storages.edit', 'Edit', array($storage->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }}  </td>
                    
                    <td>
                        {{ Form::open(array('method'=>'DELETE', 'route'=>array('storages.destroy', $storage->id))) }}
                        {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $storages->links() }}  {{-- code at the left is for breadcrumbes --}}
    @else
        There are no storages
    @endif
    
@stop {{-- End of section main --}}