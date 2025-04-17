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
    <title>Form Peminjaman Barang</title>
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
                <a href="{{ route('peminjaman.create') }}" class="text-primary font-weight-bold">Peminjaman
                    Barang</a>
                <a href="{{ route('jaringan.index') }}" class="mx-3 text-dark">Kendala
                    Jaringan</a>
                <a href="{{ route('peminjaman.ruangan') }}" class="text-dark">Peminjaman
                    Ruangan</a>
            </div>
        </div>
    </center>
    <div class="content py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3 order-md-1 order-2">
                            <p><img src="{{ asset('images/people.png') }}" alt="Image" class="img-fluid"
                                    style="margin-right: 20px;"></p>
                        </div>
                        <div class="col-md-9 order-md-2 order-1">
                            <h3 class="heading mb-4">Form Peminjaman Barang</h3>
                            <p>Silakan lengkapi form berikut untuk melakukan peminjaman barang.</p>

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

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <button type="button" class="btn btn-light" id="tambah-kategori"
                                        style="border: none;">
                                        <i class="bi bi-plus-circle" style="font-size: 1.5rem; color: #0d6efd;"> Tambah
                                            Barang </i>
                                    </button>
                                </div>
                            </div>

                            <form id="form-peminjaman" action="{{ route('peminjaman.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Nama" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Nomor HP</label>
                                        <input type="number" class="form-control" name="nomor_hp" id="nomor_hp"
                                            placeholder="Nomor HP" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Tanggal Peminjaman</label>
                                        <input type="date" class="form-control" name="waktu_peminjaman" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Tanggal Pengembalian</label>
                                        <input type="date" class="form-control" name="waktu_pengembalian" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Unit Kerja</label>
                                        <input type="text" class="form-control" name="unit_kerja" id="unit_kerja"
                                            placeholder="Unit Kerja" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Kegiatan</label>
                                        <input type="text" class="form-control" name="kegiatan" id="kegiatan"
                                            placeholder="Kegiatan" required>
                                    </div>
                                </div>

                                <div class="row" id="kategori-fields">
                                    <div class="col-md-6 form-group">
                                        <label>Kategori Barang</label>
                                        <select id="kategori_barang_id" name="kategori_barang_id[]" class="form-control"
                                            required>
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            @foreach ($kategori as $k)
                                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Jumlah</label>
                                        <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah"
                                            min="1" required>
                                    </div>
                                </div>
                                <div>
                                    <div class="g-recaptcha" data-sitekey="6LcBxGMqAAAAALGgCKDoG8T96mwfYaN1fCXcXbgg"
                                        data-callback="onCaptchaVerify"></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <canvas id="signature-pad" class="signature-pad" width="300"
                                            style="border:1px solid #000;"></canvas>
                                        <br>
                                        <button id="clear" type="button"
                                            class="btn btn-secondary mt-2">Clear</button>
                                        <input type="hidden" name="ttd_peminjam" id="ttd_peminjam" required>
                                        <input type="submit" value="Kirim" class="btn btn-primary mt-2">
                                        <span class="submitting"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> <!-- End Row -->
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

            var canvas = document.getElementById('signature-pad');
            var signaturePad = new SignaturePad(canvas);

            document.querySelector('form').addEventListener('submit', function(event) {
                if (signaturePad.isEmpty()) {
                    alert('Silakan tanda tangani terlebih dahulu.');
                    event.preventDefault();
                } else {
                    var dataURL = signaturePad.toDataURL();
                    document.getElementById('ttd_peminjam').value = dataURL;
                }
            });

            document.getElementById('clear').addEventListener('click', function() {
                signaturePad.clear();
            });

            document.getElementById('tambah-kategori').addEventListener('click', function() {
                var newField = document.createElement('div');
                newField.classList.add('d-flex', 'w-100');
                newField.innerHTML = `
                    <div class="col-md-6 form-group">
                        <select name="kategori_barang_id[]" class="form-control" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5 form-group">
                        <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" min="1" required>
                    </div>
                    <button type="button" class="btn btn-danger remove-kategori">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                document.getElementById('kategori-fields').appendChild(newField);
                newField.querySelector('.remove-kategori').addEventListener('click', function() {
                    newField.remove();
                });
            });
        });

        // reCAPTCHA validation
        // document.querySelector('form').addEventListener('submit', function(event) {
        //     var response = grecaptcha.getResponse();
        //     if (response.length === 0) {
        //         alert('Please complete the reCAPTCHA.');
        //         event.preventDefault(); // Prevent form submission
        //     }
        // });
    </script>
</body>

</html>
