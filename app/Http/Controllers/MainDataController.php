<?php

namespace App\Http\Controllers;

use App\Models\MainData;
use App\Http\Requests\StoreMainDataRequest;
use App\Http\Requests\UpdateMainDataRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminOnly;
use App\Models\Subdata;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;


class MainDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = MainData::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('id_fwc', 'like', "%$search%")
                ->orWhere('id_num', 'like', "%$search%");
        })->orderBy('created_at', 'desc')->get();

        return view('fwc.index', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fwc.register');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'id_num' => 'required|numeric|unique:MainData,id_num',
                'relasi_fwc' => 'required|in:01,02,03',
                'jenis_fwc' => 'required|in:G,S',
                'email' => 'nullable|email',
                'no_hp' => 'nullable|string|max:15',
            ]);

            $tgl = now();
            $tglKode = $tgl->format('dmy');
            $jenis = $request->jenis_fwc;
            $relasi = $request->relasi_fwc;

            $count = MainData::whereDate('tgl_reg', $tgl)->count() + 1;
            $urutan = str_pad($count, 3, '0', STR_PAD_LEFT);

            $id_fwc = $jenis . $relasi . $tglKode . $urutan;

            $tgl_exp = $jenis === 'G' ? $tgl->copy()->addDays(60) : $tgl->copy()->addDays(30);
            $kuota = $jenis === 'G' ? 10 : 6;

            $newData = MainData::create([
                'id_fwc' => $id_fwc,
                'nama' => $request->nama,
                'id_num' => $request->id_num,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'tgl_reg' => $tgl,
                'tgl_exp' => $tgl_exp,
                'relasi_fwc' => $relasi,
                'jenis_fwc' => $jenis,
                'kuota' => $kuota,
            ]);

            Log::info('FWC berhasil dibuat:', $newData->toArray());

            return redirect()->back()->with('success', 'Registrasi FWC berhasil!');
            // return redirect()->route('fwc.index')->with('success', 'Registrasi FWC berhasil!');
            // return response()->json([$newData]);

        } catch (\Throwable $e) {
            Log::error('Gagal registrasi FWC: ' . $e->getMessage(), [
                'request' => $request->all()
            ]);

            return redirect()->back()->withInput()->withErrors(['error' => 'Registrasi gagal. Silakan coba lagi.']);
            // return response()->json([
            //     'error' => 'Registrasi gagal. Silakan coba lagi.',
            //     'message' => $e->getMessage()
            // ], 500);

        }
    }



    /**
     * Display the specified resource.
     */
    public function show(MainData $MainData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainData $MainData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, MainData $MainData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fwc = MainData::where('id_fwc', $id)->firstOrFail();
        $fwc->delete();

        return response()->json(['success' => true]);
    }

    public function exportMain()
    {
        $data = DB::table('maindata')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            'ID FWC',
            'Nama',
            'ID Number',
            'No HP',
            'Email',
            'Relasi',
            'Jenis',
            'Kuota',
            'Tgl Registrasi',
            'Tgl Expired'
        ], NULL, 'A1');

        // Data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A$row", $item->id_fwc);
            $sheet->setCellValue("B$row", $item->nama);
            $sheet->setCellValue("C$row", $item->id_num);
            $sheet->setCellValue("D$row", $item->no_hp);
            $sheet->setCellValue("E$row", $item->email);
            // if item->relasi_fwc == 1 then jaban, if 2 then jaka, if 3 then kaban
            $relasiLabel = ['01' => 'Jaban', '02' => 'Jaka', '03' => 'Kaban'];
            $sheet->setCellValue("F$row", $relasiLabel[$item->relasi_fwc] ?? 'Unknown');
            // $sheet->setCellValue("F$row", $item->relasi_fwc);

            $sheet->setCellValue("G$row", $item->jenis_fwc);
            $sheet->setCellValue("H$row", $item->kuota);
            $sheet->setCellValue("I$row", $item->tgl_reg);
            $sheet->setCellValue("J$row", $item->tgl_exp);
            $row++;
        }

        // Buat response
        $filename = 'fwc_main.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Simpan ke memori
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    public function exportSub()
    {
        $data = DB::table('subdata')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            'ID FWC',
            'ID Pesanan',
            'Tgl Berangkat',
            'Tgl Redeem',
            'User'
        ], NULL, 'A1');

        // Data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A$row", $item->id_fwc);
            $sheet->setCellValue("B$row", $item->id_pesanan);
            $sheet->setCellValue("C$row", $item->tgl_departure);
            $sheet->setCellValue("D$row", $item->created_at);
            $sheet->setCellValue("E$row", $item->user);
            $row++;
        }

        $filename = 'fwc_sub.xlsx';
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
}