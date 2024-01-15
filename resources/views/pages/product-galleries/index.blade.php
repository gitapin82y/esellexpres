@extends('layouts.admin')

@section('title','Product Photos')
@section('content')
    <div class="orders">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-6">
                                <div class="box-title"></div>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{route('galleries.create')}}" class="btn btn-primary w-100">Add Photo</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Product Name</td>
                                        <td>Photo</td>
                                        <td>Default</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($galleries as $gallery)
                                        <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $gallery->product->name }}</td>
                                        <td><img src="{{ url($gallery->photo) }}" alt="product-gallery"></td>
                                        {{-- <td>{{ $gallery->is_default ? 'Ya' : 'Tidak' }}</td> --}}
                                        <td>
                                            <form action="{{ route('galleries.destroy', $gallery->id) }}" class="d-inline" method="POST">
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