<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('BrandController@update', $brand->id), 'method' => 'put', 'id' => 'brand_add_form', 'files' => true ]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.edit' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __( 'lang.name' ) . ':*') !!}
                {!! Form::text('name', $brand->name, ['class' => 'form-control', 'placeholder' => __( 'lang.name' ), 'required' ]);
                !!}
            </div>

            <div class="form-group">
                <label for="projectinput2"> {{ __('categories.image') }}</label>
                <div class="container mt-3">
                    <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                        <div class="col-12">
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-10 offset-1">
                                        <div class="variants">
                                            <div class='file file--upload w-100'>
                                                <label for='file-input-edit-brand' class="w-100">
                                                    <i class="fas fa-cloud-upload-alt"></i>Upload
                                                </label>
                                                <input type="file" id="file-input-edit-brand">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-10 offset-1">
                            <div class="preview-edit-brand-container">
                                @if($brand)
                                    <div id="preview{{ $brand->id }}" class="preview">
                                        @if (!empty($brand->getFirstMediaUrl('brand')))
                                            <img src="{{ $brand->getFirstMediaUrl('brand') }}"
                                                 id="img{{  $brand->id }}" alt="">
                                        @else
                                            <img src="{{ asset('/uploads/'.session('logo')) }}" alt=""
                                                 id="img{{  $brand->id }}">
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="cropped_edit_brand_images"></div>
        <div class="modal-footer">
            <button id="submit-edit-brand-btn" class="btn btn-primary">@lang( 'lang.update' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}
        <div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBrandModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="croppie-modal-brand-edit" style="display:none">
                            <div id="croppie-container-brand-edit"></div>
                            <button data-dismiss="modal" id="croppie-cancel-btn-brand-edit" type="button"
                                    class="btn btn-secondary"><i
                                    class="fas fa-times"></i></button>
                            <button id="croppie-submit-btn-brand-edit" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('#brand_category_id').selectpicker('render')
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    $("#submit-edit-brand-btn").on("click",function (e){
        e.preventDefault();
        getEditBrandImages();
        setTimeout(()=>{
            $("#brand_add_form").submit();
        },500)
    });

    const fileEditBrandInput = document.querySelector('#file-input-edit-brand');
    const previewEditBrandContainer = document.querySelector('.preview-edit-brand-container');
    const croppieEditBrandModal = document.querySelector('#croppie-modal-brand-edit');
    const croppieEditBrandContainer = document.querySelector('#croppie-container-brand-edit');
    const croppieEditBrandCancelBtn = document.querySelector('#croppie-cancel-btn-brand-edit');
    const croppieEditBrandSubmitBtn = document.querySelector('#croppie-submit-btn-brand-edit');
    // let currentFiles = [];
    fileEditBrandInput.addEventListener('change', () => {
        previewEditBrandContainer.innerHTML = '';
        let files = Array.from(fileEditBrandInput.files)
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
                                getEditBrandImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#editBrandModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchEditBrandCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewEditBrandContainer.appendChild(preview);
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

        getEditBrandImages()
    });
    function launchEditBrandCropTool(img) {
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
        const croppie = new Croppie(croppieEditBrandContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieEditBrandModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieEditBrandCancelBtn.addEventListener('click', () => {
            croppieEditBrandModal.style.display = 'none';
            $('#editBrandModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieEditBrandSubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieEditBrandModal.style.display = 'none';
                $('#editBrandModal').modal('hide');
                croppie.destroy();
                getEditBrandImages()
            });
        });
    }
    function getEditBrandImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-edit-brand-container');
            let images = [];
            $("#cropped_edit_brand_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                $("#cropped_edit_brand_images").append(newInput);
            }
            return images
        }, 300);
    }

</script>

