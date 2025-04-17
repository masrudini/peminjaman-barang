<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('PUPR.ico') }}" />
    <!-- Google reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Jadwal Ruangan</title>
    <style>
        /* Untuk browser berbasis WebKit (Chrome, Safari, Edge) */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>
    <center>
        <div class="col-md-10 d-flex justify-content-between mt-5 align-self-center">
            <p style="color: #000000"><b>PRANALA (Pengelola Ruang, Asset, dan Layanan Jaringan)</b></p>
            <div>
                <a href="{{ route('peminjaman.create') }}" class="text-dark">Peminjaman
                    Barang</a>
                <a href="{{ route('jaringan.index') }}" class="mx-3 text-dark">Kendala
                    Jaringan</a>
                <a href="{{ route('peminjaman.ruangan') }}" class="text-primary font-weight-bold">Peminjaman
                    Ruangan</a>
            </div>
        </div>
    </center>
    <div class="content py-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('errors'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="container d-md-flex justify-content-between w-100">
            <div class="mb-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPinjamRuangan"
                    style="font-size: 1rem">
                    <i class="bi bi-plus-circle"></i> Pinjam
                </button>
                {{-- dropdown ruangan --}}
                <div class="dropdown d-inline-block">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ruangan
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach ($ruangan as $item)
                            {{-- tombol modal detail ruangan --}}
                            <button class="dropdown-item" data-toggle="modal"
                                data-target="#modalDetailRuangan{{ $item->id }}">
                                {{ $item->nama }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <form action="{{ route('peminjaman.ruangan') }}" method="GET" class="d-flex">
                <div class="form-group">
                    <input type="date" name="search" id="search" class="form-control"
                        value="{{ request('search') ?? '' }}">
                </div>
                <button type="submit" class="btn btn-secondary ml-2">Search</button>
            </form>
            {{-- modal pinjam ruangan --}}
            <div class="modal fade" id="modalPinjamRuangan" tabindex="-1" role="dialog"
                aria-labelledby="modalPinjamRuanganLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('peminjaman.ruangan.store') }}" method="POST">
                            @method('POST')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPinjamRuanganLabel">Pinjam Ruangan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="ruangan_id">Ruangan</label>
                                    <select name="ruangan_id" id="ruangan_id" class="form-control" required>
                                        <option value="">Pilih Ruangan</option>
                                        @foreach ($ruangan_tersedia as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="no_hp">No HP</label>
                                    <input type="number" name="no_hp" id="no_hp" class="form-control" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="kegiatan">Kegiatan</label>
                                    <input name="kegiatan" id="kegiatan" class="form-control" required></input>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-primary my-2"
                                        id="tambahTanggal">Tambah</button>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 form-group">
                                        <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                        <input type="date" name="tanggal_pinjam[]" id="tanggal_pinjam"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="jam_mulai">Jam Mulai</label>
                                        <input type="time" name="jam_mulai[]" id="jam_mulai" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="jam_selesai">Jam Selesai</label>
                                        <input type="time" name="jam_selesai[]" id="jam_selesai"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div id="jadwal">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Pinjam</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- modal detail ruangan --}}
            @foreach ($ruangan as $item)
                <div class="modal fade" id="modalDetailRuangan{{ $item->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="modalDetailRuanganLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p><b>Nama Ruangan:</b> {{ $item->nama }}</p>
                                <p><b>Status:</b> {{ $item->status }}</p>
                                <p><b>Deskripsi:</b> {{ $item->deskripsi }}</p>
                                <div class="d-flex justify-content-center">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt=""
                                        class="img-fluid" style="max-height: 50vh">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 order-2 order-md-1">
                    <p><img src="{{ asset('images/people.png') }}" alt="Image" class="img-fluid"
                            style="margin-right: 20px;"></p>
                </div>
                <div class="col-md-9 order-1 order-md-2">
                    {{-- carousel tabel jadwal --}}
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($ruangan as $item)
                                <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                    <div class="card" style="max-height: 50vh;">
                                        <div class="card-header d-flex justify-content-center bg-white">
                                            <h5>
                                                <b>Jadwal {{ $item->nama }}
                                                    {{ request('search') != null ? date('d-m-Y', strtotime(request('search'))) : date('d-m-Y') }}</b>
                                            </h5>
                                        </div>
                                        <div class="card-body p-0" style="overflow-y: auto; overflow-x: auto;">
                                            <table class="table table-responsive table-hover w-100 d-block d-md-table">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Kegiatan</th>
                                                        <th>Tanggal</th>
                                                        <th>Jam Mulai</th>
                                                        <th>Jam Selesai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($jadwal->where('ruangan_id', $item->id)->isEmpty())
                                                        <tr>
                                                            <td colspan="5" class="text-center">Tidak ada
                                                                jadwal
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($jadwal->where('ruangan_id', $item->id) as $p)
                                                            <tr>
                                                                <td>{{ $p->nama }}</td>
                                                                <td>{{ $p->kegiatan }}</td>
                                                                <td>{{ $p->tanggal_pinjam }}</td>
                                                                <td>{{ $p->jam_mulai }}</td>
                                                                <td>{{ $p->jam_selesai }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <ol class="carousel-indicators" style="position: static">
                            @foreach ($ruangan as $item)
                                <li data-target="#carouselExampleControls" data-slide-to="{{ $loop->index }}"
                                    class="{{ $loop->iteration == 1 ? 'active' : '' }}"
                                    style="background-color: #000000"></li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 5000);

            document.getElementById('tambahTanggal').addEventListener('click', function() {
                var newField = document.createElement('div');
                newField.classList.add('row', 'mb-3');
                newField.innerHTML = `
                    <div class="col-md-4 form-group">
                        <label for="tanggal_pinjam">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam[]" id="tanggal_pinjam"
                            class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai[]" id="jam_mulai" class="form-control"
                            required>
                    </div>
                    <div class="col-md-3 form-group">  
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai[]" id="jam_selesai"
                            class="form-control" required>
                    </div>
                     <div class="col-md-1 form-group d-flex align-items-end"> 
                        <button type="button" class="btn btn-sm btn-danger remove-tanggal">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>`;
                document.getElementById('jadwal').appendChild(newField);
                newField.querySelector('.remove-tanggal').addEventListener('click', function() {
                    newField.remove();
                });
            });
        });
    </script>
</body>

</html>
