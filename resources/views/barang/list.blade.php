@extends('layouts.app')

@section('title', 'List Barang')

@section('contents')
    <div class="d-flex align-items-center justify-content-between mb-3">
        {{-- <form action="{{ route('barang.index') }}" method="GET" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-warning">Search</button>
        </form> --}}
        <div>
            <a href="{{ route('barang.printAll') }}" class="btn btn-info mr-1">Print Laporan</a>
        </div>

        <div class="d-flex">
            {{-- modal create barang --}}
            <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#createModal">
                Tambah Data Barang </button>
            {{-- print data barang to excel --}}
            <form id="print-selected-form" action="{{ route('barang.printSelected') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="selected_ids" id="selected-ids" value="">
                <button type="submit" class="btn btn-primary">Print Data Terpilih</button>
            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @method('POST')
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Tambah Data Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control"
                                    value="{{ old('nama_barang') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" name="gambar" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="tgl_masuk">Tanggal Masuk</label>
                                <input type="date" name="tgl_masuk" class="form-control" value="{{ old('tgl_masuk') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="kondisi">Kondisi</label>
                                <select name="kondisi" class="form-control" required>
                                    <option value="bagus" {{ old('kondisi') == 'bagus' ? 'selected' : '' }}>Bagus</option>
                                    <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kategori_barang_id">Kategori</label>
                                <select name="kategori_barang_id" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategori as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kategori_barang_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control"
                                    value="{{ old('kode_barang') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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

    <table class="table table-hover table-responsive" id="myTable">
        <thead class="table-warning">
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>No</th>
                <th style="max-width: 70px; white-space: normal; word-wrap: break-word;">Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Gambar</th>
                <th>Kondisi</th>
                <th>Tanggal Masuk</th>
                <th>QR Code</th> <!-- Add a column for QR Code -->
                <th>Ketersediaan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($barang as $b)
                    <tr>
                        <td><input type="checkbox" name="selected[]" value="{{ $b->id }}"></td>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle" style="max-width: 70px; white-space: normal; word-wrap: break-word;">{{ $b->kode_barang }}</td>
                        <td class="align-middle">{{ $b->nama_barang }}</td>
                        <td class="align-middle">{{ $b->kategoriBarang->nama_kategori }}</td>
                        <td class="align-middle">
                            @if ($b->image)
                                <img src="{{ asset('storage/' . $b->image) }}" alt="Gambar Barang"
                                    style="width: 100px;">
                            @else
                                No image
                            @endif
                        </td>
                        <td class="align-middle">{{ ucfirst($b->kondisi) }}</td>
                        <td class="align-middle">{{ date('d/m/Y', strtotime($b->tgl_masuk)) }}</td>
                        <td class="align-middle">
                            @if ($b->qr_code)
                                <img src="{{ asset('storage/' . $b->qr_code) }}" alt="QR Code" style="width: 100px;">
                            @else
                                No QR Code
                            @endif
                        </td>
                        <td class="align-middle">
                            @if ($b->status == 'Tersedia')
                                <span class="badge badge-success">{{ $b->status }}</span>
                            @else
                                <span class="badge badge-danger">{{ $b->status }}</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('barang.show', $b->id) }}" class="btn btn-secondary">Detail</a>
                                {{-- modal edit --}}
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#editModal{{ $b->id }}">
                                    Edit </button>
                                {{-- modal delete --}}
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#deleteModal{{ $b->id }}">
                                    Delete </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal edit -->
                    <div class="modal fade editModal" id="editModal{{ $b->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel{{ $b->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('barang.update', $b->id) }}" method="POST"
                                    enctype="multipart/form-data" class="mb-3">
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $b->id }}">Edit Data Barang
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" name="nama_barang" class="form-control"
                                                value="{{ $b->nama_barang }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="gambar">Gambar</label>
                                            <input type="file" name="gambar" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_masuk">Tanggal Masuk</label>
                                            <input type="date" name="tgl_masuk" class="form-control"
                                                value="{{ $b->tgl_masuk }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kondisi">Kondisi</label>
                                            <select name="kondisi" class="form-control" required>
                                                <option value="bagus" {{ $b->kondisi == 'bagus' ? 'selected' : '' }}>
                                                    Bagus
                                                </option>
                                                <option value="rusak" {{ $b->kondisi == 'rusak' ? 'selected' : '' }}>
                                                    Rusak
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="kategori_barang_id">Kategori</label>
                                            <select name="kategori_barang_id" class="form-control">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $k)
                                                    <option value="{{ $k->id }}"
                                                        {{ $b->kategori_barang_id == $k->id ? 'selected' : '' }}>
                                                        {{ $k->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- keterangan --}}
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea class="form-control" name="keterangan" id="keterangan" rows="3">{{ $b->keterangan }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="kode_barang">Kode Barang</label>
                                            <input type="text" name="kode_barang" class="form-control"
                                                value="{{ $b->kode_barang }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- modal delete --}}
                    <div class="modal fade deleteModal" id="deleteModal{{ $b->id }}" tabindex="-1"
                        role="dialog" aria-labelledby="deleteModalLabel{{ $b->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $b->id }}">Delete Data
                                            Barang
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data barang ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
        </tbody>
    </table>

    <script>
        new DataTable('#myTable', {
        stateSave: true
    , });
    </script>

    <script>
        document.getElementById('select-all').addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('input[name="selected[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
        });

        document.getElementById('print-selected-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const selectedIds = Array.from(document.querySelectorAll('input[name="selected[]"]:checked'))
                .map(checkbox => checkbox.value);

            if (selectedIds.length === 0) {
                alert('Pilih data yang ingin dicetak.');
                return;
            }

            document.getElementById('selected-ids').value = JSON.stringify(selectedIds);
            this.submit();
        });
    </script>

    <script>
        // generate kode barang otomatis 2 huruf pertama dari kategori barang kapital + nama barang kapital + tgl masuk (DDMMYY)
        const nama_barang = document.querySelector('input[name=nama_barang]');
        const kode_barang = document.querySelector('input[name=kode_barang]');
        const kategori_barang = document.querySelector('select[name=kategori_barang_id]');
        const tgl_masuk = document.querySelector('input[name=tgl_masuk]');
        var barangId = {{ $barang_id }};

        const generateKodeBarang = () => {
            if (nama_barang.value && kategori_barang.value && tgl_masuk.value) {
                const kategoriKode = kategori_barang.options[kategori_barang.selectedIndex].text.slice(0, 2)
                    .toUpperCase();
                const namaKode = nama_barang.value.replaceAll(' ', '').toUpperCase();

                // Format tanggal masuk menjadi dmy
                const tgl = new Date(tgl_masuk.value);
                const tglKode =
                    `${tgl.getDate().toString().padStart(2, '0')}${(tgl.getMonth() + 1).toString().padStart(2, '0')}${tgl.getFullYear().toString().slice(-2)}`;

                // Gabungkan menjadi kode lengkap
                const kode = `${kategoriKode}${namaKode}-${tglKode}-${barangId}`;
                kode_barang.value = kode;
            }
        }

        // Event listeners
        nama_barang.addEventListener('input', generateKodeBarang);
        kategori_barang.addEventListener('change', generateKodeBarang);
        tgl_masuk.addEventListener('input', generateKodeBarang);
        generateKodeBarang();
    </script>

@endsection
