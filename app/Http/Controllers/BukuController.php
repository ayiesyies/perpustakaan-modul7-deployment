<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku ke tampilan index (terbaru di atas).
     */
    public function index()
    {
        $bukus = Buku::latest()->get();
        return view('buku.index', compact('bukus'));
    }

    /**
     * Pencarian buku aman dari SQL Injection menggunakan Eloquent Parameter Binding.
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul');
        $buku = Buku::where('judul', $judul)->get();

        return response()->json($buku);
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        $kategoris = $this->getKategorisSafely();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Menyimpan data buku baru ke database Supabase menggunakan StoreBukuRequest.
     */
    public function store(StoreBukuRequest $request)
    {
        $validated = $request->validated();

        Buku::create([
            'judul'        => $validated['judul'],
            'kategori_id'  => $validated['kategori_id'],
            'penulis'      => $validated['penulis'],
            'tahun_terbit' => $validated['tahun_terbit'],
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(Buku $buku)
    {
        $kategoris = $this->getKategorisSafely();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Memperbarui data buku yang sudah ada menggunakan UpdateBukuRequest.
     */
    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $validated = $request->validated();

        $buku->update([
            'judul'        => $validated['judul'] ?? $buku->judul,
            'kategori_id'  => $validated['kategori_id'] ?? $buku->kategori_id,
            'penulis'      => $validated['penulis'] ?? $buku->penulis,
            'tahun_terbit' => $validated['tahun_terbit'] ?? $buku->tahun_terbit,
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Menghapus data buku (dipanggil via metode DELETE + CSRF Token).
     */
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Mengambil data kategori secara aman tanpa memicu error 500.
     */
    private function getKategorisSafely()
    {
        try {
            if (class_exists(\App\Models\Kategori::class)) {
                return \App\Models\Kategori::all();
            }
        } catch (\Throwable $e) {
            return collect([]);
        }

        return collect([]);
    }
}