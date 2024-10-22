@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">ID :</th>
                    <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Kode :</th>
                    <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Pembeli :</th>
                    <td class="col-9">{{ $penjualan->pembeli }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">User :</th>
                    <td class="col-9">{{ $penjualan->user->nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal :</th>
                    <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Barang :</th>
                    <td>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penjualan->detail as $detail)
                                    <tr>
                                        <td>{{ $detail->barang->barang_nama }}</td>
                                        <td>{{ $detail->harga }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->harga * $detail->jumlah }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty