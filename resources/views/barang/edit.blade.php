@extends('layouts.app')


@section('contents')
<h1>Edit Form Peminjaman Barang</h1>
    <div>
        <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nama">Nama Peminjam</label>
                <input type="text" name="nama" class="form-control" value="{{ $peminjaman->nama }}" >
            </div>

            <div class="form-group">
                <label for="nomor_hp">Nomor HP</label>
                <input type="text" name="nomor_hp" class="form-control" value="{{ $peminjaman->nomor_hp }}" >
            </div>

            <div class="form-group">
                <label for="waktu_peminjaman">Tgl Peminjaman</label>
                <input type="date" name="waktu_peminjaman" class="form-control" value="{{ $peminjaman->waktu_peminjaman }}" >
            </div>
            
            <div class="form-group">
                <label for="waktu_pengembalian">Tgl Pengembalian</label>
                <input type="date" name="waktu_pengembalian" class="form-control" value="{{ $peminjaman->waktu_pengembalian }}" >
            </div>
            
            <div class="form-group">
                <label for="unit_kerja">Unit Kerja</label>
                <input type="text" name="unit_kerja" class="form-control" value="{{ $peminjaman->unit_kerja }}" >
            </div>

            <div class="form-group">
                <label for="kegiatan">Kegiatan</label>
                <input type="text" name="kegiatan" class="form-control" value="{{ $peminjaman->kegiatan }}" >
            </div>
            
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control">{{ $peminjaman->keterangan }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
&nbsp;
@endsection
