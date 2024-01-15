@extends('layouts.admin')

@section('title','Other photos')
@section('content')
    <div class="orders">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-6">
                                <div class="box-title">Other photo product <b>{{$products->name}}</b></div>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="{{route('galleries.edit', $products->id)}}" class="btn btn-primary w-100">Add Photo</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Photo</td>
                                        {{-- <td>Default</td> --}}
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($galleries as $gallery)
                                        <tr>
                                        <td>{{ $gallery->product->id }}</td>
                                        <td>
                                            <a href="{{ url($gallery->photo) }}" data-lightbox="roadtrip">
                                                <img src="{{ url($gallery->photo) }}" alt="product-gallery"></td>
                                            </a>
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