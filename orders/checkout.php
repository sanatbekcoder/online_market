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

$total = 0;

include "../includes/header.php";

?>

<style>
    .checkout-box {

        background: #fff;

        padding: 40px;

        border-radius: 30px;

        box-shadow:
            0 20px 45px rgba(37,
                99,
                235,
                0.08);

        margin-block-end: 30px;

    }


    /* ITEM */

    .item-card {

        display: flex;

        align-items: center;

        gap: 15px;

        padding: 15px;

        background: #f8fbff;

        border-radius: 20px;

        margin-block-end: 15px;

        transition: .3s;

        border: 1px solid #e0ecff;

    }


    .item-card:hover {

        transform:
            translateY(-3px);

        box-shadow:
            0 10px 25px rgba(37,
                99,
                235,
                0.10);

    }


    .item-card img {

        inline-size: 80px;

        block-size: 80px;

        border-radius: 15px;

        object-fit: cover;

        box-shadow:
            0 8px 15px rgba(0,
                0,
                0,
                0.08);

    }


    /* TOTAL */

    .total-box {

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        color: white;

        padding: 22px;

        border-radius: 20px;

        font-size: 24px;

        font-weight: 700;

        text-align: center;

        margin: 25px 0;

        box-shadow:
            0 15px 35px rgba(37,
                99,
                235,
                0.20);

    }


    /* INPUT */

    .checkout-box .form-control {

        block-size: 55px;

        border-radius: 15px;

        margin-block-end: 15px;

        border: 1px solid #dbeafe;

        padding: 15px;

        transition: .3s;

    }


    .checkout-box .form-control:focus {

        border-color: #2563eb;

        box-shadow:
            0 0 0 4px rgba(37,
                99,
                235,
                0.10);

    }


    /* TEXTAREA */

    .checkout-box textarea {

        block-size: 110px !important;

        border-radius: 15px;

    }


    /* BUTTON */

    .send-btn {

        block-size: 55px;

        inline-size: 100%;

        border: none;

        background: #2563eb;

        color: white;

        border-radius: 15px;

        font-weight: 600;

        transition: .3s;

    }


    .send-btn:hover {

        background: #1d4ed8;

        transform:
            translateY(-2px);

        box-shadow:
            0 10px 25px rgba(37,
                99,
                235,
                0.20);

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .checkout-box {

            padding: 20px;

        }

        .item-card {

            flex-direction: column;

            text-align: center;

        }

    }
</style>



<div class="checkout-box">

    <h2 class="mb-4">

        📦 Buyurtma berish

    </h2>


    <?php while ($item = $sql->fetch()): ?>

        <?php

        $sum =
            $item['price']
            *
            $item['quantity'];

        $total +=
            $sum;

        ?>

        <div class="item-card">

            <img src="../uploads/<?= $item['image'] ?>">

            <div>

                <h5>

                    <?= htmlspecialchars(
                        $item['name']
                    ) ?>

                </h5>

                <div>

                    Miqdor:

                    <?= $item['quantity'] ?>

                </div>

                <div>

                    <?= number_format(
                        $sum
                    ) ?>

                    so'm

                </div>

            </div>

        </div>

    <?php endwhile; ?>


    <div class="total-box">

        Jami:

        <?= number_format(
            $total
        ) ?>

        so'm

    </div>



    <form action="create.php" method="POST">

        <input name="fullname" class="form-control" placeholder="Ism" required>


        <input name="phone" class="form-control" placeholder="+998901234567" required>


        <textarea name="address" class="form-control" placeholder="Manzil" required></textarea>


        <textarea name="note" class="form-control" placeholder="Izoh"></textarea>


        <button class="send-btn">

            🚀 Buyurtma yuborish

        </button>

    </form>

</div>


<?php include "../includes/footer.php"; ?>