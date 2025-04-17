<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('PUPR.ico') }}" />
    <!-- LINEARICONS -->
    <link rel="stylesheet" href="{{ asset('a/fonts/linearicons/style.css') }}">
    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{ asset('a/css/style.css') }}">

    <!-- Tambahkan CSS untuk styling notifikasi kustom -->
    <style>
        #customAlert {
            display: none;
            position: fixed;
            top: 20px;
            /* Letakkan di atas dengan jarak 20px dari atas */
            left: 50%;
            transform: translateX(-50%);
            /* Hanya terapkan translasi horizontal untuk menempatkan di tengah horizontal */
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            z-index: 1000;
        }

        #customAlert p {
            margin: 0;
            color: #333;
            font-size: 16px;
        }

        #closeAlert {
            margin-top: 15px;
            padding: 8px 20px;
            border: none;
            background-color: #ff0000;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <!-- Notifikasi kustom -->
    <div id="customAlert">
        <p>Username atau Password salah</p>
        <button id="closeAlert">OK</button>
    </div>

    <div class="wrapper">
        <div class="inner">
            <img src="{{ asset('a/images/image-1.png') }}" alt="" class="image-1">
            <form action="{{ route('login.action') }}" method="POST">
                @csrf
                <h3>Login</h3>

                <div class="form-holder">
                    <span class="lnr lnr-user"></span>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <button type="submit">
                    <span>Login</span>

                </button>
                <br>
                <div class="g-recaptcha" data-sitekey="6LcBxGMqAAAAALGgCKDoG8T96mwfYaN1fCXcXbgg"></div>
            </form>

            <img src="{{ asset('a/images/image-2.png') }}" alt="" class="image-2">
        </div>
    </div>

    <script src="{{ asset('a/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('a/js/main.js') }}"></script>

    <!-- Script untuk memunculkan notifikasi kustom -->
    <script>
        // Fungsi untuk menampilkan notifikasi
        function showCustomAlert(message) {
            var customAlert = document.getElementById('customAlert');
            customAlert.querySelector('p').innerText = message;
            customAlert.style.display = 'block';
        }

        // Jika ada error dari session, tampilkan notifikasi
        @if (session('error'))
            showCustomAlert("{{ session('error') }}");
        @endif

        // Menutup notifikasi ketika tombol OK ditekan
        document.getElementById('closeAlert').addEventListener('click', function() {
            document.getElementById('customAlert').style.display = 'none';
        });
    </script>
</body>

</html>
