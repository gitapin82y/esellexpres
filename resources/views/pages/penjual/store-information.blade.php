@extends('layouts.admin')
 
@section('title', 'Store Information')
 @push('after-style')
 
 <style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }

    .dtfc-fixed-right{
        background-color: rgba(255, 255, 255, 0.768);
    }
</style>    

 @endpush
@section('content')

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-4 mb-4">
                        <div class="col-md-6 text-left col-12">
                            <h4>Store Information</h4>
                        </div>
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="row col-12">
                        <div class="col-12 col-lg-6">
                            @if (! empty($stores))
                            <img src="{{$stores->logo}}" width="150px" class="mb-2">
                            <h4>Visit Store <br> <a href="{{Request::root().'/'.$stores->slug}}" class="text-primary">{{Request::root().'/'.$stores->slug}}</a></h4>
                            @endif
                            <hr>
                            <h4>do you want to deactivate the store or activate the store?<br>please email <strong>cs@esellexpress.com</strong></h4> 
                            {{-- @if ($stores->status == "OFF")
                            <h4>You can reactivate the store</h4> 
                                <a href="javascript::void(0)" data-id="{{$stores->id}}" data-status="ON" class="statusStore btn btn-success mt-2">Request ON</a>
                            @else
                            <h4>You can deactivate the store</h4> 
                            <a href="javascript::void(0)" data-id="{{$stores->id}}" data-status="OFF" class="statusStore btn btn-danger mt-2">Request OFF</a>
                            @endif --}}
                        </div>
                        <div class="col-12 col-lg-6">
                            <form action="{{route('stores.add')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Shop Name</label><br>
                                    <input type="text" id="name" class="form-control" value="{{$stores->name}}" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="logo" class="form-control-label">Shop Logo</label>
                                    <input type="file" id="logo" name="logo"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-main btn-block mt-3">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('after-script')
    <script>
         jQuery(document).ready(function ($) {
        $('body').on('click', '.statusStore', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');

            Swal.fire({
            title: 'Are you sure you want to make a request?',
            text: 'This request requires an acc from the admin, if the admin acc you will get an email notification',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f78104',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, apply now!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.get('stores/request?is_active='+status+'&store_id='+id, function (data) {

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Request sent successfully'
                        });
                });
            }
        });
           
    });
});
    </script>
@endpush
