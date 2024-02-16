@extends('layouts.admin')
@section('title','Add Product Photos')
@push('after-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<!-- Cropper CSS -->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.min.css'>
@endpush
@section('content')
<div class="orders">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="box-title">Add Product Photos</div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="page-header float-right">
                                <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="{{route('products.index')}}">Product List</a></li>
                                    <li class="active">Add Photo</li>
                                </ol>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>

            <div class="card-body card-block">
                <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="products_id" value="{{$product->id}}">
                    <div class="form-group">
                        <label for="photo" class="form-control-label">Product Photo<span class="text-danger">*</span></label>
                        <input type="file" name="photo" id="photo" accept="image/*" value="{{ old('photo') }}"
                            class="form-control  @error('photo') is-invalid @enderror" required>
                        @error('photo')
                        <div class="text-muted text-danger">{{ $message }}</div>
                        @enderror

                        @error('photo_input')
                        @if (!$errors->has('photo')) <!-- Add this condition -->
                        <div class="text-danger">{{ $message }}</div>
                        @endif
                    
                        @enderror
                    </div>

                    <div class="form-group">
                        <img id="photo_preview" src="#" alt="Photo Preview" style="width: 100px;width: 100px; display: none;">
                        <input type="hidden" name="photo_input" id="photo_input">
                    </div>

                    {{-- <div class="form-group">
                        <label for="price" class="form-control-label">Default</label><br>
                        <input type="radio" id="true" name="is_default" value="1" checked>
                        <label for="true">Yes</label>&nbsp;
                        <input type="radio" id="false" name="is_default" value="0">
                        <label for="false">No</label><br>
                        @error('is_default')
                        <div class="text-muted text-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Add Product Photos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Tambahkan modal untuk pratinjau dan crop gambar -->
    <div class="modal fade modal-lg" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="" id="image" style="max-width: 100%; width: auto; height: auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('after-script')
<script src='https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.1/cropper.min.js'></script>

<script>
    jQuery(document).ready(function ($) {
    // Cropper.js initialization
    var cropper;

    // Ketika pengguna memilih gambar
    $('#photo').change(function(e) {
    var file = e.target.files[0];
    var reader = new FileReader();

    reader.onload = function(event) {
        $('#cropModal').modal('show');
        var imageUrl = event.target.result;
        // Tambahkan timestamp ke URL gambar

        $('#image').attr('src', imageUrl);

        // Inisialisasi Cropper
        cropper = new Cropper(document.getElementById('image'), {
            aspectRatio: 1 / 1, // Rasio aspek 1:1
            viewMode: 1,
        });
    }

    reader.readAsDataURL(file);
});

    $('#crop').click(function() {
if (cropper) {
    var canvas = cropper.getCroppedCanvas({
        width: 600, // Lebar gambar hasil crop
        height: 600, // Tinggi gambar hasil crop
    });

    if (canvas) {
        canvas.toBlob(function(blob) {
            $('#photo_preview').attr('src', url).show();
            if (blob) {
                var url = URL.createObjectURL(blob);
                var reader = new FileReader();

                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $('#photo_preview').attr('src', base64data);
                    $('#photo_input').val(base64data); // Set nilai input tersembunyi dengan gambar yang dipangkas
                    
                    $('#cropModal').modal('hide');
                };
            } else {
                console.error('Canvas is null or undefined.');
            }
        });
    } else {
        console.error('Failed to get cropped canvas.');
    }
} else {
    console.error('Cropper is not initialized.');
}
});

// Reset Cropper saat gambar berubah
$('#photo').on('change', function() {
    if (cropper) {
        cropper.destroy(); // Hancurkan objek Cropper
        cropper = null; // Atur cropper menjadi null
    }
});

});
</script>
@endpush
