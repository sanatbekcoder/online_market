<?php

session_start();

require_once "../config/database.php";


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


/* STATUS O'ZGARTIRISH */

if (
    isset($_GET['status'])
    &&
    isset($_GET['id'])
) {

    $id =
        (int) $_GET['id'];

    $status =
        trim($_GET['status']);


    $get =
        $conn->prepare(

            "SELECT status
FROM orders
WHERE id=?"

        );

    $get->execute([$id]);

    $current =
        $get->fetchColumn();


    $allow = false;


    /* ketma ketlik */

    if (

        $current == "korib_chiqilyapdi"

        &&

        (
            $status == "tasdiqlandi"
            ||
            $status == "bekor_qilindi"
        )

    ) {

        $allow = true;

    }


    if (

        $current == "tasdiqlandi"

        &&

        $status == "yetkazilyapdi"

    ) {

        $allow = true;

    }


    if (

        $current == "yetkazilyapdi"

        &&

        $status == "yetkazildi"

    ) {

        $allow = true;

    }



    if ($allow) {

        $sql =
            $conn->prepare(

                "UPDATE orders
SET status=?
WHERE id=?"

            );

        $sql->execute([

            $status,
            $id

        ]);


        header(

            "Location: orders.php?msg="
            . $status

        );

        exit();

    }

}



/* FILTER */

$statusFilter =
    $_GET['filter']
    ?? "all";


if (
    $statusFilter == "all"
) {

    $orders =
        $conn->query(

            "SELECT

orders.*,

users.name

FROM orders

JOIN users

ON users.id=
orders.user_id

ORDER BY orders.id DESC"

        );

} else {


    $sql =
        $conn->prepare(

            "SELECT

orders.*,

users.name

FROM orders

JOIN users

ON users.id=
orders.user_id

WHERE orders.status=?

ORDER BY orders.id DESC"

        );


    $sql->execute([

        $statusFilter

    ]);

    $orders = $sql;

}



include "../includes/header.php";

?>
<style>
    .orders-panel {

        background: white;

        padding: 35px;

        border-radius: 35px;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.08);

    }


    /* TOP */

    .orders-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 35px;

        border-radius: 30px;

        color: white;

        margin-block-end: 30px;

        position: relative;

        overflow: hidden;

    }


    .orders-header::before {

        content: "";

        position: absolute;

        inline-size: 260px;
        block-size: 260px;

        background:
            rgba(255,
                255,
                255,
                0.10);

        border-radius: 50%;

        inset-block-start: -100px;
        inset-inline-end: -80px;

    }


    .orders-header h2 {

        font-size: 36px;

        font-weight: 700;

        position: relative;

        z-index: 2;

    }


    /* FILTER */

    .filters {

        display: flex;

        gap: 10px;

        flex-wrap: wrap;

        margin-block-end: 30px;

    }


    .filter-btn {

        border-radius: 18px;

        font-weight: 600;

        padding: 12px 18px;

    }


    /* CARD */

    .order-card {

        background: #f8fbff;

        padding: 25px;

        border-radius: 30px;

        margin-block-end: 20px;

        border: 1px solid #e0ecff;

        transition: .35s;

    }


    .order-card:hover {

        transform:
            translateY(-5px);

        box-shadow:
            0 15px 35px rgba(37,
                99,
                235,
                0.10);

    }


    .order-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        flex-wrap: wrap;

        gap: 15px;

        margin-block-end: 15px;

    }


    .order-id {

        font-size: 24px;

        font-weight: 700;

    }


    .user-name {

        color: #64748b;

    }


    /* STATUS */

    .status {

        padding: 10px 16px;

        border-radius: 30px;

        font-weight: 700;

    }

    .pending {
        background: #fff4d4;
        color: #a16207;
    }

    .accepted {
        background: #dcfce7;
        color: #15803d;
    }

    .delivery {
        background: #dbeafe;
        color: #2563eb;
    }

    .done {
        background: #e2e8f0;
        color: #0f172a;
    }

    .cancel {
        background: #ffe2e2;
        color: #dc2626;
    }


    /* ACTION */

    .action-box {

        display: flex;

        gap: 10px;

        flex-wrap: wrap;

        margin-block-start: 20px;

    }

    .action-btn {

        border-radius: 15px;

        font-weight: 700;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .orders-panel {

            padding: 20px;

        }

        .orders-header {

            text-align: center;

            padding: 30px;

        }

        .order-top {

            flex-direction: column;

            text-align: center;

        }

        .action-box {

            justify-content: center;

        }

    }
</style>



<div class="orders-panel">

    <div class="orders-header">

        <h2>

            📦 Buyurtmalar markazi

        </h2>

        <p>

            Buyurtmalarni boshqarish va kuzatish paneli

        </p>

    </div>



    <div class="filters">

        <a href="?filter=all" class="btn <?= $statusFilter == "all" ? "btn-dark" : "btn-outline-dark" ?> filter-btn">

            Hammasi

        </a>


        <a href="?filter=korib_chiqilyapdi"
            class="btn <?= $statusFilter == "korib_chiqilyapdi" ? "btn-warning" : "btn-outline-warning" ?> filter-btn">

            🆕 Yangi

        </a>


        <a href="?filter=tasdiqlandi"
            class="btn <?= $statusFilter == "tasdiqlandi" ? "btn-success" : "btn-outline-success" ?> filter-btn">

            ✅ Tasdiqlandi

        </a>


        <a href="?filter=yetkazilyapdi"
            class="btn <?= $statusFilter == "yetkazilyapdi" ? "btn-primary" : "btn-outline-primary" ?> filter-btn">

            🚚 Yetkazish

        </a>


        <a href="?filter=yetkazildi"
            class="btn <?= $statusFilter == "yetkazildi" ? "btn-secondary" : "btn-outline-secondary" ?> filter-btn">

            📦 Tugagan

        </a>


        <a href="?filter=bekor_qilindi"
            class="btn <?= $statusFilter == "bekor_qilindi" ? "btn-danger" : "btn-outline-danger" ?> filter-btn">

            ❌ Bekor

        </a>

    </div>



    <?php while ($order = $orders->fetch()): ?>


        <?php

        $class = "";
        $statusText = "";

        switch ($order['status']) {

            case "korib_chiqilyapdi":
                $class = "pending";
                $statusText = "🔍 Ko'rib chiqilyapdi";
                break;

            case "tasdiqlandi":
                $class = "accepted";
                $statusText = "✅ Tasdiqlandi";
                break;

            case "yetkazilyapdi":
                $class = "delivery";
                $statusText = "🚚 Yetkazilyapdi";
                break;

            case "yetkazildi":
                $class = "done";
                $statusText = "📦 Yetkazildi";
                break;

            case "bekor_qilindi":
                $class = "cancel";
                $statusText = "❌ Bekor qilindi";
                break;

        }

        ?>


        <div class="order-card">

            <div class="order-top">

                <div>

                    <div class="order-id">

                        #<?= $order['id'] ?>

                    </div>

                    <div class="user-name">

                        👤 <?= htmlspecialchars($order['name']) ?>

                    </div>

                    <div class="user-name">

                        📱 <?= htmlspecialchars($order['phone'] ?? "Noma'lum") ?>

                    </div>

                </div>


                <div class="status <?= $class ?>">

                    <?= $statusText ?>

                </div>

            </div>



            <div class="action-box">

                <?php if ($order['status'] == "korib_chiqilyapdi"): ?>

                    <a href="?id=<?= $order['id'] ?>&status=tasdiqlandi" class="btn btn-success action-btn">

                        Tasdiqlash

                    </a>


                    <a href="?id=<?= $order['id'] ?>&status=bekor_qilindi" class="btn btn-danger action-btn">

                        Bekor

                    </a>


                <?php elseif ($order['status'] == "tasdiqlandi"): ?>

                    <a href="?id=<?= $order['id'] ?>&status=yetkazilyapdi" class="btn btn-warning action-btn">

                        🚚 Jo'natish

                    </a>


                <?php elseif ($order['status'] == "yetkazilyapdi"): ?>

                    <a href="?id=<?= $order['id'] ?>&status=yetkazildi" class="btn btn-primary action-btn">

                        📦 Tugatish

                    </a>


                <?php elseif ($order['status'] == "yetkazildi"): ?>

                    <span class="badge bg-success">

                        Yakunlandi ✅

                    </span>


                <?php else: ?>

                    <span class="badge bg-danger">

                        Bekor qilingan

                    </span>

                <?php endif; ?>

            </div>

        </div>

    <?php endwhile; ?>

</div>