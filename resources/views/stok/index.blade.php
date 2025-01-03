@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Stok Barang</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Data
            </button>
            <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">
                <i class="fa fa-upload"></i> Import Stok
            </button>
            <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Stok</a>        
            <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Stok(PDF)</a>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Filter data -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="filter_kategori" class="col-form-label">Filter Kategori</label>
                    <select name="filter_kategori" class="form-control filter_kategori">
                        <option value="">- Semua Kategori -</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-sm table-striped table-hover" id="table-stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Stok</th>
                        <th>Supplier</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('css') 
@endpush 

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    
    var tableStok;
    $(document).ready(function(){
        tableStok = $('#table-stok').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('stok/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter_barang = $('.filter_barang').val();
                    d.filter_kategori = $('.filter_kategori').val(); // Tambahkan filter kategori
                }
            },
            columns: [
                {
                    data: "DT_RowIndex", 
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "stok_tanggal", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "barang.barang_nama", 
                    orderable: true,
                    searchable: true,
                },
                {
                    data: "stok_jumlah", 
                    orderable: true,
                    searchable: false,
                    render: function(data, type, row){
                        return new Intl.NumberFormat('id-ID').format(data);
                    }
                },
                {
                    data: "supplier.supplier_nama", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "user.nama", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi", 
                    orderable: false,
                    searchable: false
                }
            ]
        });
    
        // Listener untuk filter barang
        $('.filter_barang').change(function() {
            tableStok.draw(); // Refresh tabel saat filter barang berubah
        });
    
        // Listener untuk filter kategori
        $('.filter_kategori').change(function() {
            tableStok.draw(); // Refresh tabel saat filter kategori berubah
        });
    
        $('#stok_id').on('change', function () {
            tableStok.ajax.reload(); // Reload data jika stok_id berubah
        });
    });
    </script>    
@endpush
