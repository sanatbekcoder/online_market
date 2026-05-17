<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql = $conn->prepare(

    "SELECT cart.*,
products.name,
products.price,
products.image

FROM cart

JOIN products

ON cart.product_id=products.id

WHERE user_id=?"

);

$sql->execute([
    $user_id
]);

$items = $sql;

$total = 0;

include "../includes/header.php";

?>
<style>
    .cart-page {

        padding: 20px 0;

    }


    /* TOP */

    .cart-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 40px;

        border-radius: 35px;

        margin-block-end: 35px;

        color: white;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.18);

    }


    .cart-header h2 {

        font-size: 40px;

        font-weight: 700;

        margin-block-end: 10px;

    }


    /* CARD */

    .cart-card {

        background: white;

        padding: 20px;

        border-radius: 30px;

        margin-block-end: 20px;

        box-shadow:
            0 10px 30px rgba(0,
                0,
                0,
                0.06);

        transition: .4s;

    }


    .cart-card:hover {

        transform:
            translateY(-8px);

        box-shadow:
            0 20px 40px rgba(37,
                99,
                235,
                0.12);

    }


    .cart-content {

        display: flex;

        align-items: center;

        gap: 25px;

    }


    .cart-image {

        inline-size: 130px;

        block-size: 130px;

        border-radius: 25px;

        object-fit: cover;

        transition: .4s;

    }


    .cart-image:hover {

        transform: scale(1.05);

    }


    .product-info {

        flex: 1;

    }


    .product-info h4 {

        font-weight: 700;

        margin-block-end: 15px;

    }


    .quantity {

        display: inline-block;

        padding: 10px 16px;

        border-radius: 30px;

        background: #eff6ff;

        color: #2563eb;

        font-weight: 600;

        margin-block-end: 15px;

    }


    .price {

        font-size: 26px;

        font-weight: 700;

        color: #2563eb;

    }


    /* DELETE */

    .delete-btn {

        border-radius: 15px;

        padding: 12px 18px;

        font-weight: 600;

    }


    /* TOTAL */

    .total-box {

        margin-block-start: 35px;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        padding: 35px;

        border-radius: 35px;

        color: white;

        text-align: center;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.2);

    }


    .total-price {

        font-size: 35px;

        font-weight: 700;

        margin: 15px 0;

    }


    /* CHECKOUT */

    .checkout-btn {

        inline-size: 100%;

        block-size: 58px;

        border: none;

        border-radius: 18px;

        font-size: 18px;

        font-weight: 700;

        background: white;

        color: #2563eb;

        margin-block-start: 20px;

    }


    /* EMPTY */

    .empty-box {

        background: white;

        padding: 60px;

        text-align: center;

        border-radius: 35px;

        box-shadow:
            0 10px 30px rgba(37,
                99,
                235,
                0.08);

    }


    .empty-icon {

        font-size: 70px;

        margin-block-end: 20px;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .cart-header {

            text-align: center;

            padding: 30px;

        }

        .cart-header h2 {

            font-size: 32px;

        }

        .cart-content {

            flex-direction: column;

            text-align: center;

        }

        .cart-image {

            inline-size: 100%;

            block-size: 220px;

        }

    }
</style>



<div class="cart-page">

    <div class="cart-header">

        <h2>

            🛒 Sizning savatchangiz

        </h2>

        <p>

            Tanlangan mahsulotlaringiz shu yerda saqlanadi

        </p>

    </div>


    <?php if ($items->rowCount() > 0): ?>


        <?php while ($item = $items->fetch()): ?>

            <?php
            $sum = $item['price'] * $item['quantity'];
            $total += $sum;
            ?>

            <div class="cart-card">

                <div class="cart-content">

                    <img src="../uploads/<?= $item['image'] ?>" class="cart-image">


                    <div class="product-info">

                        <h4>

                            <?= htmlspecialchars($item['name']) ?>

                        </h4>


                        <div class="quantity">

                            📦 <?= $item['quantity'] ?> dona

                        </div>


                        <div class="price">

                            <?= number_format($sum) ?>

                            so'm

                        </div>

                    </div>


                    <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-danger delete-btn">

                        🗑 O'chirish

                    </a>

                </div>

            </div>

        <?php endwhile; ?>


        <div class="total-box">

            <h4>

                Jami buyurtma qiymati

            </h4>

            <div class="total-price">

                <?= number_format($total) ?>

                so'm

            </div>


            <a href="../orders/checkout.php" class="btn checkout-btn">

                🚀 Buyurtma berish

            </a>

        </div>


    <?php else: ?>

        <div class="empty-box">

            <div class="empty-icon">

                🛒

            </div>

            <h2>

                Savatcha bo‘sh

            </h2>

            <p class="text-muted">

                Hozircha savatchada mahsulot yo‘q

            </p>

            <a href="../products/index.php" class="btn btn-success">

                Mahsulotlarni ko‘rish

            </a>

        </div>

    <?php endif; ?>

</div>

<?php include "../includes/footer.php"; ?>