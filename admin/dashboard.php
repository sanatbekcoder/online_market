<?php

session_start();

if (
    !isset($_SESSION['user_id'])
    ||
    $_SESSION['role'] != "admin"
) {

    header(
        "Location: ../accounts/login.php"
    );

    exit();

}

require_once "../config/database.php";


$userCount = $conn->query(
    "SELECT COUNT(*) FROM users"
)->fetchColumn();

$productCount = $conn->query(
    "SELECT COUNT(*) FROM products"
)->fetchColumn();

$orderCount = $conn->query(
    "SELECT COUNT(*) FROM orders"
)->fetchColumn();

$newOrders = $conn->query(

    "SELECT COUNT(*)

FROM orders

WHERE status='korib_chiqilyapdi'"

)->fetchColumn();


include "../includes/header.php";

?>

<style>
    .admin-page {
        padding: 20px 0;
    }


    /* GRID */

    .dashboard {

        display: grid;

        grid-template-columns:
            320px 1fr;

        gap: 25px;

    }


    /* SIDEBAR */

    .sidebar {

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        border-radius: 35px;

        padding: 35px;

        color: white;

        min-block-size: 700px;

        position: relative;

        overflow: hidden;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.20);

    }

    .sidebar::before {

        content: "";

        position: absolute;

        inline-size: 260px;
        block-size: 260px;

        border-radius: 50%;

        background:
            rgba(255,
                255,
                255,
                0.08);

        inset-block-start: -100px;
        inset-inline-end: -100px;

    }


    .admin-avatar {

        inline-size: 90px;
        block-size: 90px;

        border-radius: 50%;

        background: white;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 34px;

        font-weight: 700;

        color: #2563eb;

        margin-block-end: 20px;

    }


    .admin-name {

        font-size: 28px;

        font-weight: 700;

    }


    .admin-role {

        opacity: .85;

        margin-block-end: 35px;

    }


    .side-link {

        display: flex;

        align-items: center;

        gap: 15px;

        padding: 16px;

        margin-block-end: 12px;

        border-radius: 18px;

        text-decoration: none;

        color: white;

        transition: .3s;

        background:
            rgba(255,
                255,
                255,
                0.08);

    }

    .side-link:hover {

        background:
            rgba(255,
                255,
                255,
                0.18);

        color: white;

        transform:
            translateX(6px);

    }


    /* CONTENT */

    .content {

        display: flex;

        flex-direction: column;

        gap: 25px;

    }


    .top-card {

        background: white;

        padding: 35px;

        border-radius: 35px;

        box-shadow:
            0 10px 35px rgba(37,
                99,
                235,
                0.08);

    }


    .top-card h1 {

        font-weight: 700;

        margin-block-end: 10px;

    }


    .stats-grid {

        display: grid;

        grid-template-columns:
            repeat(4, 1fr);

        gap: 20px;

    }


    .stat-card {

        background: white;

        padding: 30px;

        border-radius: 30px;

        box-shadow:
            0 10px 30px rgba(0,
                0,
                0,
                0.06);

        transition: .35s;

    }


    .stat-card:hover {

        transform:
            translateY(-8px);

        box-shadow:
            0 15px 35px rgba(37,
                99,
                235,
                0.12);

    }


    .stat-icon {

        font-size: 35px;

        margin-block-end: 15px;

    }


    .stat-number {

        font-size: 36px;

        font-weight: 700;

        color: #2563eb;

    }


    /* QUICK */

    .quick-box {

        background: white;

        padding: 35px;

        border-radius: 35px;

        box-shadow:
            0 10px 30px rgba(37,
                99,
                235,
                0.08);

    }


    .quick-grid {

        display: grid;

        grid-template-columns:
            repeat(3, 1fr);

        gap: 20px;

        margin-block-start: 20px;

    }


    .quick-btn {

        padding: 25px;

        border-radius: 25px;

        text-align: center;

        text-decoration: none;

        background: #f8fbff;

        color: #1e293b;

        transition: .3s;

        font-weight: 700;

        border: 1px solid #e0ecff;

    }


    .quick-btn:hover {

        background: #2563eb;

        color: white;

        transform:
            translateY(-5px);

    }


    /* MOBILE */

    @media(max-inline-size:992px) {

        .dashboard {

            grid-template-columns: 1fr;

        }

        .sidebar {

            min-block-size: auto;

        }

        .stats-grid {

            grid-template-columns:
                repeat(2, 1fr);

        }

        .quick-grid {

            grid-template-columns: 1fr;

        }

    }


    @media(max-inline-size:768px) {

        .stats-grid {

            grid-template-columns: 1fr;

        }

    }
</style>



<div class="admin-page">

    <div class="dashboard">


        <div class="sidebar">

            <div class="admin-avatar">

                <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>

            </div>

            <div class="admin-name">

                <?= $_SESSION['user_name'] ?>

            </div>

            <div class="admin-role">

                ⚙ Administrator panel

            </div>


            <a href="dashboard.php" class="side-link">

                🏠 Dashboard

            </a>

            <a href="products.php" class="side-link">

                🛍 Mahsulotlar

            </a>

            <a href="users.php" class="side-link">

                👤 Foydalanuvchilar

            </a>

            <a href="orders.php" class="side-link">

                📦 Buyurtmalar

            </a>

        </div>



        <div class="content">


            <div class="top-card">

                <h1>

                    🚀 Boshqaruv markazi

                </h1>

                <p class="text-muted">

                    Market tizimidagi barcha ma'lumotlarni boshqarish paneli

                </p>

            </div>



            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-icon">

                        👤

                    </div>

                    <div>

                        Foydalanuvchilar

                    </div>

                    <div class="stat-number">

                        <?= number_format($userCount) ?>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">

                        🛍

                    </div>

                    <div>

                        Mahsulotlar

                    </div>

                    <div class="stat-number">

                        <?= number_format($productCount) ?>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">

                        📦

                    </div>

                    <div>

                        Buyurtmalar

                    </div>

                    <div class="stat-number">

                        <?= number_format($orderCount) ?>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">

                        🔔

                    </div>

                    <div>

                        Yangi buyurtma

                    </div>

                    <div class="stat-number">

                        <?= number_format($newOrders) ?>

                    </div>

                </div>

            </div>



            <div class="quick-box">

                <h3>

                    ⚡ Tezkor boshqaruv

                </h3>

                <div class="quick-grid">

                    <a href="products.php" class="quick-btn">

                        🛍 Mahsulotlar

                    </a>

                    <a href="users.php" class="quick-btn">

                        👤 Foydalanuvchilar

                    </a>

                    <a href="orders.php" class="quick-btn">

                        📦 Buyurtmalar

                    </a>

                </div>

            </div>


        </div>

    </div>

</div>
<?php include "../includes/footer.php"; ?>