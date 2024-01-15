@extends('layouts.admin')
@section('title','Edit Product')
@section('content')
<div class="orders">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header"><strong>Edit Product {{$product->name}}</strong></div>

            <div class="card-body card-block">
                <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label for="photo" class="form-control-label">Product Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/*" value="{{ old('photo') ? old('photo') : $product->galleries[0]->photo }}"
                            class="form-control @error('photo') is-invalid @enderror">
                        @error('photo')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-control-label">Product Name<span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') ? old('name') : $product->name }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label for="category_id" class="form-control-label">Category <span class="text-danger">*</span></label>

                        <select name="category_id" class="form-control select2 select2-hidden-accessible  @error('category_id') is-invalid @enderror" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option disabled selected>Choose Category</option>
                            @inject('categories', 'App\Models\Category')
                            @foreach ($categories->get() as $category)
                            <option value="{{$category->id}}" @if($category->id == $product->category_id) selected @endif>{{$category->name}}</option>
                            @endforeach
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </select>
					</select> </div>


                    <label for="price" class="form-control-label">Price<span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                          </div>
                        <input type="text" id="price" name="price" placeholder="0.00" oninput="handleInput(this)" value="{{ old('price') ? old('price') : $product->price }}"
                            class="form-control @error('price') is-invalid @enderror">
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <label for="promo_price" class="form-control-label">Discount</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                          </div>
                        <input type="text" id="promo_price" name="promo_price" placeholder="0.00" oninput="handleInput(this)" value="{{ old('promo_price') ? old('promo_price') : $product->promo_price }}"
                            class="form-control @error('promo_price') is-invalid @enderror">
                        @error('promo_price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="quantity" class="form-control-label">Quantity<span class="text-danger">*</span></label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') ? old('quantity') : $product->quantity }}"
                            class="form-control @error('quantity') is-invalid @enderror">
                        @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="total_views" class="form-control-label">Total Views</label>
                        <input type="number" id="total_views" name="total_views" value="{{ old('total_views') ? old('total_views') : $product->total_views }}"
                            class="form-control @error('total_views') is-invalid @enderror">
                        @error('total_views')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="total_sold" class="form-control-label">Total Sold</label>
                        <input type="number" id="total_sold" name="total_sold" value="{{ old('total_sold') ? old('total_sold') : $product->total_sold }}"
                            class="form-control @error('total_sold') is-invalid @enderror">
                        @error('total_sold')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="description" class="form-control-label">Product Description<span class="text-danger">*</span></label>
                        <textarea type="text" id="description" name="description"
                            class="form-control ckeditor @error('description') is-invalid @enderror">{{ old('description') ? old('description') : $product->description }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    @endsection

    @push('after-script')
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
                .create( document.querySelector( '.ckeditor' ) )
                .then( editor => {
                        console.log( editor );
                } )
                .catch( error => {
                        console.error( error );
                } );
</script>
<script>
    function handleInput(inputElement) {
          let value = inputElement.value;
  
          // Menghapus karakter selain angka dan titik
          value = value.replace(/[^\d.]/g, '');
  
          // Mencegah lebih dari satu titik desimal
          let parts = value.split('.');
          if (parts.length > 2) {
              value = parts[0] + '.' + parts.slice(1).join('');
          }
  
          // Memastikan value bukan NaN
          if (!isNaN(value)) {
              // Memberikan format dolar dengan dua digit desimal
              inputElement.value = value;
          } else {
              // Jika input tidak valid, set nilai ke kosong
              inputElement.value = '';
          }
      }
  </script>
    @endpush