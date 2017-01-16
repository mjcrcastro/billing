@extends('master')

{{-- The next section only serves to 
    let know master blade that the shops 
    menu option needs to be highligted--}}
@section('config_active')
    active
@stop

@section('main')

    <h1> All descriptor types </h1>

    <p> {{ link_to_route('productTypes.create', 'Add new product type') }} </p>

    @if ($productTypes->count())
        <table class="table table-striped table-ordered">
            <thead>
                <tr>
                    <th>Product Type Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productTypes as $productType)
                <tr>
                    <td> {{ $productType->description }}  </td>

                    <td> {{ link_to_route('productTypes.edit', 'Edit', array($productType->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }}  </td>
                    
                    <td> {{ link_to_route('products.index', 'Products', array('productType_id'=>$productType->id), array('class'=>'btn btn-info '.Config::get('global/default.button_size'))) }} </td>
                    
                    <td>
                        {{ Form::open(array('method'=>'DELETE', 'route'=>array('productTypes.destroy', $productType->id))) }}
                        {{ Form::submit('Delete', array('class'=>'btn btn-danger '.Config::get('global/default.button_size'), 'onclick'=>"if(!confirm('Are you sure to delete this item?')){return false;};")) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $productTypes->links() }}  {{-- code at the left is for breadcrumbes --}}
    @else
        There are no descriptor types
    @endif
    
@stop {{-- End of section main --}}