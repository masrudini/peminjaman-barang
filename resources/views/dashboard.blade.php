@extends('layouts.app')

@section('contents')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card bg-succes text-white">
                    <div class="card-body">
                        <div class="row text-center">
                            <!-- Kotak pertama dengan icon Peminjaman -->
                            <div class="col-md-3">
                                <a href="/admin/peminjaman" class="text-decoration-none">
                                    <div class="card-custom p-3" style="background-color: #00008C;">
                                        <div class="icon-box">
                                            <i class="fas fa-archive fa-2x text-white"></i>
                                        </div>
                                        <div class="icon-text">
                                            <div class="text-white-50">Data</div>
                                            <div class="font-weight-bold text-white">Peminjaman</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Kotak kedua dengan icon Barang -->
                            <div class="col-md-3">
                                <a href="{{ route('barang.index') }}" class="text-decoration-none">
                                    <div class="card-custom p-3" style="background-color: #69ff3b;">
                                        <div class="icon-box">
                                            <i class="fas fa-boxes fa-2x text-white"></i>
                                        </div>
                                        <div class="icon-text">
                                            <div class="text-white-50">Data</div>
                                            <div class="font-weight-bold text-white">Barang</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ route('jaringan.admin') }}" class="text-decoration-none">
                                    <div class="card-custom p-3" style="background-color: #F44336;">
                                        <div class="icon-box">
                                            <i class="fas fa-wifi fa-2x text-white"></i>
                                        </div>
                                        <div class="icon-text">
                                            <div class="text-white-50">Jaringan</div>
                                            <div class="font-weight-bold text-white">Pengaduan Jaringan</div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Kotak ketiga dengan icon Profile -->
                            <div class="col-md-3">
                                <a href="/user" class="text-decoration-none">
                                    <div class="card-custom p-3" style="background-color: #2196F3;">
                                        <div class="icon-box">
                                            <i class="fas fa-user fa-2x text-white"></i>
                                        </div>
                                        <div class="icon-text">
                                            <div class="text-white-50">User</div>
                                            <div class="font-weight-bold text-white">Manajemen User</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
