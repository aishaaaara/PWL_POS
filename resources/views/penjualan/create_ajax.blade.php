<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <input type="datetime-local" name="tanggal" id="tanggal" class="form-control" required> 
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>User:</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                </div>                        
                <div class="form-group">
                    <label>Barang:</label>
                    <div class="row">
                        @foreach($barang as $barangItem)
                            <div class="col-md-4 mb-3"> <!-- Menggunakan col-md-4 untuk 3 kolom per baris -->
                                <!-- Tambahkan card untuk styling modern -->
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <!-- Checkbox tetap digunakan, tapi lebih rapi -->
                                        <div class="form-check">
                                            <input class="form-check-input barang-checkbox" type="checkbox" id="barang_{{ $barangItem->barang_id }}" name="barang[{{ $barangItem->barang_id }}][id]" value="{{ $barangItem->barang_id }}">
                                            <label class="form-check-label" for="barang_{{ $barangItem->barang_id }}">
                                                {{ $barangItem->barang_nama }}
                                            </label>
                                        </div>
                
                                        <!-- Penambahan informasi harga dengan desain yang lebih menonjol -->
                                        <div class="mt-2">
                                            <p class="text-muted mb-1">Harga: Rp {{ number_format($barangItem->harga_jual, 0, ',', '.') }}</p>
                                        </div>
                
                                        <!-- Jumlah barang dengan input number dan perhitungan subtotal -->
                                        <div class="input-group input-group-sm mt-2">
                                            <input type="number" class="form-control barang-jumlah" id="barang_{{ $barangItem->barang_id }}_jumlah" name="barang[{{ $barangItem->barang_id }}][jumlah]" min="1" step="1" value="1" data-id="{{ $barangItem->barang_id }}">
                                            <input type="hidden" class="form-control barang-harga" id="barang_{{ $barangItem->barang_id }}_harga" name="barang[{{ $barangItem->barang_id }}][harga]" value="{{ $barangItem->harga_jual }}">
                                            <input type="text" class="form-control barang-subtotal" id="barang_{{ $barangItem->barang_id }}_subtotal" name="barang[{{ $barangItem->barang_id }}][subtotal]" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                penjualan_kode: {
                    required: true,
                    maxlength: 10
                },
                pembeli: {
                    required: true,
                    maxlength: 100
                },
                tanggal: {
                    required: true,
                },
                user_id: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                var dateTimeInput = $('#tanggal').val();
                if (!dateTimeInput) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Tanggal dan waktu tidak boleh kosong.'
                    });
                    return false; // Jangan kirim form jika tidak ada input tanggal
                }

                // Kirim data ke server melalui AJAX
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(), // Data form termasuk tanggal dengan format datetime-local
                    success: function(response) {
                        console.log(response); // Tambahkan ini untuk melihat respon
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan' 
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
                    },
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

        // Menghitung subtotal setiap kali jumlah barang diubah
        $('.barang-jumlah').on('input', function() {
            var id = $(this).data('id');
            var harga = parseFloat($('#barang_' + id + '_harga').val());
            var jumlah = parseFloat($(this).val());
            var subtotal = harga * jumlah;

            $('#barang_' + id + '_subtotal').val(subtotal.toFixed(2)); // Format dua angka desimal
        });
    });
</script>

