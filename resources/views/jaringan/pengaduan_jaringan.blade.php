@extends('layouts.app')

@section('title', 'List Pengaduan Jaringan')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <!-- Form untuk mencari data peminjaman -->
        <form action="{{ route('jaringan.admin') }}" method="GET" class="form-inline">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ isset($search) ? $search : '' }}">
            </div>
            <button type="submit" class="btn btn-warning ml-2">Search</button>
        </form>
        {{-- modal cetak laporan --}}
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#cetakModal">
            Cetak Laporan</button>
    </div>
    <hr />
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session()->get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table table-hover">
        <thead class="table-warning">
            <tr>
                <th>No</th>
                <th>Nama Pelapor</th>
                <th>Kendala Jaringan</th>
                <th>Ruangan</th>
                <th>Waktu Pelaporan</th>
                <th>Foto</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($jaringan->count() > 0)
                @foreach ($jaringan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kendala_jaringan }}</td>
                        <td>{{ $item->ruangan }}</td>
                        <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                        <td>
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto"
                                    style="width: 100px; height: auto;">
                            @endif
                        </td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            {{-- cetak laporan --}}
                            <a href="{{ route('jaringan.print', $item->id) }}" class="btn btn-secondary">Cetak</a>
                            {{-- modal edit --}}
                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#editModal{{ $item->id }}">
                                Edit</button>
                            {{-- modal delete jaringan --}}
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#deleteModal{{ $item->id }}">
                                Hapus</button>
                        </td>
                    </tr>
                    <!-- Modal Edit keterangan -->
                    <div class="modal fade text-left" id="editModal{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('jaringan.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit keterangan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div
                                            class="form-group
                                        @error('keterangan') has-error @enderror">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan">{{ $item->keterangan }}</textarea>
                                            @error('keterangan')
                                                <span class="help-block text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Delete Jaringan -->
                    <div class="modal fade text-left" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('jaringan.delete', $item->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">Hapus Data
                                            Jaringan
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda Yakin Ingin Menghapus Data Jaringan Ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="11">Kendala Jaringan Tidak Ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $jaringan->links() }}

    <!-- Modal Cetak Laporan -->
    <div class="modal fade text-left" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="cetakModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('jaringan.cetak') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="cetakModalLabel">Cetak Laporan Kendala Jaringan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- form untuk memilih tahun --}}
                        <div class="form-group"></div>
                        <label for="tahun">Pilih Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            @for ($i = date('Y') - 3; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
