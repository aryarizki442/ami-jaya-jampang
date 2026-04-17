<style>
    .footer {
        background: linear-gradient(90deg, #0D3523, #1F7D53);
        color: #fff;
        font-size: 14px;
    }

    .footer-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.4);
        margin-bottom: 25px;
    }

    .footer-contact {
        text-align: right;
        font-size: 14px;
    }

    .footer-title {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .footer-text {
        line-height: 1.7;
    }

    .footer-social {
        display: flex;
        gap: 12px;
    }

    .footer-social a {
        width: 36px;
        height: 36px;
        background: #F4E869;
        color: #1F7D53;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        font-size: 22px;
        text-decoration: none;
    }

    .footer-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .footer-list li {
        margin-bottom: 6px;
    }

    .footer-list li::before {
        content: "• ";
    }

    .footer-map {
        width: 100%;
        border-radius: 3px;
    }

    .footer-address {
        margin-top: 10px;
        font-size: 13px;
    }

    .footer-bottom {
        border-top: 3px solid #F4E869;
        padding: 10px 0;
        color: #F4E869;
        font-size: 14px;
    }

    .footer-map iframe {
        width: 100%;
        height: 120px;
    }

    .copyright-icon {
        font-size: 18px;
    }

    /* Desktop */
    @media (min-width: 992px) {
        .footer-social {
            margin-top: -70px;
        }
    }

    /* Mobile */
    @media (max-width: 991px) {
        .footer-social {
            justify-content: center;
        }
    }
</style>

<footer class="footer">

    <div class="container pt-4">

        <!-- Top -->
        <div class="footer-top">

            <div class="footer-logo d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo/logo-putih.png') }}" width="150">
            </div>

            <div class="footer-contact">
                <div>0812-1122-3344</div>
                <div>amijayajampang@gmail.com</div>
            </div>

        </div>


        <div class="row">

            <!-- Tentang Kami -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">Tentang kami</h5>
                <p class="footer-text">
                    Ami Jaya Jampang adalah toko online yang menjual beras berkualitas untuk kebutuhan sehari-hari.
                    Kami berkomitmen memberikan produk yang baik dengan pelayanan yang cepat dan terpercaya.
                </p>

                {{-- <div class="footer-social">
                    <a href="#"><span class="iconify" data-icon="mdi:instagram"></span></a>
                    <a href="#"><span class="iconify" data-icon="mdi:facebook"></span></a>
                    <a href="#"><span class="iconify" data-icon="mdi:youtube"></span></a>
                    <a href="#"><span class="iconify" data-icon="prime:twitter"></span></a>
                </div> --}}
            </div>


            <!-- Produk Kami -->
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <h5 class="footer-title">Produk Kami</h5>

                <div class="d-flex justify-content-center">
                    <ul class="footer-list text-start">
                        <li>Beras Putih Medium</li>
                        <li>Beras Putih Premium</li>
                        <li>Beras Ketan</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <h5 class="footer-title">Waktu Operasional Toko</h5>

                <div class="d-flex justify-content-center">
                    <div class="text-start">
                        <p class="mb-1">Senin - Kamis : <span class="fw-bold">17.30-16.30 WIB </span></p>
                        <p class="mb-1">Jum’at : <span style="color:#EF4444;" class="fw-bold">Libur</span></p>
                        <p class="mb-0">Sabtu - Minggu : <span class="fw-bold">17.30-16.30 WIB</span> </p>

                    </div>
                </div>
            </div>


            <!-- Alamat -->
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <h5 class="footer-title">Alamat Kami</h5>

                <a href="https://maps.app.goo.gl/ej9AvZBymzNPT3fC6" target="_blank">
                    <iframe src="https://www.google.com/maps?q=-6.45,106.70&z=11&output=embed" width="50%"
                        height="140" loading="lazy">
                    </iframe>
                </a>

                <div class="footer-address">
                    Jl. Raya Parung, Desa Jampang, <br>
                    Kec. Kemang Kabupaten bogor
                </div>
            </div>

        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer-social">
                <a href="#"><span class="iconify" data-icon="mdi:instagram"></span></a>
                <a href="#"><span class="iconify" data-icon="mdi:facebook"></span></a>
                <a href="#"><span class="iconify" data-icon="mdi:youtube"></span></a>
                <a href="#"><span class="iconify" data-icon="prime:twitter"></span></a>
            </div>
        </div>
    </div>


    <div class="footer-bottom">
        <div class="container">
            <span class="copyright-icon">©</span> Ami Jaya Jampang 2026. Hak Cipta Dilindungi
        </div>
    </div>

</footer>
