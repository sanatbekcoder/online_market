<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql = $conn->prepare(

    "SELECT *
FROM orders

WHERE user_id=?

ORDER BY id DESC"

);

$sql->execute([
    $user_id
]);

include "../includes/header.php";

?>
<style>
    .orders-page {

        padding: 20px 0;

    }


    /* TOP */

    .orders-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 45px;

        border-radius: 35px;

        margin-block-end: 35px;

        color: white;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.18);

        position: relative;

        overflow: hidden;

    }


    .orders-header::before {

        content: "";

        position: absolute;

        inline-size: 260px;
        block-size: 260px;

        border-radius: 50%;

        background:
            rgba(255,
                255,
                255,
                0.10);

        inset-inline-end: -100px;
        inset-block-start: -100px;

    }


    .orders-header h2 {

        font-size: 40px;

        font-weight: 700;

        position: relative;

        z-index: 2;

    }


    .orders-header p {

        opacity: .9;

        position: relative;

        z-index: 2;

    }


    /* ORDER */

    .order-card {

        background: white;

        border-radius: 35px;

        padding: 30px;

        margin-block-end: 30px;

        box-shadow:
            0 10px 35px rgba(0,
                0,
                0,
                0.06);

        transition: .4s;

    }

    .order-card:hover {

        transform:
            translateY(-8px);

        box-shadow:
            0 20px 40px rgba(37,
                99,
                235,
                0.12);

    }


    /* HEAD */

    .order-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        flex-wrap: wrap;

        gap: 10px;

        margin-block-end: 20px;

    }


    .order-id {

        font-size: 24px;

        font-weight: 700;

    }


    /* STATUS */

    .status {

        padding: 10px 18px;

        border-radius: 30px;

        font-weight: 700;

        font-size: 14px;

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
        background: #dcfce7;
        color: #166534;
    }

    .cancel {
        background: #ffe2e2;
        color: #dc2626;
    }


    /* PRODUCT */

    .product-item {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 18px;

        margin-block-end: 12px;

        background: #f8fbff;

        border-radius: 18px;

        border: 1px solid #e0ecff;

        transition: .3s;

    }


    .product-item:hover {

        background: #eff6ff;

    }


    /* TOTAL */

    .total-box {

        margin-block-start: 25px;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        padding: 25px;

        border-radius: 25px;

        text-align: center;

        color: white;

        font-weight: 700;

        font-size: 24px;

        box-shadow:
            0 15px 35px rgba(37,
                99,
                235,
                0.18);

    }


    /* EMPTY */

    .empty-orders {

        background: white;

        padding: 60px;

        border-radius: 35px;

        text-align: center;

        box-shadow:
            0 10px 30px rgba(37,
                99,
                235,
                0.08);

    }


    .empty-orders h2 {

        margin-block-start: 20px;

        font-weight: 700;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .orders-header {

            padding: 30px;

            text-align: center;

        }

        .orders-header h2 {

            font-size: 32px;

        }

        .product-item {

            flex-direction: column;

            text-align: center;

            gap: 8px;

        }

        .order-top {

            justify-content: center;

            text-align: center;

        }

    }
</style>



<div class="orders-page">


    <?php if (isset($_GET['success'])): ?>

        <div class="alert alert-success">

            ✅ Buyurtma muvaffaqiyatli yuborildi

        </div>

    <?php endif; ?>


    <div class="orders-header">

        <h2>

            📦 Buyurtmalar paneli

        </h2>

        <p>

            Barcha buyurtmalaringiz va holatlari shu yerda

        </p>

    </div>


    <?php
    $count = 0;
    while ($order = $sql->fetch()):
        $count++;
        ?>

        <div class="order-card">

            <div class="order-top">

                <div class="order-id">

                    Buyurtma #<?= $order['id'] ?>

                </div>


                <?php

                $class = "";
                $text = "";

                switch ($order['status']) {

                    case "korib_chiqilyapdi":
                        $class = "pending";
                        $text = "🔍 Ko'rib chiqilyapdi";
                        break;

                    case "tasdiqlandi":
                        $class = "accepted";
                        $text = "✅ Tasdiqlandi";
                        break;

                    case "yetkazilyapdi":
                        $class = "delivery";
                        $text = "🚚 Yetkazilyapdi";
                        break;

                    case "yetkazildi":
                        $class = "done";
                        $text = "📦 Yetkazildi";
                        break;

                    case "bekor_qilindi":
                        $class = "cancel";
                        $text = "❌ Bekor qilindi";
                        break;

                }

                ?>

                <div class="status <?= $class ?>">

                    <?= $text ?>

                </div>

            </div>


            <?php

            $items = $conn->prepare(
                "SELECT
order_items.*,
products.name
FROM order_items
JOIN products
ON order_items.product_id=products.id
WHERE order_id=?"
            );

            $items->execute([$order['id']]);

            $total = 0;

            while ($item = $items->fetch()):

                $sum = $item['price'] * $item['quantity'];

                $total += $sum;

                ?>

                <div class="product-item">

                    <div>

                        📱 <?= htmlspecialchars($item['name']) ?>

                    </div>

                    <div>

                        x<?= $item['quantity'] ?>

                    </div>

                    <div>

                        <?= number_format($sum) ?>

                        so'm

                    </div>

                </div>

            <?php endwhile; ?>


            <div class="total-box">

                Jami:
                <?= number_format($total) ?>
                so'm

            </div>

        </div>

    <?php endwhile; ?>


    <?php if ($count == 0): ?>

        <div class="empty-orders">

            <h1>

                📦

            </h1>

            <h2>

                Buyurtmalar mavjud emas

            </h2>

            <p class="text-muted">

                Hozircha birorta buyurtma berilmagan

            </p>

            <a href="../products/index.php" class="btn btn-success">

                Mahsulotlarga o'tish

            </a>

        </div>

    <?php endif; ?>


</div>