@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('form_search')
{{ Form::open(array('method'=>'get','role'=>'search','route'=>'locations.index')) }}
@include('form_search_file')
@stop

@section('main')
<div class="container-fluid">
    <h1> Todas las Ubicaciones</h1>

    <p> {{ link_to_route('locations.create', 'Add new location') }} </p>

    @if ($locations->count())
        <table class="table table-striped table-ordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locations as $location)
                <tr>
                    <td> {{ $location->description }}  </td>

                    <td> {{ link_to_route('locations.edit', 'Edit', array($location->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }}  </td>
                    
                    <td>
                        {{ Form::open(array('method'=>'DELETE', 'route'=>array('locations.destroy', $location->id))) }}
                        {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
        {{ $locations->links() }}  {{-- code at the left is for breadcrumbes --}}
    @else
        There are no locations
    @endif
    
@stop {{-- End of section main --}}