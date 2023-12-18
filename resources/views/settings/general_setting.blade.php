@extends('layouts.app')
@section('title', __('lang.general_settings'))
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
    <style>
        .preview-logo-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-header-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-footer-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview {
            position: relative;
            width: 150px;
            height: 150px;
            padding: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin: 30px 0px;
            border: 1px solid #ddd;
        }
        .preview img {
            width: 100%;
            height: 100%;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            object-fit: cover;

        }

        .delete-btn {
            position: absolute;
            top: 156px;
            right: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: transparent;
            color: rgba(235, 32, 38, 0.97);
        }

        .crop-btn {
            position: absolute;
            top: 156px;
            left: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
            background: transparent;
            color: #007bff;
        }
    </style>
    <style>
        .variants {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .variants>div {
            margin-right: 5px;
        }

        .variants>div:last-of-type {
            margin-right: 0;
        }

        .file {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>input[type='file'] {
            display: none
        }

        .file>label {
            font-size: 1rem;
            font-weight: 300;
            cursor: pointer;
            outline: 0;
            user-select: none;
            border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
            border-style: solid;
            border-radius: 4px;
            border-width: 1px;
            background-color: hsl(0, 0%, 100%);
            color: hsl(0, 0%, 29%);
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>label:hover {
            border-color: hsl(0, 0%, 21%);
        }

        .file>label:active {
            background-color: hsl(0, 0%, 96%);
        }

        .file>label>i {
            padding-right: 5px;
        }

        .file--upload>label {
            color: hsl(204, 86%, 53%);
            border-color: hsl(204, 86%, 53%);
        }

        .file--upload>label:hover {
            border-color: hsl(204, 86%, 53%);
            background-color: hsl(204, 86%, 96%);
        }

        .file--upload>label:active {
            background-color: hsl(204, 86%, 91%);
        }

        .file--uploading>label {
            color: hsl(48, 100%, 67%);
            border-color: hsl(48, 100%, 67%);
        }

        .file--uploading>label>i {
            animation: pulse 5s infinite;
        }

        .file--uploading>label:hover {
            border-color: hsl(48, 100%, 67%);
            background-color: hsl(48, 100%, 96%);
        }

        .file--uploading>label:active {
            background-color: hsl(48, 100%, 91%);
        }

        .file--success>label {
            color: hsl(141, 71%, 48%);
            border-color: hsl(141, 71%, 48%);
        }

        .file--success>label:hover {
            border-color: hsl(141, 71%, 48%);
            background-color: hsl(141, 71%, 96%);
        }

        .file--success>label:active {
            background-color: hsl(141, 71%, 91%);
        }

        .file--danger>label {
            color: hsl(348, 100%, 61%);
            border-color: hsl(348, 100%, 61%);
        }

        .file--danger>label:hover {
            border-color: hsl(348, 100%, 61%);
            background-color: hsl(348, 100%, 96%);
        }

        .file--danger>label:active {
            background-color: hsl(348, 100%, 91%);
        }

        .file--disabled {
            cursor: not-allowed;
        }

        .file--disabled>label {
            border-color: #e6e7ef;
            color: #e6e7ef;
            pointer-events: none;
        }


        @keyframes pulse {
            0% {
                color: hsl(48, 100%, 67%);
            }

            50% {
                color: hsl(48, 100%, 38%);
            }

            100% {
                color: hsl(48, 100%, 67%);
            }
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12  no-print">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4>@lang('lang.general_settings')</h4>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => action('SettingController@updateGeneralSetting'), 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('site_title', __('lang.site_title'), []) !!}
                        {!! Form::text('site_title', !empty($settings['site_title']) ? $settings['site_title'] : null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3 hide">
                        {!! Form::label('developed_by', __('lang.developed_by'), []) !!}
                        {!! Form::text('developed_by', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('time_format', __('lang.time_format'), []) !!}
                        {!! Form::select('time_format', ['12' => '12 hours', '24' => '24 hours'], !empty($settings['time_format']) ? $settings['time_format'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('timezone', __('lang.timezone'), []) !!}
                        {!! Form::select('timezone', $timezone_list, !empty($settings['timezone']) ? $settings['timezone'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('language', __('lang.language'), []) !!}
                        {!! Form::select('language', $languages, !empty($settings['language']) ? $settings['language'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('currency', __('lang.currency'), []) !!}
                        {!! Form::select('currency', $currencies, !empty($settings['currency']) ? $settings['currency'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('invoice_lang', __('lang.invoice_lang'), []) !!}
                        {!! Form::select('invoice_lang', $languages + ['ar_and_en' => 'Arabic and English'], !empty($settings['invoice_lang']) ? $settings['invoice_lang'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('invoice_terms_and_conditions', __('lang.tac_to_be_printed'), []) !!}
                        {!! Form::select('invoice_terms_and_conditions', $terms_and_conditions, !empty($settings['invoice_terms_and_conditions']) ? $settings['invoice_terms_and_conditions'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => __('lang.please_select')]) !!}
                    </div>
                    @if (session('system_mode') != 'restaurant')
                        <div class="col-md-3">
                            {!! Form::label('default_purchase_price_percentage', __('lang.default_purchase_price_percentage'), []) !!} <i class="dripicons-question" data-toggle="tooltip"
                                title="@lang('lang.default_purchase_price_percentage_info')"></i>
                            {!! Form::number('default_purchase_price_percentage', !empty($settings['default_purchase_price_percentage']) ? $settings['default_purchase_price_percentage'] : null, ['class' => 'form-control']) !!}
                        </div>
                    @else
                        <div class="col-md-3">
                            {!! Form::label('default_profit_percentage', __('lang.default_profit_percentage'), []) !!} <small>@lang('lang.without_%_symbol')</small>
                            {!! Form::number('default_profit_percentage', !empty($settings['default_profit_percentage']) ? $settings['default_profit_percentage'] : null, ['class' => 'form-control']) !!}
                        </div>
                    @endif
                    <div class="col-md-3">
                        {!! Form::label('Watsapp Numbers', __('lang.watsapp_numbers')) !!}
                        {!! Form::text('watsapp_numbers', !empty($settings['watsapp_numbers']) ? $settings['watsapp_numbers'] : null, ['class' => 'form-control',Auth::user()->is_superadmin == 1?'':'disabled']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('font_size_at_invoice', __('lang.font_size_at_invoice'), []) !!}
                        {!! Form::select('font_size_at_invoice', $fonts, !empty($settings['font_size_at_invoice']) ? $settings['font_size_at_invoice'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        <div class="i-checks">
                            <input id="show_the_window_printing_prompt" name="show_the_window_printing_prompt"
                                type="checkbox" @if (!empty($settings['show_the_window_printing_prompt']) && $settings['show_the_window_printing_prompt'] == '1') checked @endif value="1"
                                class="form-control-custom">
                            <label for="show_the_window_printing_prompt"><strong>
                                    @lang('lang.show_the_window_printing_prompt')
                                </strong></label>
                        </div>
                    </div>
                    @if (session('system_mode') == 'restaurant')
                        <div class="col-md-3">
                            <div class="i-checks">
                                <input id="enable_the_table_reservation" name="enable_the_table_reservation" type="checkbox"
                                    @if (!empty($settings['enable_the_table_reservation']) && $settings['enable_the_table_reservation'] == '1') checked @endif value="1" class="form-control-custom">
                                <label for="enable_the_table_reservation"><strong>
                                        @lang('lang.enable_the_table_reservation')
                                    </strong></label>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-3">
                        {!! Form::label('numbers_length_after_dot', __('lang.numbers_length_after_dot'), []) !!}
                        {!! Form::select('numbers_length_after_dot', ['1' => '0.0', '2' => '0.00', '3' => '0.000','4' => '0.0000', '5' => '0.00000', '6' => '0.000000', '7' => '0.0000000'],  !empty($settings['numbers_length_after_dot']) ? $settings['numbers_length_after_dot'] : null, ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                </div>
                <br>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="projectinput2"> {{  __('lang.letter_header') }}</label>
                            <div class="container mt-3">
                                <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                                    <div class="col-12">
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-10 offset-1">
                                                    <div class="variants">
                                                        <div class='file file--upload w-100'>
                                                            <label for='file-input-header' class="w-100">
                                                                <i class="fas fa-cloud-upload-alt"></i>Upload
                                                            </label>
                                                            <!-- <input  id="file-input" multiple type='file' /> -->
                                                            <input type="file" id="file-input-header">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <div class="preview-header-container">
                                            @if (!empty($settings['letter_header']))
                                                <div class="preview">
                                                    <img src="{{ asset("uploads/".  $settings['letter_header']) }}"
                                                         id="img_header_footer" alt="">
                                                    <button class="btn btn-xs btn-danger delete-btn remove_image" data-type="letter_header"><i style="font-size: 25px;"
                                                                                                                                               class="fa fa-trash"></i></button>

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="projectinput2"> {{ __('lang.letter_footer') }}</label>
                            <div class="container mt-3">
                                <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                                    <div class="col-12">
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-10 offset-1">
                                                    <div class="variants">
                                                        <div class='file file--upload w-100'>
                                                            <label for='file-input-footer' class="w-100">
                                                                <i class="fas fa-cloud-upload-alt"></i>Upload
                                                            </label>
                                                            <input type="file" id="file-input-footer">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <div class="preview-footer-container">
                                            @if (!empty($settings['letter_footer']))
                                                <div class="preview">
                                                    <img src="{{ asset("uploads/".  $settings['letter_footer']) }}"
                                                         id="img_letter_footer" alt="">
                                                    <button class="btn btn-xs btn-danger delete-btn remove_image" data-type="letter_footer"><i style="font-size: 25px;"
                                                                                                                                               class="fa fa-trash"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="projectinput2"> {{ __('lang.logo') }}</label>
                            <div class="container mt-3">
                                <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                                    <div class="col-12">
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-10 offset-1">
                                                    <div class="variants">
                                                        <div class='file file--upload w-100'>
                                                            <label for='file-input-logo' class="w-100">
                                                                <i class="fas fa-cloud-upload-alt"></i>Upload
                                                            </label>
                                                            <!-- <input  id="file-input" multiple type='file' /> -->
                                                            <input type="file" id="file-input-logo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <div class="preview-logo-container">
                                            @if (!empty($settings['logo']))
                                                <div class="preview">
                                                    <img src="{{ asset("uploads/".  $settings['logo']) }}"
                                                         id="img_logo_footer" alt="">
                                                    <button class="btn btn-xs btn-danger delete-btn remove_image" data-type="logo"><i style="font-size: 25px;"
                                                                                                                                      class="fa fa-trash"></i></button>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('help_page_content', __('lang.help_page_content'), []) !!}
                            {!! Form::textarea('help_page_content', !empty($settings['help_page_content']) ? $settings['help_page_content'] : null, ['class' => 'form-control', 'id' => 'help_page_content']) !!}
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div id="cropped_logo_images"></div>
                <div id="cropped_header_images"></div>
                <div id="cropped_footer_images"></div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="croppie-logo-modal" style="display:none">
                        <div id="croppie-logo-container"></div>
                        <button data-dismiss="modal" id="croppie-logo-cancel-btn" type="button" class="btn btn-secondary"><i
                                class="fas fa-times"></i></button>
                        <button id="croppie-logo-submit-btn" type="button" class="btn btn-primary"><i
                                class="fas fa-crop"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="headerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="croppie-header-modal" style="display:none">
                        <div id="croppie-header-container"></div>
                        <button data-dismiss="modal" id="croppie-header-cancel-btn" type="button" class="btn btn-secondary"><i
                                class="fas fa-times"></i></button>
                        <button id="croppie-header-submit-btn" type="button" class="btn btn-primary"><i
                                class="fas fa-crop"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="footerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="croppie-footer-modal" style="display:none">
                        <div id="croppie-footer-container"></div>
                        <button data-dismiss="modal" id="croppie-footer-cancel-btn" type="button" class="btn btn-secondary"><i
                                class="fas fa-times"></i></button>
                        <button id="croppie-footer-submit-btn" type="button" class="btn btn-primary"><i
                                class="fas fa-crop"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $('.selectpicker').selectpicker();
        $(document).ready(function() {
            tinymce.init({
                selector: "#help_page_content",
                height: 130,
                plugins: [
                    "advlist autolink lists link charmap print preview anchor textcolor image",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime table contextmenu paste code wordcount",
                ],
                toolbar: "insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat",
                branding: false,
            });
        });
        // $(document).on('click', '.remove_image', function() {
        //     var type = $(this).data('type');
        //     $.ajax({
        //         url: "/settings/remove-image/" + type,
        //         type: "POST",
        //         success: function(response) {
        //             if (response.status == 'success') {
        //                 location.reload();
        //             }
        //         }
        //     });
        // });
        $(document).on('click', '.remove_image', function(e) {
            e.preventDefault();
            var type = $(this).data('type');
            console.log(type)
            Swal.fire({
                title: '{{ __("site.Are you sure?") }}',
                text: "{{ __("site.You won't be able to delete!") }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/settings/remove-image/" + type,
                        type: "POST",
                        success: function(response) {
                            if (response.success) {
                                if (type == "letter_header"){
                                    const previewHeaderContainer = document.querySelector('.preview-header-container');
                                    previewHeaderContainer.innerHTML = '';
                                }else if(type == "letter_footer"){
                                    const previewFooterContainer = document.querySelector('.preview-footer-container');
                                    previewFooterContainer.innerHTML = '';
                                }else if(type == "logo"){
                                    const previewLogoContainer = document.querySelector('.preview-logo-container');
                                    previewLogoContainer.innerHTML = '';
                                }
                                Swal.fire(
                                    'Deleted!',
                                    '{{ __("site.Your Image has been deleted.") }}',
                                    'success'
                                );

                            }
                        }
                    });
                }
            });

        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

    <script>
        $("#submit-btn").on("click",function (e){
            e.preventDefault();
            setTimeout(()=>{
                getHeaderImages();
                getFooterImages();
                getLogoImages();
                $("#setting_form").submit();
            },1000)
        });
    </script>
    <script>
        var fileHeaderInput = document.querySelector('#file-input-header');
        var previewHeaderContainer = document.querySelector('.preview-header-container');
        var croppieHeaderModal = document.querySelector('#croppie-header-modal');
        var croppieHeaderContainer = document.querySelector('#croppie-header-container');
        var croppieHeaderCancelBtn = document.querySelector('#croppie-header-cancel-btn');
        var croppieHeaderSubmitBtn = document.querySelector('#croppie-header-submit-btn');
        // let currentFiles = [];
        fileHeaderInput.addEventListener('change', () => {
            previewHeaderContainer.innerHTML = '';
            let files = Array.from(fileHeaderInput.files)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                let fileType = file.type.slice(file.type.indexOf('/') + 1);
                let FileAccept = ["jpg","JPG","jpeg","JPEG","png","PNG","BMP","bmp"];
                // if (file.type.match('image.*')) {
                if (FileAccept.includes(fileType)) {
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const preview = document.createElement('div');
                        preview.classList.add('preview');
                        const img = document.createElement('img');
                        const actions = document.createElement('div');
                        actions.classList.add('action_div');
                        img.src = reader.result;
                        preview.appendChild(img);
                        preview.appendChild(actions);
                        const container = document.createElement('div');
                        const deleteBtn = document.createElement('span');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                        deleteBtn.addEventListener('click', () => {
                            Swal.fire({
                                title: '{{ __("site.Are you sure?") }}',
                                text: "{{ __("site.You won't be able to delete!") }}",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire(
                                        'Deleted!',
                                        '{{ __("site.Your Image has been deleted.") }}',
                                        'success'
                                    )
                                    files.splice(file, 1)
                                    preview.remove();
                                    getHeaderImages()
                                }
                            });
                        });
                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#headerModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchHeaderCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewHeaderContainer.appendChild(preview);
                    });
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("site.Oops...") }}',
                        text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                    })
                }
            }

            getHeaderImages()
        });
        function launchHeaderCropTool(img) {
            // Set up Croppie options
            const croppieOptions = {
                viewport: {
                    width: 240,
                    height: 120,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 350,
                    height: 350,
                },
                enableOrientation: true
            };

            // Create a new Croppie instance with the selected image and options
            const croppie = new Croppie(croppieHeaderContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieHeaderModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieHeaderCancelBtn.addEventListener('click', () => {
                croppieHeaderModal.style.display = 'none';
                $('#headerModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieHeaderSubmitBtn.addEventListener('click', () => {
                croppie.result({
                    type: 'canvas',
                    size: {
                        width: 800,
                        height: 600
                    },
                    quality: 1 // Set quality to 1 for maximum quality
                }).then((croppedImg) => {
                    img.src = croppedImg;
                    croppieHeaderModal.style.display = 'none';
                    $('#headerModal').modal('hide');
                    croppie.destroy();
                    getHeaderImages()
                });
            });
        }
        function getHeaderImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-header-container');
                let images = [];
                $("#cropped_header_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "letter_header").val(container[0].children[i].children[0].src);
                    $("#cropped_header_images").append(newInput);
                }
                return images
            }, 300);
        }

    </script>
    <script>
        var fileFooterInput = document.querySelector('#file-input-footer');
        var previewFooterContainer = document.querySelector('.preview-footer-container');
        var croppieFooterModal = document.querySelector('#croppie-footer-modal');
        var croppieFooterContainer = document.querySelector('#croppie-footer-container');
        var croppieFooterCancelBtn = document.querySelector('#croppie-footer-cancel-btn');
        var croppieFooterSubmitBtn = document.querySelector('#croppie-footer-submit-btn');
        // let currentFiles = [];
        fileFooterInput.addEventListener('change', () => {
            previewFooterContainer.innerHTML = '';
            let files = Array.from(fileFooterInput.files)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                let fileType = file.type.slice(file.type.indexOf('/') + 1);
                let FileAccept = ["jpg","JPG","jpeg","JPEG","png","PNG","BMP","bmp"];
                // if (file.type.match('image.*')) {
                if (FileAccept.includes(fileType)) {
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const preview = document.createElement('div');
                        preview.classList.add('preview');
                        const img = document.createElement('img');
                        const actions = document.createElement('div');
                        actions.classList.add('action_div');
                        img.src = reader.result;
                        preview.appendChild(img);
                        preview.appendChild(actions);
                        const container = document.createElement('div');
                        const deleteBtn = document.createElement('span');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                        deleteBtn.addEventListener('click', () => {
                            Swal.fire({
                                title: '{{ __("site.Are you sure?") }}',
                                text: "{{ __("site.You won't be able to delete!") }}",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire(
                                        'Deleted!',
                                        '{{ __("site.Your Image has been deleted.") }}',
                                        'success'
                                    )
                                    files.splice(file, 1)
                                    preview.remove();
                                    getFooterImages()
                                }
                            });
                        });
                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#footerModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewFooterContainer.appendChild(preview);
                    });
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("site.Oops...") }}',
                        text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                    })
                }
            }

            getFooterImages()
        });
        function launchCropTool(img) {
            // Set up Croppie options
            const croppieOptions = {
                viewport: {
                    width: 240,
                    height: 120,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 350,
                    height: 350,
                },
                enableOrientation: true
            };

            // Create a new Croppie instance with the selected image and options
            const croppie = new Croppie(croppieFooterContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieFooterModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieFooterCancelBtn.addEventListener('click', () => {
                croppieFooterModal.style.display = 'none';
                $('#footerModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieFooterSubmitBtn.addEventListener('click', () => {
                croppie.result({
                    type: 'canvas',
                    size: {
                        width: 800,
                        height: 600
                    },
                    quality: 1 // Set quality to 1 for maximum quality
                }).then((croppedImg) => {
                    img.src = croppedImg;
                    croppieFooterModal.style.display = 'none';
                    $('#footerModal').modal('hide');
                    croppie.destroy();
                    getFooterImages()
                });
            });
        }
        function getFooterImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-footer-container');
                let images = [];
                $("#cropped_footer_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "letter_footer").val(container[0].children[i].children[0].src);
                    $("#cropped_footer_images").append(newInput);
                }
                return images
            }, 300);
        }

    </script>
    <script>
        var fileLogoInput = document.querySelector('#file-input-logo');
        var previewLogoContainer = document.querySelector('.preview-logo-container');
        var croppieLogoModal = document.querySelector('#croppie-logo-modal');
        var croppieLogoContainer = document.querySelector('#croppie-logo-container');
        var croppieLogoCancelBtn = document.querySelector('#croppie-logo-cancel-btn');
        var croppieLogoSubmitBtn = document.querySelector('#croppie-logo-submit-btn');
        // let currentFiles = [];
        fileLogoInput.addEventListener('change', () => {
            previewLogoContainer.innerHTML = '';
            let files = Array.from(fileLogoInput.files)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                let fileType = file.type.slice(file.type.indexOf('/') + 1);
                let FileAccept = ["jpg","JPG","jpeg","JPEG","png","PNG","BMP","bmp"];
                // if (file.type.match('image.*')) {
                if (FileAccept.includes(fileType)) {
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const preview = document.createElement('div');
                        preview.classList.add('preview');
                        const img = document.createElement('img');
                        const actions = document.createElement('div');
                        actions.classList.add('action_div');
                        img.src = reader.result;
                        preview.appendChild(img);
                        preview.appendChild(actions);
                        const container = document.createElement('div');
                        const deleteBtn = document.createElement('span');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                        deleteBtn.addEventListener('click', () => {
                            Swal.fire({
                                title: '{{ __("site.Are you sure?") }}',
                                text: "{{ __("site.You won't be able to delete!") }}",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire(
                                        'Deleted!',
                                        '{{ __("site.Your Image has been deleted.") }}',
                                        'success'
                                    )
                                    files.splice(file, 1)
                                    preview.remove();
                                    getLogoImages()
                                }
                            });
                        });
                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#logoModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchLogoCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewLogoContainer.appendChild(preview);
                    });
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("site.Oops...") }}',
                        text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                    })
                }
            }

            getLogoImages()
        });
        function launchLogoCropTool(img) {
            // Set up Croppie options
            const croppieOptions = {
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 300,
                    height: 300,
                },
                enableOrientation: true
            };

            // Create a new Croppie instance with the selected image and options
            const croppie = new Croppie(croppieLogoContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieLogoModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieLogoCancelBtn.addEventListener('click', () => {
                croppieLogoModal.style.display = 'none';
                $('#logoModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieLogoSubmitBtn.addEventListener('click', () => {
                croppie.result({
                    type: 'canvas',
                    size: {
                        width: 800,
                        height: 600
                    },
                    quality: 1 // Set quality to 1 for maximum quality
                }).then((croppedImg) => {
                    img.src = croppedImg;
                    croppieLogoModal.style.display = 'none';
                    $('#logoModal').modal('hide');
                    croppie.destroy();
                    getLogoImages()
                });
            });
        }
        function getLogoImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-logo-container');
                let images = [];
                $("#cropped_logo_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "logo").val(container[0].children[i].children[0].src);
                    $("#cropped_logo_images").append(newInput);
                }
                return images
            }, 300);
        }

    </script>
@endsection
