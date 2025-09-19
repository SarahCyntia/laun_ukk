<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function cekStatus($tracking_code)
    {
        $pesanan = Pesanan::with(['pelanggan', 'paket'])
            ->where('tracking_code', $tracking_code)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Kode tracking tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pesanan
        ]);
    }
}
