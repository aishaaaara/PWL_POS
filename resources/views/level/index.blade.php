@extends('layouts.template') 
@section('content') 
<div class="card card-outline card-primary"> 
    <div class="card-header">
        <h3 class="card-title">Daftar Level</h3>
        <div >
        <button onclick="modalAction('{{ url('/level/create_ajax') }}')" class="btn btn-success">Tambah level</button>
          <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import level</button>
          <a href="{{ url('/level/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export level (Excel)</a>        
          <a href="{{ url('/level/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export level(PDF)</a>
        </div>
    </div>
    <div class="card-body"> 
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm mt-3" id="table_level">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Level Kode</th>
                    <th>Level Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div> 
@endsection 

@push('js') 
<script>
  function modalAction(url = ''){ 
      $('#myModal').load(url, function(){ 
          $('#myModal').modal('show'); 
      });
  }

  var dataLevel;
  $(document).ready(function() {
    // Inisialisasi DataTable
    dataLevel = $('#table_level').DataTable({
        processing: true,
        serverSide: true,      
        ajax: { 
            "url": "{{ url('level/list') }}", 
            "dataType": "json", 
            "type": "POST",
            "data": function (d) {
                d.level_kode = $('#level_kode').val();
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
                data: "level_kode",       
                orderable: true,     
                searchable: true     
            },
            { 
                data: "level_nama",  
                orderable: false,     
                searchable: false     
            },
            { 
                data: "aksi",   
                orderable: false,     
                searchable: false     
            }
        ]
    });

    $('#table-level_filter input').unbind().bind().on('keyup', function(e){
        if(e.keyCode == 13){ // enter key
            dataLevel.search(this.value).draw();
        }
    });
});

</script>
@endpush  
