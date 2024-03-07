@extends('layouts.admin')
@section('title','Edit Profile')
@section('content')
<div class="orders">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header"><strong>Information Profile</strong></div>

            <div class="card-body card-block">
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
                        <label for="born" class="form-control-label">Born<span class="text-danger">*</span></label>
                        <input type="date" id="born" onfocus="this.showPicker()"  name="born" value="{{ old('born') ? old('born') : $users->born }}"
                               class="form-control @error('born') is-invalid @enderror">
                        @error('born')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="gender" class="form-control-label">Gender<span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="Man" {{ $users->gender === 'Man' ? 'selected' : '' }}>Man</option>
                            <option value="Woman" {{ $users->gender === 'Woman' ? 'selected' : '' }}>Woman</option>
                        </select>
                        @error('gender')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="country" class="form-control-label">Country<span class="text-danger">*</span></label>
                        <input type="text" id="country" name="country" value="{{ old('country') ? old('country') : $users->country }}"
                               class="form-control @error('country') is-invalid @enderror">
                        @error('country')
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
                        <button type="submit" class="btn btn-main w-100" onclick="this.disabled=true;this.form.submit();">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
