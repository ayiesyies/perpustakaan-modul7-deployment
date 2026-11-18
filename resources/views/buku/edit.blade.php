@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card border-0 text-white p-4 mb-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #0e4c68 0%, #0099e5 100%);">
        <h3 class="fw-bold m-0">✏️ Ubah Data Buku</h3>
        <p class="m-0 mt-1 text-white-50">Perbarui informasi detail buku di bawah ini.</p>
    </div>

    <div class="card card-custom p-4 border-0 shadow-sm col-md-8 mx-auto">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('buku.update', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul" class="form-label fw-semibold">Judul Buku</label>
                <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $buku->judul) }}" required>
            </div>

            <div class="mb-3">
                <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                @if(isset($kategoris) && $kategoris->count() > 0)
                    <select name="kategori_id" id="kategori_id" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama ?? $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="number" name="kategori_id" id="kategori_id" class="form-control" value="{{ old('kategori_id', $buku->kategori_id) }}">
                @endif
            </div>

            <div class="mb-3">
                <label for="penulis" class="form-label fw-semibold">Penulis</label>
                <input type="text" name="penulis" id="penulis" class="form-control" value="{{ old('penulis', $buku->penulis) }}" required>
            </div>

            <div class="mb-3">
                <label for="tahun_terbit" class="form-label fw-semibold">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" required>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection