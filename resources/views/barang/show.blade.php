@extends('layouts.app')
@push('customscript')
    <link href="{{ asset('css/custom-css/style.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('contents')
    <div class="container">
        <div class="row">
            <!-- Detail barang -->
            <div class="col-md-6">
                <div class="project-info-box mt-0">
                    <h5>Detail Barang {{ $barang->nama_barang }}</h5>

                </div><!-- / project-info-box -->

                <div class="project-info-box">
                    <p><b>Kode Barang:</b> {{ $barang->kode_barang }}</p>
                    <p><b>Kondisi:</b> {{ $barang->kondisi }}</p>
                    <p><b>Tanggal Masuk:</b> {{ $barang->tgl_masuk }}</p>
                </div><!-- / project-info-box -->

                <div class="project-info-box">
                    @if ($barang->status == 'Tersedia')
                        <p><b>Status:</b> <span class="badge badge-success">{{ $barang->status }}</span></p>
                    @else
                        <p><b>Status:</b> <span class="badge badge-danger">{{ $barang->status }}</span></p>
                    @endif
                </div><!-- / project-info-box -->

                <div class="project-info-box">
                    <p><b>Keterangan:</b> {{ $barang->keterangan }}</p>
                </div><!-- / project-info-box -->
            </div><!-- / column -->

            <div class="col-md-4 col-sm-12 mb-3">
                @if ($barang->image)
                    <img src="{{ asset('storage/' . $barang->image) }}" alt="Gambar Barang" class="rounded img-fluid"
                        style="max-width: 100%;">
                @else
                    <p>No image available</p>
                @endif

            </div>
            <div class="col-md-6">
                <div class="project-info-box mt-0">
                    <p><b>Riwayat Peminjaman:</b></p>
                    <table class="table table-hover table-responsive w-100 d-block d-md-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Kegiatan</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang->barangDiBerikan as $item)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $item->peminjaman->nama }}</td>
                                    <td class="align-middle">{{ $item->peminjaman->kegiatan }}</td>
                                    <td class="align-middle">{{ $item->peminjaman->waktu_peminjaman }}</td>
                                    <td class="align-middle">{{ $item->peminjaman->waktu_pengembalian }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
