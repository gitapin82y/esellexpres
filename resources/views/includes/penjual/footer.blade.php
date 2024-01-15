<div class="copyright-reserved footer-style py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                @php
                use App\Models\Store;
                $store = Store::with('users')->where('slug',request()->segment(1))->first();
                @endphp
                <div class="d-flex justify-content-center justify-content-md-start mb-2 mb-md-0" style="color: #b2b2b2;">
                    <div class="mail-service mr-3">
                        <i class=" fa fa-envelope"></i> {{$store->users->email}}
                    </div>
                    <div class="phone-service">
                        <i class=" fa fa-phone"></i> {{$store->users->phone}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="copyright-text float-center float-md-right">
                    Copyright &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    All rights reserved | Esselexpres
                </div>
            </div>
        </div>
    </div>
</div>