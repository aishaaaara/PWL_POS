@empty($stok)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/stok/' . $stok->stok_id . '/update_ajax') }}" method="POST" id="form-edit-stok">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                            <option value="">- Pilih Supplier -</option>
                            @foreach ($supplier as $s)
                                <option value="{{ $s->supplier_id }}" {{ $s->supplier_id == $stok->supplier_id ? 'selected' : '' }}>
                                    {{ $s->supplier_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                    </div>
                    <label>User</label>
                    <div class="form-group">
                        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->user_id }}">
                        <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                    </div>                    
                    <div class="form-group">
                        <label>Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="">- Pilih Barang -</option>
                            @foreach ($barang as $b)
                                <option value="{{ $b->barang_id }}" {{ $b->barang_id == $stok->barang_id ? 'selected' : '' }}>
                                    {{ $b->barang_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-barang_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Stok</label>
                        <input value="{{ $stok->stok_jumlah }}" type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" required>
                        <small id="error-stok_jumlah" class="error-text form-text text-danger"></small>
                    </div>
                    <input type="hidden" name="stok_tanggal" id="stok_tanggal" value="{{ now()->format('Y-m-d H:i:s') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-edit-stok").validate({
                rules: {
                    supplier_id: {
                        required: true
                    },
                    user_id: {
                        required: true
                    },
                    barang_id: {
                        required: true
                    },
                    stok_jumlah: {
                        required: true,
                        min: 1
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide'); // Pastikan ID modal sesuai
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                tableStok.ajax.reload(); // Reload tabel setelah update
                            } else {
                                $('.error-text').text(''); // Kosongkan pesan kesalahan sebelumnya
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]); // Tampilkan kesalahan di bawah dropdown
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Menangani kesalahan AJAX
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Server',
                                text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.'
                            });
                        }
                    });
                    return false; // Mencegah pengiriman form normal
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error); // Menambahkan kesalahan di bawah elemen form
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid'); // Menambahkan kelas is-invalid untuk styling
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid'); // Menghapus kelas is-invalid saat valid
                }
            });
        });
    </script>
@endempty
