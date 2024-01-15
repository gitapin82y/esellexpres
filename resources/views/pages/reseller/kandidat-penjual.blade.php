@extends('layouts.admin')
 
@section('title', 'Kandidat Penjual')
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
                            <h4>Seller Candidates</h4>
                        </div>
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableKandidatPenjual" class="table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Card</th>
                                        <th>Name Store</th>
                                        <th>Logo Store</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- data table ajax--}}
                                </tbody>
                            </table>
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
        $('#tableKandidatPenjual').DataTable({
            processing: true,
            serverside: true,
            fixedColumns: {
                right: 1,
                left: 0,
            },
            scrollX: true,
            "order": [
                [0, "asc"]
            ],
            ajax: {
                url: '{{route("getKandidatPenjual")}}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'card',
                    name: 'card'
                },{
                    data: 'stores.name',
                    name: 'stores.name'
                },
                {
                    data: 'stores.logo',
                    name: 'stores.logo',
                    render: function(data) {
                        return '<a href="'+data+'" data-lightbox="roadtrip"><img src="'+data+'" width="90px"></a>'
                    }

                },{
                    data: 'address',
                    name: 'address',
                },{
                    data: 'action',
                    name: 'action',
                },
            ],

        });
        // end data table ajax

        // aksi ajax jika tombol edit di klik
        $('body').on('click', '.accKandidat', function () {
            var id = $(this).data('id');
            $.get("{{ route('kandidat-penjual.index') }}" + '/' + id + '/acc', function (data) {
                const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});

Toast.fire({
    icon: 'success',
    title: 'Successful in acc'
});
$('#tableKandidatPenjual').DataTable().ajax.reload();

            });
        });

        $('body').on('click', '.tolakKandidat', function () {
            var id = $(this).data('id');
            $.get("{{ route('kandidat-penjual.index') }}" + '/' + id + '/tolak', function (data) {
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});

Toast.fire({
    icon: 'success',
    title: 'Successfully rejected'
});

$('#tableKandidatPenjual').DataTable().ajax.reload();

            });
        });
        // end aksi ajax jika tombol edit di klik
    });

</script>

{{-- @include('sweetalert::alert') --}}
@endpush
{{-- end script khusus pada pages daftar anggota --}}
