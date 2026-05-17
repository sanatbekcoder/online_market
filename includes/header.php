<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="uz">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tech Store | </title> 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {

            font-family: 'Poppins', sans-serif;

            background:
                linear-gradient(180deg,
                    #f8fbff,
                    #edf4ff);

        }


        /* NAVBAR */

        .custom-nav {

            background:
                rgba(255,
                    255,
                    255,
                    0.95);

            backdrop-filter: blur(15px);

            padding: 15px 0;

            box-shadow:
                0 5px 30px rgba(37,
                    99,
                    235,
                    0.08);

            position: sticky;

            inset-block-start: 0;

            z-index: 1000;

        }


        .logo {

            font-size: 28px;

            font-weight: 700;

            color: #2563eb !important;

        }


        .navbar-toggler {

            border: none;

            box-shadow: none;

        }


        .nav-btn {

            border-radius: 14px;

            padding: 10px 18px;

            font-size: 14px;

            font-weight: 600;

            border: none;

            color: #2563eb;

            transition: .3s;

        }


        .nav-btn:hover {

            background: #dbeafe;

            transform: translateY(-2px);

        }


        /* AKTIV SAHIFA */

        .nav-active {

            background: #2563eb !important;

            color: white !important;

            box-shadow:
                0 10px 25px rgba(37,
                    99,
                    235,
                    0.20);

        }


        .profile-mini {

            background:
                linear-gradient(135deg,
                    #2563eb,
                    #3b82f6);

            inline-size: 42px;

            block-size: 42px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            font-weight: bold;

            margin-inline-start: 15px;

            box-shadow:
                0 8px 20px rgba(37,
                    99,
                    235,
                    0.25);

        }


        .main-container {

            padding-block-start: 25px;

            padding-block-end: 50px;

        }


        @media(max-inline-size:768px) {

            .navbar-collapse {

                padding-block-start: 15px;

            }

            .nav-btn {

                inline-size: 100%;

                margin-block-end: 10px;

            }

            .profile-mini {

                margin-inline-start: 0;

                margin-block-start: 10px;

            }

        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg custom-nav">

        <div class="container">

            <a class="navbar-brand logo" href="/market">

                🚀 TechStore

            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">

                ☰

            </button>


            <div class="collapse navbar-collapse" id="menu">

                <div class="ms-auto d-lg-flex align-items-center gap-2">


                    <a class="nav-btn <?= ($current == "/market/" || $current == "/market") ? "nav-active" : "" ?>"
                        href="/market">

                        Bosh sahifa

                    </a>


                    <a class="nav-btn <?= strpos($current, 'products') !== false ? "nav-active" : "" ?>"
                        href="/market/products/index.php">

                        Mahsulotlar

                    </a>


                    <a class="nav-btn <?= strpos($current, 'cart') !== false ? "nav-active" : "" ?>"
                        href="/market/cart/cart.php">

                        🛒 Savatcha

                    </a>


                    <?php if (isset($_SESSION['user_id'])): ?>


                        <a class="nav-btn <?= strpos($current, 'orders') !== false ? "nav-active" : "" ?>"
                            href="/market/orders/my_orders.php">

                            📦 Buyurtmalar

                        </a>


                        <a class="nav-btn <?= strpos($current, 'profile') !== false ? "nav-active" : "" ?>"
                            href="/market/accounts/profile.php">

                            👤 Profil

                        </a>


                        <?php if (
                            isset($_SESSION['role']) &&
                            $_SESSION['role'] == "admin"
                        ): ?>

                            <a class="nav-btn <?= strpos($current, 'admin') !== false ? "nav-active" : "" ?>"
                                href="/market/admin/dashboard.php">

                                ⚙ Admin

                            </a>

                        <?php endif; ?>


                        <a class="btn btn-danger nav-btn" href="/market/accounts/logout.php">

                            Chiqish

                        </a>


                        <div class="profile-mini">

                            <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>

                        </div>

                    <?php else: ?>


                        <a class="nav-btn <?= strpos($current, 'login') !== false ? "nav-active" : "" ?>"
                            href="/market/accounts/login.php">

                            Kirish

                        </a>


                        <a class="nav-btn <?= strpos($current, 'register') !== false ? "nav-active" : "" ?>"
                            href="/market/accounts/register.php">

                            Ro'yxatdan o'tish

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </nav>

    <div class="container main-container">