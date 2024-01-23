@extends('layouts.penjual')
@section('title', 'Information Profile')
@push('before_style')
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css
" rel="stylesheet">
<style>
/* CSS untuk scrollbar horizontal */
.table-responsive {
    overflow-x: scroll;
}
.table-responsive table{
    width: 100%;
}
/* Optional: Mengatur style scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 12px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: #e7ab3c;
    border-radius: 6px;
}
 td,
 th {
    vertical-align: middle !important;
}

.table-responsive::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.footer-style{
            margin-bottom: -40px;
}
</style>
@endpush
@section('content')
     <!-- Breadcrumb Section Begin -->
     <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="/{{request()->segment(1)}}"><i class="fa fa-home"></i> Home</a>
                        <span>Information Profile</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('updateProfile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
    
                        <input type="hidden" name="id" value="{{$users->id}}"
                        class="form-control">
    
                            {{-- <div class="col-md-3 col-8">
                                <img src="{{asset($users->avatar)}}" class="user-avatar rounded-circle" alt="">
                            </div> --}}
                    
    
                        <div class="form-group">
                            <label for="avatar" class="form-control-label">Photo Profile</label>
                            <input type="file" name="avatar" id="avatar" accept="image/*" value="{{ old('avatar') ? old('avatar') : $users->avatar }}"
                                class="form-control @error('avatar') is-invalid @enderror">
                            @error('avatar')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="form-group">
                            <label for="name" class="form-control-label">Full Name<span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') ? old('name') : $users->name }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
    
                        <div class="form-group">
                            <label for="email" class="form-control-label">Email<span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') ? old('email') : $users->email }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="form-group">
                            <label for="phone" class="form-control-label">Phone<span class="text-danger">*</span></label>
                            <input type="number" id="phone" name="phone" value="{{ old('phone') ? old('phone') : $users->phone }}"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="form-group">
                            <label for="address" class="form-control-label">Address<span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address') ? old('address') : $users->address }}"
                                class="form-control @error('address') is-invalid @enderror">
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
    
                        <div class="form-group">
                            <button type="submit" class="btn btn-main w-100">Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->


    {{-- modal success --}}

@endsection

@push('before_script');
<script>
    jQuery(document).ready(function($) {
  $('#mymodal').on('show.bs.modal',function(e){
      var button = $(e.relatedTarget);
      var modal = $(this);
      modal.find('.modal-body').load(button.data('remote'));
      modal.find('.modal-title').html(button.data('title'));
  });
  });
</script>

<div class="modal" id="mymodal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
    </div>
  </div>
</div>
@endpush