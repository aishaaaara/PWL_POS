<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Penjualan:</label>
                    <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli:</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal:</label>
                    <input type="text" name="tanggal" id="tanggal" class="form-control" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>User:</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih User -</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}">{{ $user->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Barang:</label>
                    @foreach($barang as $barang)
                        <div class="col-md-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input barang-checkbox" type="checkbox" id="barang_{{ $barang->barang_id }}" name="barang[{{ $barang->barang_id }}][id]" value="{{ $barang->barang_id }}">
                                <label class="form-check-label" for="barang_{{ $barang->barang_id }}">
                                    {{ $barang->barang_nama }}
                                </label>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <input type="number" class="form-control barang-jumlah" id="barang_{{ $barang->barang_id }}_jumlah" name="barang[{{ $barang->barang_id }}][jumlah]" min="1" step="1" value="1" data-id="{{ $barang->barang_id }}">
                                <input type="hidden" class="form-control barang-harga" id="barang_{{ $barang->barang_id }}_harga" name="barang[{{ $barang->barang_id }}][harga]" value="{{ $barang->harga_jual }}">
                                <input type="text" class="form-control barang-subtotal" id="barang_{{ $barang->barang_id }}_subtotal" name="barang[{{ $barang->barang_id }}][subtotal]" readonly>
                            </div>
                        </div>
                    @endforeach
                </div>
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
        $("#form-tambah").validate({
            rules: {
                kategori_id: {
                    required: true,
                    number: true
                },
                penjualan_kode: { // Perbaikan nama
                    required: true,
                    maxlength: 10
                },
                pembeli: { // Perbaikan nama
                    required: true,
                    maxlength: 100
                },
                tanggal: { // Perbaikan nama
                    required: true,
                },
                user_id: { // Perbaikan nama
                    required: true,
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPenjualan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
