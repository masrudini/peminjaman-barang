@extends('layouts.app')
@section('contents')
    <style>
        .btn-group .btn {
            flex: 1;
            margin-right: 5px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <form action="{{ route('ruangan.index') }}" method="GET" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-warning">Search</button>
        </form>

        {{-- modal create ruangan --}}
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createRuangan">
            Create Ruangan</button>

        <div class="modal fade" id="createRuangan" tabindex="-1" role="dialog" aria-labelledby="createRuanganLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRuanganLabel">Create Ruangan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div
                                class="form-group
                                @error('nama_ruangan') has-error @enderror">
                                <label for="nama_ruangan">Nama Ruangan</label>
                                <input type="text" name="nama_ruangan" class="form-control" id="nama_ruangan"
                                    value="{{ old('nama_ruangan') }}">
                                @error('nama_ruangan')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group @error('image') has-error @enderror">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                                @error('image')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group
                            @error('deskripsi') has-error @enderror">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea type="text" name="deskripsi" class="form-control" id="deskripsi"></textarea>
                                @error('deskripsi')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group @error('status') has-error @enderror">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia
                                    </option>
                                    <option value="Tidak Tersedia"
                                        {{ old('status') == 'Tidak Tersedia' ? 'selected' : '' }}>
                                        Tidak Tersedia</option>
                                </select>
                                @error('status')
                                    <span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                <th>Nama Ruangan</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ruangan as $item)
                {{-- justify align center --}}
                <tr class="align-items-center">
                    <td class="align-middle">{{ $loop->iteration }}</td>
                    <td class="align-middle">{{ $item->nama }}</td>
                    <td class="align-middle">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama }}"
                            style="max-width: 200px;">
                    </td>
                    <td class="align-middle">
                        {{-- badge --}}
                        @if ($item->status == 'Tersedia')
                            <span class="badge badge-success">{{ $item->status }}</span>
                        @else
                            <span class="badge badge-danger">{{ $item->status }}</span>
                        @endif
                    <td class="align-middle">
                        <div class="btn-group">
                            {{-- tombol modal edit --}}
                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#editRuangan{{ $item->id }}">Edit</button>

                            {{-- tombol modal delete --}}
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#deleteRuangan{{ $item->id }}">Delete</button>

                        </div>
                    </td>
                </tr>
                {{-- modal edit --}}
                <div class="modal fade" id="editRuangan{{ $item->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editRuangan{{ $item->id }}Label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('ruangan.update', $item->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRuangan{{ $item->id }}Label">Edit
                                        Ruangan
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div
                                        class="form-group
                                            @error('nama_ruangan') has-error @enderror">
                                        <label for="nama_ruangan">Nama Ruangan</label>
                                        <input type="text" name="nama_ruangan" class="form-control" id="nama_ruangan"
                                            value="{{ $item->nama }}">
                                        @error('nama_ruangan')
                                            <span class="help-block text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div
                                        class="form-group
                                        @error('image') has-error @enderror">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control" id="image">
                                        @error('image')
                                            <span
                                                class="help-block
                                                text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div
                                        class="form-group
                                        @error('deskripsi') has-error @enderror">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea type="text" name="deskripsi" class="form-control" id="deskripsi">{{ $item->deskripsi }}</textarea>
                                        @error('deskripsi')
                                            <span class="help-block text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div
                                        class="form-group
                                        @error('status') has-error @enderror">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" id="status">
                                            <option value="Tersedia" {{ $item->status == 'Tersedia' ? 'selected' : '' }}>
                                                Tersedia
                                            </option>
                                            <option value="Tidak Tersedia"
                                                {{ $item->status == 'Tidak Tersedia' ? 'selected' : '' }}>
                                                Tidak
                                                Tersedia</option>
                                        </select>
                                        @error('status')
                                            <span class="help-block text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- modal delete  --}}
                <div class="modal fade" id="deleteRuangan{{ $item->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="deleteRuangan{{ $item->id }}Label" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('ruangan.delete', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteRuangan{{ $item->id }}Label">
                                        Delete
                                        Ruangan
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Anda yakin menghapus ruangan ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
@endsection
