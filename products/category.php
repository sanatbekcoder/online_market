<?php

session_start();

require_once "../config/database.php";

$id = $_GET['id'] ?? 0;

$category = $conn->prepare(
    "SELECT * FROM categories
WHERE id=?"
);

$category->execute([$id]);

$cat = $category->fetch();

if (!$cat) {

    die("Kategoriya topilmadi");

}

$products = $conn->prepare(

    "SELECT *
FROM products

WHERE category_id=?"

);

$products->execute([$id]);

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


    /* CATEGORY HEADER */

    .category-header {

        background:
            linear-gradient(135deg,
                #ffffff,
                #dbeafe,
                #bfdbfe);

        padding: 60px;

        border-radius: 30px;

        margin-block-end: 40px;

        box-shadow:
            0 15px 40px rgba(37,
                99,
                235,
                0.08);

        position: relative;

        overflow: hidden;

    }


    .category-header::before {

        content: "";

        position: absolute;

        inline-size: 280px;
        block-size: 280px;

        background:
            rgba(59,
                130,
                246,
                0.08);

        border-radius: 50%;

        inset-block-start: -120px;
        inset-inline-end: -90px;

    }


    .category-header::after {

        content: "";

        position: absolute;

        inline-size: 200px;
        block-size: 200px;

        background:
            rgba(255,
                255,
                255,
                0.45);

        border-radius: 50%;

        inset-block-end: -80px;
        inset-inline-start: -50px;

    }


    /* PRODUCT */

    .product-card {

        background: white;

        border: none;

        border-radius: 28px;

        overflow: hidden;

        block-size: 100%;

        transition: .4s;

        box-shadow:
            0 10px 25px rgba(0,
                0,
                0,
                0.06);

    }


    .product-card:hover {

        transform:
            translateY(-10px);

        box-shadow:
            0 20px 45px rgba(37,
                99,
                235,
                0.15);

    }


    .product-card img {

        inline-size: 100%;

        block-size: 240px;

        object-fit: cover;

        transition: .6s;

    }


    .product-card:hover img {

        transform:
            scale(1.08);

    }


    .card-body {

        padding: 25px;

    }


    /* PRICE */

    .price {

        font-size: 24px;

        font-weight: 700;

        color: #2563eb;

        margin: 15px 0;

    }


    /* BUTTON */

    .btn-success {

        background: #2563eb;

        border: none;

        border-radius: 15px;

        padding: 10px 20px;

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

        border-radius: 15px;

        padding: 10px 20px;

        font-weight: 600;

        transition: .3s;

    }


    .btn-outline-success:hover {

        background: #2563eb;

        color: white;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .category-header {

            padding: 30px;

            text-align: center;

        }

        .category-header h1 {

            font-size: 30px;

        }

    }
</style>



<div class="category-header">

    <div class="row align-items-center">

        <div class="col-md-8">

            <h1>

                <?= $cat['name'] ?>

                📦

            </h1>

            <p class="text-muted mt-3">

                Bu bo'limdagi mahsulotlarni ko'rishingiz mumkin.

            </p>

        </div>


        <div class="col-md-4 text-end">

            <a href="../products/index.php" class="btn btn-success">

                ← Barcha mahsulotlar

            </a>

        </div>

    </div>

</div>



<div class="row">

    <?php while ($p = $products->fetch()): ?>

        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card product-card">

                <img src="../uploads/<?= $p['image'] ?>">

                <div class="card-body">

                    <h4>

                        <?= htmlspecialchars(
                            $p['name']
                        ) ?>

                    </h4>

                    <p class="text-muted">

                        <?= mb_strimwidth(

                            strip_tags(
                                $p['description']
                            ),

                            0,
                            70,
                            "..."

                        ) ?>

                    </p>


                    <div class="price">

                        <?= number_format(
                            $p['price']
                        ) ?>

                        so'm

                    </div>


                    <div class="d-flex gap-2">

                        <a href="../cart/add.php?id=<?= $p['id'] ?>" class="btn btn-success flex-fill">

                            🛒 Savatcha

                        </a>


                        <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline-success flex-fill">

                            Ko'rish

                        </a>

                    </div>

                </div>

            </div>

        </div>

    <?php endwhile; ?>

</div>


<?php include "../includes/footer.php"; ?>