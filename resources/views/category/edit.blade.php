<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('CategoryController@update', $category->id), 'method' => 'put', 'id' => 'category_add_form', 'files' => true]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.edit_category' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __('lang.name') . ':*') !!}
                <div class="input-group my-group">
                    {!! Form::text('name', $category->name, ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                    <span class="input-group-btn">
                        <button class="btn btn-default bg-white btn-flat translation_btn" type="button"  data-type="category"><i
                                class="dripicons-web text-primary fa-lg"></i></button>
                    </span>
                </div>
            </div>
            @include('layouts.partials.translation_inputs', [
                'attribute' => 'name',
                'translations' => $category->translations,
                'type' => 'category',
            ])
            <div class="form-group">
                {!! Form::label('description', __('lang.description') . ':') !!}
                {!! Form::text('description', $category->description, ['class' => 'form-control', 'placeholder' => __('lang.description')]) !!}
            </div>
            @if ($type=='category')
                <div class="form-group ">
                    {!! Form::label('product_class_id', __('lang.class') . ':') !!}
                    {!! Form::select('product_class_id', $product_classes, $category->product_class_id, ['class' => 'form-control', 'data-live-search' => 'true', 'style' => 'width: 100%', 'placeholder' => __('lang.please_select')]) !!}
                </div>
            @endif
            @if ($type=='sub_category' )
                <div class="form-group ">
                    {!! Form::label('parent_id', __('lang.parent_category') . ':') !!}
                    {!! Form::select('parent_id', $categories, $category->parent_id, ['class' => 'form-control', 'data-live-search' => 'true', 'style' => 'width: 100%', 'placeholder' => __('lang.please_select')]) !!}
                </div>
            @endif
            <div class="col-md-12">
                <div class="form-group">
                    <label for="projectinput2">{{ __('categories.image') }}</label>
                    {{--                                                        <input type="file" id="projectinput2"  class="form-control img" name="image" accept="image/*" />--}}
                    <div class="container mt-3">
                        <div class="row mx-0"
                             style="border: 1px solid #ddd;padding: 30px 0px;">
                            <div class="col-12">
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-10 offset-1">
                                            <div class="variants">
                                                <div class='file file--upload w-100'>
                                                    <label for='file-input-edit'
                                                           class="w-100">
                                                        <i class="fas fa-cloud-upload-alt"></i>Upload
                                                    </label>
                                                    <!-- <input  id="file-input" multiple type='file' /> -->
                                                    <input type="file"
                                                           id="file-input-edit"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-10 offset-1">
                                <div class="preview-edit-container">
                                    @if($category)
                                        <div id="preview{{ $category->id }}" class="preview">
                                            @if (!empty($category->getFirstMediaUrl('category')))
                                                <img src="{{ $category->getFirstMediaUrl('category') }}"
                                                     id="img{{  $category->id }}" alt="">
                                            @else
                                                <img src="{{ asset('/uploads/'.session('logo')) }}" alt=""
                                                     id="img{{  $category->id }}">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="cropped_images"></div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'lang.update' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

        <div class="modal fade" id="imagesModal" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imagesModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="croppie-modal-edit" style="display:none">
                            <div id="croppie-container-edit"></div>
                            <button data-dismiss="modal" id="croppie-cancel-btn-edit" type="button"
                                    class="btn btn-secondary"><i
                                    class="fas fa-times"></i></button>
                            <button id="croppie-submit-btn-edit" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    $("#sub-button-form").click(function (e) {
        e.preventDefault();
        getImages()
        setTimeout(() => {
            $("#category_add_form").submit();
        }, 500)
    });
    const fileInputImages = document.querySelector('#file-input-edit');
    const previewImagesContainer = document.querySelector('.preview-edit-container');
    const croppieModal = document.querySelector('#croppie-modal-edit');
    const croppieContainer = document.querySelector('#croppie-container-edit');
    const croppieCancelBtn = document.querySelector('#croppie-cancel-btn-edit');
    const croppieSubmitBtn = document.querySelector('#croppie-submit-btn-edit');

    fileInputImages.addEventListener('change', () => {
        previewImagesContainer.innerHTML = '';
        let files = Array.from(fileInputImages.files)
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            let fileType = file.type.slice(file.type.indexOf('/') + 1);
            let FileAccept = ["jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "BMP", "bmp"];
            // if (file.type.match('image.*')) {
            if (FileAccept.includes(fileType)) {
                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    const preview = document.createElement('div');
                    preview.classList.add('preview');
                    const img = document.createElement('img');
                    img.src = reader.result;
                    preview.appendChild(img);
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
                                getImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#imagesModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchImagesCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewImagesContainer.appendChild(preview);
                });
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("site.Oops...") }}',
                    text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                })
            }
        }
        getImages()
    });
    function launchImagesCropTool(img) {
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
        const croppie = new Croppie(croppieContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieCancelBtn.addEventListener('click', () => {
            croppieModal.style.display = 'none';
            $('#imagesModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieSubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieModal.style.display = 'none';
                $('#imagesModal').modal('hide');
                croppie.destroy();
            });
        });
    }
    function getImages() {
        $("#cropped_images").empty();
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-edit-container');
            let images = [];
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                $("#cropped_images").append(newInput);
            }
            console.log(images)
            return images
        }, 300);
    }
</script>
