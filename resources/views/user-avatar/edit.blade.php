@extends('layouts.app')


@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h4 class="page-header">用户头像</h4>

				<div class="alert alert-warning">
					先点击“+”符号，上传并裁剪你的头像，然后点击确认按钮提交的头像。
				</div>

				<div class="image-upload-crop">

					<a href="javascript:;" class="image-upload-crop__upload-file" style="background-image: url('{{ Auth::user()->getAvatar() }}')">
						<span class="glyphicon glyphicon-plus"></span>
						<input type="file" id="image-file" name="image-file">
					</a>
					<div class="image-upload-crop__upload-preview"></div>
					<div>
						<a href="#" class="btn btn-default image-upload-crop__crop-button">确定</a>
					</div>

				</div>
			</div>
		</div>


	</div>
@stop


@push('css')
	<link href="https://cdn.bootcss.com/cropperjs/1.1.3/cropper.min.css" rel="stylesheet">
@endpush

@push('scripts')
	<script src="https://cdn.bootcss.com/cropperjs/1.1.3/cropper.min.js"></script>
	<script>

        $(function () {

            var previewDiv = $('.image-upload-crop__upload-preview'),
                submitButton = $('.image-upload-crop__crop-button'),
                cropper = null,
                cropperDetail = null,
                reader = null,
                uploadImage = null,
                allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];


            $('.image-upload-crop__upload-file input').on('change', function () {

                if (this.files.length === 0) {
                    return;
                }

                if (allowedMimeTypes.indexOf(this.files[0].type) === -1) {
                    alert('不允许的文件类型');
                    return;
                }

                if (cropper) {
                    cropper.destroy();
                }

                reader = new FileReader();

                reader.onload = function (e) {

                    var img = $('<img />', {
                        src: e.target.result,
                    });

                    previewDiv.empty();

                    img.appendTo(previewDiv);

                    uploadImage = e.target.result;

                    cropper = new Cropper(document.querySelector('.image-upload-crop__upload-preview > img'), {
                        aspectRatio: 1 / 1,
                        crop: function (e) {
                            cropDetail = e.detail;
                            console.log(cropDetail);
                        },
                    });
                }
                reader.readAsDataURL(this.files[0]);
            });


            submitButton.on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                if (!cropperDetail && !uploadImage) {
                    alert('请选择上传文件');
                    return;
                }

                elem.attr('disabled', true)
                    .html('<i class="fa fa-spinner fa-pulse"></i>');

                axios.post('/users/avatar', {
                    cropDetail: cropDetail,
                    uploadImage: uploadImage,
                }).then(function (response) {
                    console.log(response);
                    window.location.reload();
                }).catch(function (err) {
                    console.log(err);
                });

            });
        });
	</script>
@endpush
