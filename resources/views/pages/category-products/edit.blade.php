@extends('layouts.admin')
@section('title','Edit Category Product')
@section('content')
<div class="orders">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="box-title">Edit Category Product</div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="page-header float-right">
                                <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="{{route('category.index')}}">List Category</a></li>
                                    <li class="active">Edit Category</li>
                                </ol>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>

            <div class="card-body card-block">
                <form action="{{ route('category.update',$category->id) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-control-label">Category Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') ? old('name') : $category->name }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="text-muted text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" onclick="this.disabled=true;this.form.submit();">Edit Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
