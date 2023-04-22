
<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('BrandController@store'), 'method' => 'post', 'id' => $quick_add ?
        'quick_add_brand_form' : 'brand_add_form', 'files' => true ]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.add_brand' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __( 'lang.name' ) . ':*') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __( 'lang.name' ), 'required'
                ]);
                !!}
            </div>
            <input type="hidden" name="quick_add" value="{{$quick_add }}">
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
                                                <label for='file-input-brand' class="w-100">
                                                    <i class="fas fa-cloud-upload-alt"></i>Upload
                                                </label>
                                                <input type="file" id="file-input-brand">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-10 offset-1">
                            <div class="preview-brand-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="cropped_brand_images"></div>
        <div class="modal-footer">
            <button id="submit-create-brand-btn" class="btn btn-primary">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}
        <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="brandModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="croppie-brand-modal" style="display:none">
                            <div id="croppie-brand-container"></div>
                            <button data-dismiss="modal" id="croppie-brand-cancel-btn" type="button" class="btn btn-secondary"><i
                                    class="fas fa-times"></i></button>
                            <button id="croppie-brand-submit-btn" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('#brand_category_id').selectpicker('render');

    $('.view_modal').on('shown.bs.modal', function () {
        let  brand_category_id = $('#sub_category_id').val();
        if(brand_category_id){
            $("#brand_category_id").selectpicker("val", brand_category_id);
        }else{
            let  brand_category_id = $('#category_id').val();
            $("#brand_category_id").selectpicker("val", brand_category_id);
        }
    })
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    $("#submit-create-brand-btn").on("click",function (e){
        e.preventDefault();
        setTimeout(()=>{
            getBrandImages();
            $("#brand_add_form").submit();
            $("#quick_add_brand_form").submit();
        },500)
    });

    var fileBrandInput = document.querySelector('#file-input-brand');
    var previewBrandContainer = document.querySelector('.preview-brand-container');
    var croppieBrandModal = document.querySelector('#croppie-brand-modal');
    var croppieBrandContainer = document.querySelector('#croppie-brand-container');
    var croppieBrandCancelBtn = document.querySelector('#croppie-brand-cancel-btn');
    var croppieBrandSubmitBtn = document.querySelector('#croppie-brand-submit-btn');
    // let currentFiles = [];
    fileBrandInput.addEventListener('change', () => {
        previewBrandContainer.innerHTML = '';
        let files = Array.from(fileBrandInput.files)
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
                                getBrandImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#brandModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchBrandCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewBrandContainer.appendChild(preview);
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

        getBrandImages()
    });
    function launchBrandCropTool(img) {
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
        const croppie = new Croppie(croppieBrandContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieBrandModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieBrandCancelBtn.addEventListener('click', () => {
            croppieBrandModal.style.display = 'none';
            $('#brandModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieBrandSubmitBtn.addEventListener('click', () => {
            croppie.result({
                type: 'canvas',
                size: {
                    width: 800,
                    height: 600
                },
                quality: 1 // Set quality to 1 for maximum quality
            }).then((croppedImg) => {
                img.src = croppedImg;
                croppieBrandModal.style.display = 'none';
                $('#brandModal').modal('hide');
                croppie.destroy();
                getBrandImages()
            });
        });
    }
    function getBrandImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-brand-container');
            let images = [];
            $("#cropped_brand_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                $("#cropped_brand_images").append(newInput);
            }
            return images
        }, 300);
    }

</script>
