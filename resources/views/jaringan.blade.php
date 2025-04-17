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
        <div class="col-md-10 d-flex justify-content-between mt-5">
            <p style="color: #000000"><b>PRANALA (Pengelola Ruang, Asset, dan Layanan Jaringan)</b></p>
            <div>
                <a href="{{ route('peminjaman.create') }}" class="text-dark">Peminjaman
                    Barang</a>
                <a href="{{ route('jaringan.index') }}" class="mx-3 text-primary font-weight-bold">Kendala
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
                            <img src="{{ asset('images/people.png') }}" alt="Image" class="img-fluid">
                        </div>
                        <div class="col-md-9 order-md-2 order-1">
                            <h3 class="heading mb-4">Form Laporan Gangguan Jaringan</h3>
                            <p>Silakan lengkapi form berikut untuk melakukan pengaduan.</p>

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

                            <form id="form-peminjaman" action="{{ route('jaringan.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="text-black">Nama Pelapor<sup>*</sup></label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Kendala Jaringan" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="text-black">Kendala Jaringan<sup>*</sup></label>
                                        <input type="text" class="form-control" name="kendala_jaringan"
                                            id="kendala_jaringan" placeholder="Kendala Jaringan" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="text-black">Ruangan<sup>*</sup></label>
                                        <input type="text" class="form-control" name="ruangan" id="ruangan"
                                            placeholder="Ruangan" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="text-black">Foto <span
                                                class="text-muted">(Optional)</span></label>
                                        <input class="form-control" type="file" accept="image/*" name="foto"
                                            id="foto">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="g-recaptcha" data-sitekey="6LcBxGMqAAAAALGgCKDoG8T96mwfYaN1fCXcXbgg"
                                            data-callback="onCaptchaVerify"></div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary mt-2">Kirim</button>
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
        });
    </script>
</body>

</html>
