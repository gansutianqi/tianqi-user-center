@extends('layouts.app')


@section('content')
	<div class="container">
		<img src="{{ $user->getAvatar() }}" alt="" style="width:100px;">

		<h4 class="page-header">用户头像</h4>

		<div class="image-upload-crop">

			<div class="image-upload-crop__upload-preview">
				<img src="" alt="upload preview image">
			</div>

			<form class="image-upload-crop__form">
				<input type="file" id="image-file" name="image-file">
			</form>

			<a href="#" class="image-upload-crop__crop-button">cropped</a>
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
            var uploadPreview = document.querySelector('.image-upload-crop__upload-preview > img');
            var cropper = null;
            var cropperDetail = null;
            var reader = null;
            var uploadImage = null;
            $('.image-upload-crop__form #image-file').on('change', function () {

                if (this.files.length === 0) {
                    uploadPreview.src = '';
                    return;
                }

                if (cropper) {
                    cropper.destroy();
                }

                console.log(this);
                reader = new FileReader();
                reader.onload = function (e) {
                    uploadPreview.src = e.target.result;
                    uploadImage = e.target.result;
                    cropper = new Cropper(uploadPreview, {
                        aspectRatio: 1 / 1,
                        crop: function (e) {
                            cropDetail = e.detail;
                            console.log(cropDetail);
                        },
                    });

                }
                reader.readAsDataURL(this.files[0]);
            });


            $('.image-upload-crop__crop-button').on('click', function (e) {
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
