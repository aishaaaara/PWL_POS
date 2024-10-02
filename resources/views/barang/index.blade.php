@extends('layouts.template') 

@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a> 
        </div> 
      </div> 
      <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
          <div class="col-md-12">
             <div class="form-group row">
                <label class="col-1 control-label col-form-label">Filter:</label>
                <div class="col-3">
                   <select class="form-control" id="kategori_id" name="kategori_id" required>
                      <option value="">- Semua -</option>
                      @foreach($kategori as $item)
                         <option value="{{ $item->kategori_id }}">{{ $item->kategori_id }}</option>
                      @endforeach
                   </select>
                   <small class="form-text text-muted">ID Kategori</small>
                </div>
             </div>
          </div>
       </div>
       
        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Kategori ID</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection 

@push('css') 
@endpush 

@push('js')
<script>
  $(document).ready(function() {
    // Hancurkan DataTable jika sudah diinisialisasi sebelumnya
    if ($.fn.DataTable.isDataTable('#table_barang')) {
      $('#table_barang').DataTable().destroy();
    }

    // Inisiasi ulang DataTable
    var dataBarang = $('#table_barang').DataTable({
      serverSide: true,
      processing: true,
      ajax: {
        "url": "{{ url('barang/list') }}", 
        "type": "POST",
        "data": function (d) {
          d.kategori_id = $('#kategori_id').val(); // Kirim kategori_id sebagai parameter
        }
      },
      columns: [
        {
          data: "DT_RowIndex",             
          className: "text-center", 
          orderable: false, 
          searchable: false
        },{ 
          data: "barang_kode",                
          className: "", 
          orderable: true,     
          searchable: true     
        },{ 
          data: "kategori.kategori_id",  
          className: "", 
          orderable: true,     
          searchable: true       
        },{ 
          data: "barang_nama",                
          className: "", 
          orderable: true,     
          searchable: true     
        },{ 
          data: "harga_beli",                
          className: "text-right", 
          orderable: true,     
          searchable: false     
        },{ 
          data: "harga_jual",                
          className: "text-right", 
          orderable: true,     
          searchable: false     
        },{ 
          data: "aksi",                
          className: "", 
          orderable: false,     
          searchable: false     
        } 
      ]
    });

    // Event listener untuk dropdown kategori_id
    $('#kategori_id').change(function() {
      dataBarang.ajax.reload(); // Reload tabel saat filter kategori berubah
    });
  });
</script>
@endpush