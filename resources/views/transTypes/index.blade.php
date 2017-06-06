@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('form_search')
{{ Form::open(array('method'=>'get','role'=>'search','route'=>'transTypes.index')) }}
@include('form_search_file')
@stop

@section('main')
<div class="container-fluid">
    <h1> All Transaction Types</h1>

    <p> {{ link_to_route('transTypes.create', 'Add new transaction type') }} </p>

    @if ($transTypes->count())
        <table class="table table-striped table-ordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transTypes as $transType)
                <tr>
                    <td> {{ $transType->description }}  </td>

                    <td> {{ link_to_route('transTypes.edit', 'Edit', array($transType->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }}  </td>
                    
                    <td>
                        {{ Form::open(array('method'=>'DELETE', 'route'=>array('transTypes.destroy', $transType->id))) }}
                        {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
        {{ $transTypes->links() }}  {{-- code at the left is for breadcrumbes --}}
    @else
        There are no transaction types
    @endif
    
@stop {{-- End of section main --}}