<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MahasiswaController extends Controller
{
    // READ (Tampilkan data + pagination + fitur pencarian opsional)
    public function index(Request $request)
    {
        // Ambil kata kunci dari input pencarian (jika ada)
        $keyword = $request->input('search');

        // Query dasar
        $mahasiswa = Mahasiswa::query();

        // Jika ada keyword, filter berdasarkan nama, NIM, atau email
        if (!empty($keyword)) {
            $mahasiswa->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nim', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
        }

        // Gunakan pagination (5 data per halaman)
        $mahasiswa = $mahasiswa->paginate(5);

        // Kirim data ke view
        return view('mahasiswa.index', compact('mahasiswa', 'keyword'));
    }

    // CREATE FORM
    public function create()
    {
        return view('mahasiswa.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:mahasiswas',
            'email' => 'required|email|unique:mahasiswas',
        ]);

        Mahasiswa::create($request->all());
        return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil ditambahkan!');
    }

    // EDIT FORM
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // UPDATE
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'email' => 'required|email|unique:mahasiswas,email,' . $mahasiswa->id,
        ]);

        $mahasiswa->update($request->all());
        return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil diperbarui!');
    }

    // DELETE
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil dihapus!');
    }

    // CETAK PDF
    public function cetakPDF()
    {
        $mahasiswas = Mahasiswa::all();
        $pdf = Pdf::loadView('mahasiswa.pdf', compact('mahasiswas'));
        return $pdf->stream('daftar_mahasiswa.pdf');
    }

    // EXPORT EXCEL
    public function exportExcel()
    {
        $mahasiswas = Mahasiswa::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'NIM');
        $sheet->setCellValue('C1', 'Email');

        $row = 2;
        foreach ($mahasiswas as $mhs) {
            $sheet->setCellValue('A' . $row, $mhs->nama);
            $sheet->setCellValue('B' . $row, $mhs->nim);
            $sheet->setCellValue('C' . $row, $mhs->email);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Daftar_Mahasiswa.xlsx';
        $filePath = storage_path($fileName);

        $writer->save($filePath);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
