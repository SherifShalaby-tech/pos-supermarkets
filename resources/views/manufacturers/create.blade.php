<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('ManufacturerController@store'), 'method' => 'post', 'id' => $quick_add ? 'quick_add_manufacturer_form' : 'manufacturer_add_form', 'files' => true]) !!}

        <div class="modal-header">
            <h4 class="modal-title">@lang( 'lang.add_manufacturer' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __('lang.name') . ':*') !!}
                <div class="input-group my-group">
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                    <span class="input-group-btn">
                        <button class="btn btn-default bg-white btn-flat translation_btn" type="button" data-type="manufacturer"><i
                                class="dripicons-web text-primary fa-lg"></i></button>
                    </span>
                </div>
            </div>
            @include('layouts.partials.translation_inputs', [
                'attribute' => 'name',
                'translations' => [],
                'type' => 'manufacturer',
            ])
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('#cat_product_class_id').selectpicker('render');
    $('#parent_id').selectpicker('render');

    @if ($type == 'manufacturer')
        $('.view_modal').on('shown.bs.modal', function () {
        $("#cat_product_class_id").selectpicker("val", $('#product_class_id').val());
        })
    @endif

</script>
