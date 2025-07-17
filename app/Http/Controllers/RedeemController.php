<?php

namespace App\Http\Controllers;

use App\Models\Redeem;
use App\Http\Requests\StoreRedeemRequest;
use App\Http\Requests\UpdateRedeemRequest;
use App\Models\MainData;
use App\Models\SubData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RedeemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redeems = SubData::where('user', Auth::user()->name)->get();

        return view('redeem.index', compact('redeems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fwc.redeem');
    }

    public function fetch(Request $request)
    {
        $data = null;

        if ($request->has('id_fwc')) {
            $data = MainData::where('id_fwc', $request->id_fwc)->first();
        } elseif ($request->has('nama')) {
            $data = MainData::where('nama', 'LIKE', "%{$request->nama}%")->first();
        }

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'id_fwc' => $data->id_fwc,
            'nama' => $data->nama,
            'kuota' => $data->kuota,
            'tgl_exp' => $data->tgl_exp->format('Y-m-d'),
            'expired' => $data->tgl_exp->isPast(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_pesanan' => 'required|string',
                'id_fwc' => 'required|exists:maindata,id_fwc',
                'tgl_departure' => 'required|date',
            ]);

            $fwc = MainData::where('id_fwc', $request->id_fwc)->first();

            if (!$fwc || $fwc->kuota <= 0 || Carbon::parse($fwc->tgl_exp)->isPast()) {
                return redirect()->back()->withErrors(['error' => 'FWC tidak valid untuk redeem.']);
            }

            // Simpan data redeem
            SubData::create([
                'id_fwc' => $fwc->id_fwc,
                'tgl_departure' => $request->tgl_departure,
                'user' => Auth::user()->name ?? 'Anonim',
                'id_pesanan' => $request->id_pesanan,
            ]);

            // Kurangi kuota
            $fwc->decrement('kuota');

            // return redirect()->route('fwc.index')->with('success', 'Redeem berhasil!');
            return redirect()->back()->with('success', 'Redeem berhasil!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kalau error karena ID Pesanan sudah ada (duplicate entry)
            if ($e->getCode() == 23000) {
                return redirect()->back()->withErrors(['error' => 'ID Pesanan sudah terdaftar.']);
            }

            // Log error lainnya
            Log::error('Gagal registrasi FWC', [
                'request' => $request->all(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat proses redeem.']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRedeemRequest $request, )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
    // Autocomplete Nama
    public function autocompleteByIDFWC(Request $request)
    {
        $term = $request->input('term');

        $results = MainData::where('id_fwc', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get();

        return response()->json($results->map(function ($item) {
            return [
                'label' => $item->id_fwc,
                'value' => $item->id_fwc,
                'id_fwc' => $item->id_fwc,
                'nama' => $item->nama,
                'relasi_fwc' => $item->relasi_fwc,
                'kuota' => $item->kuota,
                'tgl_exp' => $item->tgl_exp,
                'expired' => \Carbon\Carbon::parse($item->tgl_exp)->isPast(),
            ];
        }));
    }


}
