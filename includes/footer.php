</div>

<footer class="mt-5">

    <div class="container">

        <div class="footer-box">

            <div class="row gy-4 align-items-center">

                <div class="col-lg-5">

                    <h3 class="footer-logo">

                        🚀 TechStore

                    </h3>

                    <p class="footer-text">

                        Eng so‘nggi texnologiyalar, qulay xarid va
                        zamonaviy onlayn savdo tajribasi bir joyda.

                    </p>

                </div>


                <div class="col-lg-4">

                    <h5 class="footer-title">

                        Navigatsiya

                    </h5>

                    <div class="footer-links">

                        <a href="/market" class="footer-link">

                            🏠 Bosh sahifa

                        </a>

                        <a href="/market/products/index.php" class="footer-link">

                            🛍 Mahsulotlar

                        </a>

                        <a href="/market/cart/cart.php" class="footer-link">

                            🛒 Savatcha

                        </a>

                    </div>

                </div>


                <div class="col-lg-3 text-lg-end">

                    <div class="mini-badge">

                        ⚡ Online 24/7

                    </div>

                    <p class="copyright-mini">

                        Tezkor xizmat va xavfsiz xarid

                    </p>

                </div>

            </div>


            <hr class="footer-line">


            <div class="text-center copyright">

                © <?= date("Y") ?>

                TechStore |

            </div>

        </div>

    </div>

</footer>



<style>
    footer {

        padding-block-end: 30px;

        margin-block-start: 50px;

    }


    /* BOX */

    .footer-box {

        background:
            linear-gradient(135deg,
                #ffffff,
                #eff6ff);

        padding: 40px;

        border-radius: 35px;

        box-shadow:
            0 20px 45px rgba(37,
                99,
                235,
                0.08);

        position: relative;

        overflow: hidden;

    }


    /* DEKOR */

    .footer-box::before {

        content: "";

        position: absolute;

        inline-size: 260px;
        block-size: 260px;

        background:
            rgba(59,
                130,
                246,
                0.07);

        border-radius: 50%;

        inset-block-start: -120px;
        inset-inline-end: -100px;

    }


    /* LOGO */

    .footer-logo {

        font-size: 30px;

        font-weight: 700;

        color: #2563eb;

        margin-block-end: 10px;

    }


    .footer-text {

        color: #64748b;

        line-height: 1.8;

    }


    /* TITLE */

    .footer-title {

        font-weight: 700;

        margin-block-end: 15px;

        color: #1e293b;

    }


    /* LINKS */

    .footer-links {

        display: flex;

        flex-direction: column;

        gap: 12px;

    }


    .footer-link {

        text-decoration: none;

        color: #475569;

        font-weight: 600;

        transition: .3s;

    }


    .footer-link:hover {

        color: #2563eb;

        transform:
            translateX(5px);

    }


    /* MINI */

    .mini-badge {

        display: inline-block;

        padding: 10px 18px;

        border-radius: 30px;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        color: white;

        font-weight: 600;

        box-shadow:
            0 10px 25px rgba(37,
                99,
                235,
                0.18);

    }


    .copyright-mini {

        margin-block-start: 15px;

        color: #64748b;

    }


    .footer-line {

        margin: 30px 0;

        opacity: .15;

    }


    .copyright {

        color: #64748b;

        font-size: 14px;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .footer-box {

            padding: 25px;

            text-align: center;

        }

        .footer-links {

            align-items: center;

        }

        .text-lg-end {

            text-align: center !important;

            margin-block-start: 10px;

        }

    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>