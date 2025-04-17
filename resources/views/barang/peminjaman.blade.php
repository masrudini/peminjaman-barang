@extends('layouts.app')

@section('title', 'List Peminjaman Barang')

@section('contents')
<div class="d-flex align-items-center justify-content-between">
    <!-- Form untuk mencari data peminjaman -->
    <form action="{{ route('peminjaman.index') }}" method="GET" class="form-inline">
        <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ isset($search) ? $search : '' }}">
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
            <th>Kategori Barang (Jumlah)</th>
            <th>Barang Diberikan</th>
            <th>Tgl Peminjaman</th>
            <th>Tgl Pengembalian</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if ($peminjamans->count() > 0)
        @foreach ($peminjamans as $p)
        <tr>
            <td class="align-middle">{{ $loop->iteration }}</td>
            <td class="align-middle">{{ $p->nama }}</td>
            <td class="align-middle">{{ $p->nomor_hp }}</td>
            <td class="align-middle">{{ $p->kegiatan }}</td>
            <td class="align-middle">
                <ul>
                    @foreach ($p->barangDiPinjam as $item)
                    <li>{{ $item->kategoriBarang->nama_kategori }} ({{ $item->jumlah }})</li>
                    @endforeach
                </ul>
            </td>
            <td class="align-middle">
                <ul>
                    @foreach ($p->barangDiBerikan as $item)
                    <li>{{ $item->barang->nama_barang }}</li>
                    @endforeach
                </ul>
            </td>
            <td class="align-middle">{{ date('d-m-Y', strtotime($p->waktu_peminjaman)) }}</td>
            <td class="align-middle">{{ date('d-m-Y', strtotime($p->waktu_pengembalian)) }}</td>
            <td class="align-middle">
                @if ($p->status == 'Pending')
                <span class="badge badge-warning">{{ $p->status }}</span>
                @elseif ($p->status == 'Diterima')
                <span class="badge badge-success">{{ $p->status }}</span>
                @elseif ($p->status == 'Ditolak')
                <span class="badge badge-danger">{{ $p->status }}</span>
                @elseif ($p->status == 'Selesai')
                <span class="badge badge-primary">{{ $p->status }}</span>
                @endif
            </td>
            <td class="align-middle">
                {{ $p->keterangan ?? '-' }}
            </td>
            <td class="align-middle d-flex flex-wrap gap-1">
                {{-- modal edit status --}}
                <button type="button" class="btn btn-sm btn-primary rounded" data-toggle="modal" data-target="#modalStatus{{ $p->id }}"><i class="fas fa-fw fa-edit" aria-hidden="true"></i> Status</button>
                <a href="{{ route('peminjaman.download-pdf', $p->id) }}" class="btn btn-sm btn-secondary rounded"><i class="fa fa-file" aria-hidden="true"></i>
                    PDF
                </a>
                {{-- modal delete --}}
                <button type="button" class="btn btn-sm btn-danger rounded" data-toggle="modal" data-target="#modalDelete{{ $p->id }}"><i class="fas fa-fw fa-trash" aria-hidden="true"></i>
                    Delete</button>
                {{-- tombol modal gambar --}}
                <button type="button" class="btn btn-sm btn-info rounded" data-toggle="modal" data-target="#modalGambar{{ $p->id }}"><i class="fas fa-fw fa-image" aria-hidden="true"></i>
                    Gambar</button>
            </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="modalStatus{{ $p->id }}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStatus{{ $p->id }}Label">Ubah Status Peminjaman
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('peminjaman.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <p>Barang yang dipinjam:</p>
                            <ul>
                                @foreach ($p->barangDiPinjam as $item)
                                <li>{{ $item->kategoriBarang->nama_kategori }} ({{ $item->jumlah }})</li>
                                @endforeach
                            </ul>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status{{ $p->id }}" class="form-control status-dropdown" data-id="{{ $p->id }}">
                                    <option value="Pending" {{ $p->status == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Diterima" {{ $p->status == 'Diterima' ? 'selected' : '' }}>
                                        Diterima
                                    </option>
                                    <option value="Ditolak" {{ $p->status == 'Ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                    <option value="Selesai" {{ $p->status == 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                            </div>
                            {{-- input multiple gambar sebelum --}}
                            <div class="form-group" id="gambarSebelum-{{ $p->id }}" style="display: none">
                                <label for="gambar_sebelum">Gambar Penyerahan</label>
                                <input type="file" name="gambar_sebelum[]" class="form-control" accept="image/*" id="gambar_sebelum" multiple>
                            </div>
                            {{-- input gambar setelah --}}
                            <div class="form-group" id="gambarSesudah-{{ $p->id }}" style="display: none">
                                <label for="gambar_sesudah">Gambar Pengembalian</label>
                                <input type="file" name="gambar_sesudah[]" class="form-control" accept="image/*" id="gambar_sesudah" multiple>
                            </div>
                            <!-- Dropdown Barang, hidden by default -->
                            <div class="form-group" id="barangDropdown-{{ $p->id }}" style="display: none">
                                <label for="barang">Barang</label>
                                <select id="select-barang-{{ $p->id }}" name="barangs[]" multiple placeholder="Select Product" autocomplete="off">
                                    @if ($p->barangDiBerikan->count() > 0)
                                    @foreach ($p->barangDiBerikan as $item)
                                    <option value="{{ $item->barang->id }}" selected>
                                        {{ $item->barang->nama_barang }}
                                    </option>
                                    @endforeach
                                    @foreach ($barangs as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_barang }}
                                    </option>
                                    @endforeach
                                    @else
                                    @foreach ($barangs as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_barang }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control">{{ $p->keterangan }}</textarea>
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

        {{-- modal delete --}}
        <div class="modal fade" id="modalDelete{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDelete{{ $p->id }}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDelete{{ $p->id }}Label">Delete Peminjaman
                            Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('peminjaman.delete', $p->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data peminjaman ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- modal carousel gambar --}}
        <div class="modal fade bd-example-modal-lg" id="modalGambar{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="modalGambar{{ $p->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="d-flex modal-body text-center">
                        <div class="col-md-6">
                            <h5>Barang Saat Penyerahan</h5>
                            <div id="carouselExampleControlsSebelum{{ $p->id }}" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($gambar_sebelum->where('peminjaman_id', $p->id) as $item)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $item->gambar_sebelum) }}" class="img-fluid" style="max-height: 40vh;" alt="...">
                                    </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControlsSebelum{{ $p->id }}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black;"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControlsSebelum{{ $p->id }}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black;"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Barang Saat Kembali</h5>
                            <div id="carouselExampleControlsSesudah{{ $p->id }}" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($gambar_sesudah->where('peminjaman_id', $p->id) as $item)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $item->gambar_sesudah) }}" class="img-fluid" style="max-height: 40vh;" alt="...">
                                    </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControlsSesudah{{ $p->id }}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black;"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControlsSesudah{{ $p->id }}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black;"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
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

{{ $peminjamans->links() }}

<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
    var pmj = @json($peminjaman);
    pmj.forEach(function(p) {
        new TomSelect(`#select-barang-${p.id}`, {
            plugins: {
                remove_button: {
                    title: 'Delete'
                , }
            }
        , });
    });

    document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
        dropdown.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const barangDropdown = document.getElementById('barangDropdown-' + id);
            const gambarSebelum = document.getElementById('gambarSebelum-' + id);
            const gambarSesudah = document.getElementById('gambarSesudah-' + id);

            // Tampilkan dropdown barang hanya jika status "Diterima" dipilih
            if (this.value === 'Diterima') {
                gambarSebelum.style.display = 'block';
                barangDropdown.style.display = 'block';
            } else {
                gambarSebelum.style.display = 'none';
                barangDropdown.style.display = 'none';
            }

            if (this.value === 'Selesai') {
                gambarSesudah.style.display = 'block';
            } else {
                gambarSesudah.style.display = 'none';
            }
        });
    });

    // Menampilkan dropdown barang jika status awal adalah "Diterima"
    document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
        const id = dropdown.getAttribute('data-id');
        const barangDropdown = document.getElementById('barangDropdown-' + id);

        if (dropdown.value === 'Diterima') {
            barangDropdown.style.display = 'block';
        }
    });

</script>

@endsection
