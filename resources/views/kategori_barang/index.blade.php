@extends('layouts.app')

@section('title', 'List Kategori Barang')

@section('contents')
<div class="d-flex align-items-center justify-content-between mb-3">
    <form action="{{ route('kategori_barang.index') }}" method="GET" class="form-inline">
        <div class="form-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-warning">Search</button>
    </form>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
        Tambah Kategori
    </button>
    {{-- modal tambah kategori --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kategori_barang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Kategori Barang</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<hr>
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
            <th>Nama Kategori</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if ($kategori_barang->count() > 0)
        @foreach ($kategori_barang as $kategori)
        <tr>
            <td class="align-middle">{{ $loop->iteration }}</td>
            <td class="align-middle">{{ $kategori->nama_kategori }}</td>
            <td class="align-middle">
                <div class="btn-group gap-1" role="group" aria-label="Basic example">
                    {{-- button modal edit --}}
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal{{ $kategori->id }}">
                        Edit
                    </button>
                    {{-- button modal delete --}}
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $kategori->id }}">
                        Delete
                    </button>
                </div>
            </td>
        </tr>
        {{-- modal delete --}}
        <div class="modal fade" id="deleteModal{{ $kategori->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{ $kategori->id }}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal{{ $kategori->id }}Label">
                            Delete User
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('kategori_barang.destroy', $kategori->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Apakah anda yakin ingin menghapus kategori barang ini?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- modal edit --}}
        <div class="modal fade bd-example-modal-lg" id="editModal{{ $kategori->id }}" tabindex="-1" role="dialog" aria-labelledby="editModal{{ $kategori->id }}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('kategori_barang.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModal{{ $kategori->id }}Label">
                                Edit Kategori Barang
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_kategori">
                                    Nama Kategori</label>
                                <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <tr>
            <td class="text-center" colspan="5">Kategori Barang not found</td>
        </tr>
        @endif
    </tbody>
</table>
@endsection
