@extends('layouts.admin')

@section('title','List Category Product')
@section('content')
    <div class="orders">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-6">
                                <div class="box-title">List Category Product</div>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{route('category.create')}}" class="btn btn-primary w-100">Add Category</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Name</td>
                                        <td>Total Product</td>
                                        <td>Date Created</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->products->count() }}</td>
                                        <td>{{ \Carbon\Carbon::parse($category->created_at)->format('d F Y') }}</td>
                                        <td>
                                            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                            <form action="{{ route('category.destroy', $category->id) }}" class="d-inline" method="POST">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                        @empty
                                            <td>
                                                <td colspan="5" class="text-center"><strong>The data you are looking for does not exist</strong></td>
                                            </td>
                                        </tr>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
