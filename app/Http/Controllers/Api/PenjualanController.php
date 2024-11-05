<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenjualanController extends Controller {
    public function index()
    {
        $penjualan = PenjualanModel::with(['detail.barang'])->get();

        $penjualan->transform(function ($item) {
            $item->detail->transform(function ($detail) {
                if ($detail->barang && $detail->barang->image) {
                    $detail->barang->image;
                }
                return $detail;
            });
            return $item;
        });

        return response()->json($penjualan);
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with(['detail.barang'])->findOrFail($id);

        $penjualan->detail->transform(function ($detail) {
            if ($detail->barang && $detail->barang->image) {
                $detail->barang->image;
            }
            return $detail;
        });

        return response()->json($penjualan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', Rule::exists('m_user', 'user_id')],
            'pembeli' => 'required|string',
            'penjualan_kode' => 'required|string|max:10',
            'penjualan_tanggal' => 'required|date',
            'detail' => 'required|array',
            'detail.*.barang_id' => 'required|exists:m_barang,barang_id',
            'detail.*.jumlah' => 'required|integer',
        ]);

        $penjualan = PenjualanModel::create($request->only(['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal']));

        foreach ($request->detail as $detail) {
            $barang = BarangModel::findOrFail($detail['barang_id']);
            $harga = $barang->harga_jual;

            DetailPenjualanModel::create([
                'penjualan_id' => $penjualan->penjualan_id,
                'barang_id' => $detail['barang_id'],
                'harga' => $harga,
                'jumlah' => $detail['jumlah'],
            ]);
        }

        return response()->json($penjualan->load('detail.barang'), 201);
    }

}