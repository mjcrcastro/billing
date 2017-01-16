@extends('master')

@section('products_active')
active
@stop

@section('header')

@section('header')

<style>
    .ui-autocomplete-category {
        font-weight: bold;
        padding: .2em .4em;
        margin: .8em 0 .2em;
        line-height: 1.5;
    }
</style>

<script>
    //to show the autocomplete with categories
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function (ul, items) {
            var that = this,
                    currentCategory = "";
            $.each(items, function (index, item) {
                var li;
                if (item.category !== currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });
</script>

<script>

    $(function () {

        //descriptors will be inserted
        $("#descriptor").catcomplete({
            delay: 200,
            autoFocus: true,
            source: function (request, response) { //declared so I can send more than one parameter
                $.ajax({
                    url: '{{ url('jdescriptors') }}',
                    dataType: "json",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);
                    }

                });
            },
            response: function (event, ui) {
                $("#addAsDescriptor").removeClass('disabled');
                $("#addAsDescriptor").text("Add " + $('#descriptor').val() + " to descriptors");
            },
            select: function (event, ui) { //function to run on select event
                addDescriptorToList(ui.item.descriptor_id,
                        ui.item.descriptorType_id,
                        ui.item.category,
                        ui.item.label);
                return false; //returns false to cancel the event
            }
        });
        $(document).on('click', '#removedescriptor', function () { //removes the <p></p> block 
            $(this).parents('p').remove();
        });

        $(document).on('click', '#addAsDescriptor', function () {
            //Show modal bootstrap
            $('#description').val($('#descriptor').val());
            $('#myModal').modal('show');
            //return
        });

        ///

        $(document).on('click', '#addNewDescriptor', function () {
            //Adds a new descriptor to database
            var newDescription = $('#descriptor').val();
            var descriptorType_id = parseInt($('#descriptorType option:selected').val());
            var category = $('#descriptorType option:selected').text();
            var descriptor = {
                descriptorType_id: descriptorType_id,
                description: newDescription
            };
            $.ajax({
                type: "POST",
                url: "{{ route('descriptors.store') }}",
                data: descriptor,
                dataType: 'json',
                success: function (data) {
                    addDescriptorToList(
                            data.id,
                            parseInt(data.descriptorType_id),
                            category,
                            data.description
                            );
                    $("#addAsDescriptor").text("...");
                }
            });
        });

        $('#formSubmit').submit(function (e) {
            //se traen todos los inputs del formulario
            var values = $("input[id='descriptorArray']")//gets the value of all elements whose id is productarray
                    .map(function () {
                        return parseInt($(this).val());
                    }).get();
            if (!values.length) {
                alert('At least the generic name needs to be provided');
                e.preventDefault(); // Cancel the submit
                return false; // 
            }
            //upto 
        });
    });

    function addDescriptorToList(id, descriptorType_id, category, description) {
        //adds a hidden input with the descriptor's id along with 
        //a hidden input with the descriptorType's id along as well as 
        //a href with the description, and an image link 
        //to be able to remove the descriptor later
        $('#descriptor').val(''); //clear text from the textbox

        var valuesDescriptor = $("input[id='descriptorArray']")//gets the value of all elements whose id is productarray
                .map(function () {
                    return parseInt($(this).val());
                }).get();

        var valuesDescriptorType = $("input[id='descriptorTypeArray']")//gets the value of all elements whose id is productarray
                .map(function () {
                    return parseInt($(this).val());
                }).get();

        if ($.inArray(descriptorType_id, valuesDescriptorType) !== -1) {
            alert('There is already a descriptor of this type');
            return false;
        }

        if (!valuesDescriptor.length || $.inArray(id, valuesDescriptor) === -1) {
            $('<p> ' +
                    '<input type="hidden" name="descriptor_id[]" id="descriptorArray" value=' + id + '>' +
                    '<input type="hidden" name="descriptorType_id[]" id="descriptorTypeArray" value=' + descriptorType_id + '>' +
                    category + ': ' + description + ' ' +
                    '<a href="#" id="removedescriptor">' +
                    '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>' +
                    '</a>' +
                    '</p>').appendTo('#descriptors');
        }
    }

</script>

@stop

@section('main')


<div class ="container-fluid">

    <h1 class="h1" > Create product </h1>

    {{-- Hidden form Bootstrap --}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add new descriptor</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">

                        {{ Form::label('description', 'Description:') }}
                        {{ Form::text('description', null, 
                                    array('class="form-control"',
                                          'disabled',
                                          'id'=>'description')) }}

                        {{ Form::label('DescriptorType', 'Descriptor Type:') }}
                        {{ Form::select('descriptorType_id', $descriptorTypes, 
                                    null, array('class="form-control"','id'=>'descriptorType')) }}
                        <p></p>
                        {{ Html::link('#','Add descriptor',
                            array('class'=>'btn btn-primary',
                                  "data-toggle='modal' data-target='#myModal'",
                                  'id'=>'addNewDescriptor')) }} 
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Hidden form from Bootstrap --}}

    {{ Form::open(array('route'=>'products.store','class'=>'horizontal','role'=>'form','id'=>'formSubmit')) }}

    <div class="form-group">

        <div id="descriptors">
            {{-- Placeholder for list of added descriptors --}} 
        </div>

        <p>
        <div class="input-group">
            {{ Form::text('descriptor', '',array('id'=>'descriptor', 'class'=>'ui-widget form-control')) }}
            <span class="input-group-btn">
                {{ html::link('#','...',array('id'=>'addAsDescriptor','class'=>'btn btn-default disabled','type'=>'button')) }} 
            </span>
        </div><!-- /input-group -->
        <p>

        <p>
            {{ Form::submit('Submit', array('class'=>'btn  btn-primary col-xs-6')) }}
            {{ link_to_route('products.index', 'Cancel', [],array('class'=>'btn btn-default col-xs-6')) }}

    </div>
</div>

{{ Form::close() }}



@if ($errors->any())
<ul>
    {{ implode('',$errors->all('<li class="error">:message</li>')) }}
</ul>
@endif

@stop