@extends('layouts.admin')
@section('title','Add Product Photos')
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
                        <label for="photo" class="form-control-label">Product Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/*" value="{{ old('photo') }}"
                            class="form-control" required>
                        {{-- @error('photo')
                        <div class="text-muted text-danger">{{ $message }}</div>
                        @enderror --}}
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

@endsection
