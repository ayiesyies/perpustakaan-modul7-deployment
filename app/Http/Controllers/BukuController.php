<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

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
     * Menyimpan data buku baru ke database Supabase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'nullable',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric',
        ]);

        Buku::create([
            'judul'        => $validated['judul'],
            'kategori_id'  => $request->kategori_id,
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
     * Memperbarui data buku yang sudah ada.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'nullable',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric',
        ]);

        $buku->update([
            'judul'        => $validated['judul'],
            'kategori_id'  => $request->kategori_id,
            'penulis'      => $validated['penulis'],
            'tahun_terbit' => $validated['tahun_terbit'],
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