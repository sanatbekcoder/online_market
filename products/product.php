<?php

session_start();

require_once "../config/database.php";

$id = $_GET['id'] ?? 0;

$sql = $conn->prepare(

    "SELECT products.*,
categories.name as category

FROM products

LEFT JOIN categories

ON products.category_id=categories.id

WHERE products.id=?"

);

$sql->execute([$id]);

$product = $sql->fetch();

if (!$product) {

    die("Mahsulot topilmadi");

}

include "../includes/header.php";

?>

<style>
    body {

        background:
            linear-gradient(180deg,
                #f8fbff,
                #edf4ff);

        font-family: 'Segoe UI', sans-serif;

    }


    /* PRODUCT BOX */

    .product-box {

        background: white;

        padding: 40px;

        border-radius: 35px;

        box-shadow:
            0 20px 45px rgba(37,
                99,
                235,
                0.08);

        margin-block-start: 20px;
        margin-block-end: 40px;

    }


    /* IMAGE */

    .product-image {

        inline-size: 100%;

        block-size: 500px;

        object-fit: cover;

        border-radius: 25px;

        transition: .5s;

        box-shadow:
            0 15px 30px rgba(0,
                0,
                0,
                0.12);

    }


    .product-image:hover {

        transform:
            scale(1.03);

        box-shadow:
            0 20px 40px rgba(37,
                99,
                235,
                0.15);

    }


    /* CATEGORY */

    .category-badge {

        display: inline-block;

        background: #2563eb;

        color: white;

        padding: 10px 18px;

        border-radius: 25px;

        text-decoration: none;

        margin-block-end: 15px;

        font-weight: 600;

        transition: .3s;

    }


    .category-badge:hover {

        background: #1d4ed8;

        color: white;

    }


    /* PRICE */

    .price {

        font-size: 35px;

        font-weight: 700;

        color: #2563eb;

        margin: 20px 0;

    }


    /* DESCRIPTION */

    .description {

        background: #f8fbff;

        padding: 25px;

        border-radius: 20px;

        margin-block-start: 20px;

        line-height: 1.8;

        box-shadow:
            0 8px 20px rgba(37,
                99,
                235,
                0.05);

    }


    /* BUTTON */

    .btn-success {

        background: #2563eb;

        border: none;

        padding: 14px 22px;

        border-radius: 15px;

        font-weight: 600;

        transition: .3s;

    }


    .btn-success:hover {

        background: #1d4ed8;

        transform:
            translateY(-2px);

    }


    .btn-outline-success {

        border: 2px solid #2563eb;

        color: #2563eb;

        padding: 14px 22px;

        border-radius: 15px;

        font-weight: 600;

        transition: .3s;

    }


    .btn-outline-success:hover {

        background: #2563eb;

        color: white;

    }


    /* TITLE */

    h1 {

        font-weight: 700;

        color: #1e293b;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .product-box {

            padding: 20px;

        }

        .product-image {

            block-size: 280px;

            margin-block-end: 25px;

        }

        .price {

            font-size: 28px;

        }

        h1 {

            font-size: 30px;

        }

    }
</style>



<div class="product-box">

    <div class="row align-items-center">

        <div class="col-lg-5">

            <img src="../uploads/<?= $product['image'] ?>" class="product-image">

        </div>


        <div class="col-lg-7">

            <a href="category.php?id=<?= $product['category_id'] ?>" class="category-badge">

                <?= $product['category'] ?>

            </a>


            <h1 class="mt-2">

                <?= htmlspecialchars(
                    $product['name']
                ) ?>

            </h1>


            <div class="price">

                <?= number_format(
                    $product['price']
                ) ?>

                so'm

            </div>


            <div class="description">

                <?= nl2br(

                    htmlspecialchars(
                        $product['description']
                    )

                ) ?>

            </div>


            <div class="mt-4 d-flex gap-3 flex-wrap">

                <a href="../cart/add.php?id=<?= $product['id'] ?>" class="btn btn-success">

                    🛒 Savatchaga qo'shish

                </a>


                <a href="index.php" class="btn btn-outline-success">

                    ← Ortga

                </a>

            </div>

        </div>

    </div>

</div>

<?php include "../includes/footer.php"; ?>