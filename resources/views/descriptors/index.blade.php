@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

{{-- this section is to show a form search for descriptors on the master blade--}}
@section('form_search')
{{ Form::open(array('method'=>'get','role'=>'search','route'=>'descriptors.index')) }}
@include('form_search_file')
@stop

@section('main')
<div class="container-fluid">

    <h1> All descriptors {{ $label }} </h1>
    
    <p> {{ link_to_route('descriptors.create', 'Add new descriptor',array('descriptorType_id'=>$descriptor_type_id)) }} </p>
    
    @if ($descriptors->count())
        <table class="table table-striped table-ordered">
            <thead>
                <tr>
                    <th>Descriptor Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($descriptors as $descriptor)
                <tr>
                    <td> {{ $descriptor->description }}  </td>

                    <td> {{ link_to_route('descriptors.edit', 'Edit', array($descriptor->id), array('class'=>'btn btn-info')) }} </td>

                    <td>
                        {{ Form::open(array('method'=>'DELETE', 'route'=>array('descriptors.destroy', $descriptor->id))) }}
                        {{ Form::submit('Delete', array('class'=>'btn btn-danger', 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $descriptors->appends(array('filter'=>$filter))->links() }}  {{-- code at the left is for breadcrumbes --}}
    @else
        There are no descriptors
    @endif
    </div>
@stop {{-- End of section main --}}