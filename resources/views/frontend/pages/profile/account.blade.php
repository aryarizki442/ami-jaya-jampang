@extends('app')

@section('title', 'Keranjang Belanja')

@section('content')

    <style>
        body {
            background-color: #f5f6f8;
        }



        .user-info {
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 10px;
        }

        .username-profile {
            font-size: 15px;
        }

        .profile-wrapper {
            display: flex;
            gap: 30px;
        }

        .profile-sidebar {
            width: 250px;
            border-radius: 8px;
            padding: 20px;

        }

        .profile-sidebar .user-info img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .profile-sidebar ul li {
            padding: 8px 0;
            font-size: 14px;
            color: #555;
            cursor: pointer;
        }

        .profile-sidebar ul li.active {
            color: #198754;
            font-weight: 500;
        }


        .btn-save {
            background-color: #198754;
            color: #fff;
            padding: 6px 25px;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-save:hover {
            background-color: #157347;
        }

        .upload-btn {
            background-color: #198754;
            color: #fff;
            font-size: 13px;
            padding: 5px 15px;
            border-radius: 6px;
        }

        .upload-btn:hover {
            background-color: #157347;
        }

        .upload-info {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }

        .active a {
            color: #1F7D53 !important;
            font-weight: 500;
        }

        /* ======================
                                                                                                                                                               RESPONSIVE PROFILE
                                                                                                                                                            ====================== */

        /* tablet */
        @media (max-width: 992px) {

            .profile-sidebar {
                width: 200px;
                padding: 15px;
            }

            .profile-sidebar .user-info img {
                width: 60px;
                height: 60px;
            }

        }


        /* mobile */
        @media (max-width: 768px) {

            .profile-wrapper {
                flex-direction: column;
                gap: 20px;
            }

            /* sidebar jadi menu atas */
            .profile-sidebar {
                width: 100%;
                padding: 15px;
            }

            .profile-sidebar ul {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
                margin-top: 15px;
            }

            .profile-sidebar ul li {
                padding: 5px 0;
            }

            /* sub menu akun */
            .profile-sidebar ul li ul {
                display: flex;
                gap: 15px;
                padding-left: 0 !important;
            }

        }


        /* mobile kecil */
        @media (max-width: 576px) {

            .profile-sidebar ul {
                flex-direction: column;
                gap: 10px;
            }

            .profile-sidebar ul li ul {
                flex-direction: column;
                gap: 8px;
            }

            .account-content {
                padding: 20px;
            }

        }
    </style>

    <div class="container py-0">
        <div class="profile-wrapper">

            {{-- SIDEBAR --}}
            <div class="profile-sidebar">
                <div class="user-info d-flex align-items-center gap-2">
                    <img src="{{ asset('images/home/category/beras-medium.png') }}" alt="">
                    <p class="mb-0 fw-semibold username-profile">
                        {{ Auth::user()->name ?? 'MALIK HASAN PELUPPESY' }}
                    </p>
                </div>
                <ul class="list-unstyled">

                    <li class="mt-3">

                        <div class="fw-semibold text-dark d-flex align-items-center gap-2" data-bs-toggle="collapse"
                            data-bs-target="#akunMenu" style="cursor:pointer;">

                            <iconify-icon icon="mdi:user-outline" width="24" style="color:#4481B5;"></iconify-icon>
                            Akun Saya

                        </div>

                        <ul id="akunMenu"
                            class="collapse list-unstyled mt-2 {{ request()->is('profile') || request()->is('address') ? 'show' : '' }}"
                            style="padding-left:2rem;">

                            <li class="{{ request()->is('profile') ? 'active' : '' }}">
                                <a href="{{ url('/profile') }}" class="text-decoration-none text-dark">
                                    Profil
                                </a>
                            </li>

                            <li class="{{ request()->is('address') ? 'active' : '' }}">
                                <a href="{{ url('/address') }}" class="text-decoration-none text-dark">
                                    Alamat
                                </a>
                            </li>

                        </ul>

                    </li>

                    {{-- PESANAN --}}
                    <li class="mt-3">
                        <div class="fw-semibold text-dark d-flex align-items-center gap-2" data-bs-toggle="collapse"
                            data-bs-target="#pesananMenu" style="cursor:pointer;">
                            <iconify-icon icon="icon-park-outline:notes" width="24"
                                style="color:#1F7D53;"></iconify-icon>
                            <a href="{{ url('/pesanan') }}" class="text-decoration-none text-dark">
                                Pesanan Saya
                            </a>
                        </div>

                        <ul id="pesananMenu"
                            class="collapse list-unstyled mt-2
                            {{ request()->is('order-all') ||
                            request()->is('order-sent') ||
                            request()->is('order-done') ||
                            request()->is('order-canceled')
                                ? 'show'
                                : '' }}"
                            style="padding-left:2rem;">

                            <li class="{{ request()->is('order-all') ? 'active' : '' }}">
                                <a href="{{ url('/order-all') }}" class="text-decoration-none text-dark">
                                    Semua Pesanan
                                </a>
                            </li>

                            <li class="{{ request()->is('order-sent') ? 'active' : '' }}">
                                <a href="{{ url('/order-sent') }}" class="text-decoration-none text-dark">
                                    Pesanan Dikirim
                                </a>
                            </li>
                            <li class="{{ request()->is('order-done') ? 'active' : '' }}">
                                <a href="{{ url('/order-done') }}" class="text-decoration-none text-dark">
                                    Pesanan Selesai
                                </a>
                            </li>
                            <li class="{{ request()->is('order-canceled') ? 'active' : '' }}">
                                <a href="{{ url('/order-canceled') }}" class="text-decoration-none text-dark">
                                    Pesanan Dibatalkan
                                </a>
                            </li>


                        </ul>
                    </li>

                    {{-- NOTIFIKASI --}}
                    <li class="mt-3 fw-semibold text-dark d-flex align-items-center gap-2">
                        <iconify-icon icon="hugeicons:notification-01" width="24" style="color:#1F7D53;"></iconify-icon>
                        <a href="{{ url('/notifikasi') }}" class="text-decoration-none text-dark">
                            Notifikasi
                        </a>
                    </li>

                </ul>
            </div>

            {{-- CONTENT DINAMIS --}}
            <div class="col-md-9 account-content">
                @yield('account-content')
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
@endsection
