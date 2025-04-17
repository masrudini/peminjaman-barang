@extends('layouts.app')
@section('title', 'List Peminjaman Ruangan')
@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <!-- Form untuk mencari data peminjaman -->
        <form action="{{ route('ruangan.peminjaman') }}" method="GET" class="form-inline">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ isset($search) ? $search : '' }}">
            </div>
            <button type="submit" class="btn btn-warning ml-2">Search</button>
        </form>
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
    <table class="table table-hover table-responsive w-100 d-block d-md-table">
        <thead class="table-warning">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Nomor HP</th>
                <th>Kegiatan</th>
                <th>Ruangan</th>
                <th>Tgl Peminjaman</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($peminjam_ruangan->count() > 0)
                @foreach ($peminjam_ruangan as $p)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $p->nama }}</td>
                        <td class="align-middle">{{ $p->no_hp }}</td>
                        <td class="align-middle">{{ $p->kegiatan }}</td>
                        <td class="align-middle">{{ $p->ruangan->nama }}</td>
                        <td class="align-middle">{{ date('d-m-Y', strtotime($p->tanggal_pinjam)) }}</td>
                        <td class="align-middle">{{ date('h:i', strtotime($p->jam_mulai)) }}</td>
                        <td class="align-middle">{{ date('h:i', strtotime($p->jam_selesai)) }}</td>
                        <td class="align-middle">
                            @if ($p->status == 'Pending')
                                <span class="badge badge-warning">{{ $p->status }}</span>
                            @elseif ($p->status == 'Diterima')
                                <span class="badge badge-success">{{ $p->status }}</span>
                            @elseif ($p->status == 'Ditolak')
                                <span class="badge badge-danger">{{ $p->status }}</span>
                            @elseif ($p->status == 'Selesai')
                                <span class="badge badge-secondary">{{ $p->status }}</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            {{ $p->keterangan ?? '-' }}
                        </td>
                        <td class="align-middle d-flex flex-wrap gap-2">
                            {{-- modal edit status --}}
                            <button type="button" class="btn btn-sm btn-primary rounded" data-toggle="modal"
                                data-target="#modalStatus{{ $p->id }}"><i class="fas fa-fw fa-edit"
                                    aria-hidden="true"></i> Status</button>
                            {{-- modal delete --}}
                            <button type="button" class="btn btn-sm btn-danger rounded" data-toggle="modal"
                                data-target="#modalDelete{{ $p->id }}"><i class="fas fa-fw fa-trash"
                                    aria-hidden="true"></i>
                                Delete</button>
                        </td>
                    </tr>
                    {{-- modal status --}}
                    <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="modalStatus{{ $p->id }}Label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalStatus{{ $p->id }}Label">Edit Status Peminjaman
                                        Ruangan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('ruangan.peminjaman.update', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div
                                            class="form-group
                                                        mb-3">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Pending" {{ $p->status == 'Pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="Diterima" {{ $p->status == 'Diterima' ? 'selected' : '' }}>
                                                    Diterima</option>
                                                <option value="Ditolak" {{ $p->status == 'Ditolak' ? 'selected' : '' }}>
                                                    Ditolak</option>
                                                <option value="Selesai" {{ $p->status == 'Selesai' ? 'selected' : '' }}>
                                                    Selesai</option>
                                            </select>
                                        </div>
                                        <div
                                            class="form-group
                                                        mb-3">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ $p->keterangan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- modal delete --}}
                    <div class="modal
                                        fade" id="modalDelete{{ $p->id }}"
                        tabindex="-1" role="dialog" aria-labelledby="modalDelete{{ $p->id }}Label"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDelete{{ $p->id }}Label">Hapus
                                        Peminjaman
                                        Ruangan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('ruangan.peminjaman.delete', $p->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data peminjaman ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="11">Peminjaman tidak ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{ $peminjam_ruangan->links() }}

@endsection
