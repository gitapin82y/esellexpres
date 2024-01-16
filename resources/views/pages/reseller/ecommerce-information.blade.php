@extends('layouts.admin')
 
@section('title', 'E-commerce Information')
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
                            <h4>E-commerce Information</h4>
                        </div>
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">

                        <div class="row col-12">
                            <div class="col-12">
                                <form action="{{route('ecommerce.add')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        @if (! empty($ecommerce))
                                        <img src="{{$ecommerce->logo}}" width="150px" alt="">
                                        @else
                                        <img src="{{asset('images/logo.png')}}" width="150px" alt="">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Shop Name</label><br>
                                        <input type="text" id="name" class="form-control" value="{{optional($ecommerce)->name ? $ecommerce->name : ''}}" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="logo" class="form-control-label">Logo</label>
                                        <input type="file" id="logo" name="logo"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Save</button>
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
