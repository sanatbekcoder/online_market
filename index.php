<?php

session_start();

require_once "config/database.php";


$limit = 12;

$page =
    isset($_GET['page'])
    ?
    max(
        1,
        (int) $_GET['page']
    )
    :
    1;


$offset =
    ($page - 1)
    *
    $limit;


/* jami mahsulotlar */

$totalProducts =
    $conn->query(

        "SELECT COUNT(*)

FROM products"

    )->fetchColumn();


$totalPages =
    ceil(
        $totalProducts / $limit
    );


/* pagination */

$products =
    $conn->prepare(

        "SELECT products.*,

categories.name as category

FROM products

LEFT JOIN categories

ON products.category_id=
categories.id

ORDER BY products.id DESC

LIMIT ?,?"

    );


$products->bindValue(
    1,
    $offset,
    PDO::PARAM_INT
);

$products->bindValue(
    2,
    $limit,
    PDO::PARAM_INT
);

$products->execute();


include "includes/header.php";

?>

<style>
    body {
        background: linear-gradient(180deg, #f8fbff, #edf4ff);
        overflow-x: hidden;
        font-family: 'Poppins', sans-serif;
    }


    /* HERO */

    .hero {
        background: linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 70px;

        border-radius: 40px;

        margin: 20px 0 50px;

        color: white;

        position: relative;

        overflow: hidden;

        box-shadow:
            0 20px 60px rgba(37,
                99,
                235,
                0.20);

    }

    .hero::before {

        content: "";

        position: absolute;

        inline-size: 300px;
        block-size: 300px;

        border-radius: 50%;

        background:
            rgba(255,
                255,
                255,
                0.09);

        inset-inline-end: -100px;
        inset-block-start: -100px;

    }

    .hero-mini {

        display: inline-block;

        padding: 10px 18px;

        border-radius: 30px;

        background:
            rgba(255,
                255,
                255,
                0.15);

        backdrop-filter: blur(10px);

        margin-block-end: 20px;

    }

    .hero-title {

        font-size: 58px;

        font-weight: 700;

        line-height: 1.1;

        margin-block-end: 20px;

    }

    .hero-text {

        font-size: 18px;

        opacity: .9;

        max-inline-size: 550px;

    }

    .hero-actions {

        display: flex;

        gap: 15px;

        margin-block-start: 30px;

        flex-wrap: wrap;

    }

    .hero-image {

        inline-size: 100%;

        block-size: 420px;

        object-fit: cover;

        border-radius: 30px;

        box-shadow:
            0 25px 50px rgba(0,
                0,
                0,
                0.25);

    }

    .hero-stats {

        display: flex;

        gap: 20px;

        margin-block-start: 40px;

        flex-wrap: wrap;

    }

    .stat-box {

        padding: 20px;

        border-radius: 25px;

        background:
            rgba(255,
                255,
                255,
                0.12);

        min-inline-size: 120px;

        text-align: center;

        backdrop-filter: blur(10px);

    }

    .stat-box h4 {

        font-size: 28px;

        font-weight: 700;

        margin-block-end: 5px;

    }


    /* SECTION */

    .section-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-block-end: 35px;

        flex-wrap: wrap;

    }

    .section-top h2 {

        font-weight: 700;

    }

    .section-top p {

        color: #64748b;

        margin: 0;

    }

    .counter-box {

        padding: 15px 25px;

        border-radius: 30px;

        background: white;

        font-weight: 700;

        box-shadow:
            0 10px 25px rgba(37,
                99,
                235,
                0.08);

    }


    /* CARD */

    .product-card {

        background: white;

        border-radius: 30px;

        overflow: hidden;

        block-size: 100%;

        transition: .4s;

        box-shadow:
            0 10px 30px rgba(0,
                0,
                0,
                0.06);

    }

    .product-card:hover {

        transform:
            translateY(-10px);

        box-shadow:
            0 20px 40px rgba(37,
                99,
                235,
                0.14);

    }

    .image-wrap {

        position: relative;

        overflow: hidden;

    }

    .image-wrap img {

        inline-size: 100%;

        block-size: 240px;

        object-fit: cover;

        transition: .6s;

    }

    .product-card:hover img {

        transform:
            scale(1.08);

    }

    .new-tag {

        position: absolute;

        inset-block-start: 15px;

        inset-inline-end: 15px;

        background: #2563eb;

        padding: 8px 14px;

        border-radius: 20px;

        color: white;

        font-size: 13px;

        font-weight: 600;

    }

    .card-body {

        padding: 25px;

    }

    .category-tag {

        display: inline-block;

        text-decoration: none;

        background: #eff6ff;

        color: #2563eb;

        padding: 8px 14px;

        border-radius: 20px;

        font-size: 13px;

        margin-block-end: 15px;

        font-weight: 600;

    }

    .price {

        font-size: 25px;

        font-weight: 700;

        color: #2563eb;

        margin-block-start: 15px;

    }


    /* BUTTON */

    .btn-success {

        background: #2563eb;

        border: none;

        padding: 12px;

        border-radius: 15px;

        font-weight: 600;

    }

    .btn-success:hover {

        background: #1d4ed8;

    }

    .btn-outline-success {

        border: 2px solid #2563eb;

        color: #2563eb;

        border-radius: 15px;

        font-weight: 600;

    }

    .btn-outline-success:hover {

        background: #2563eb;

        color: white;

    }


    /* PAGINATION */

    .pagination {

        margin-block-start: 50px;

    }

    .page-link {

        border: none;

        padding: 12px 18px;

        margin: 0 5px;

        border-radius: 14px;

        color: #2563eb;

        box-shadow:
            0 5px 15px rgba(0,
                0,
                0,
                0.06);

    }

    .active .page-link {

        background: #2563eb;

        color: white;

    }

    .page-link:hover {

        background: #dbeafe;

    }


    /* DELIVERY */

    .delivery-box {

        margin-block-start: 70px;

        padding: 60px;

        text-align: center;

        background: white;

        border-radius: 35px;

        box-shadow:
            0 10px 30px rgba(37,
                99,
                235,
                0.08);

    }

    .delivery-box h2 {

        color: #2563eb;

        font-weight: 700;

        margin-block-end: 15px;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .hero {

            padding: 30px;

            text-align: center;

        }

        .hero-title {

            font-size: 34px;

        }

        .hero-stats {

            justify-content: center;

        }

        .hero-image {

            block-size: 250px;

            margin-block-start: 25px;

        }

    }
</style>

<div class="hero">

    <div class="row align-items-center">

        <div class="col-lg-7">

            <span class="hero-mini">

                ⚡ Smart Technology Platform

            </span>

            <h1 class="hero-title">

                Kelajak texnologiyalari
                bir joyda 🚀

            </h1>

            <p class="hero-text">

                Telefonlar, noutbuklar, smart qurilmalar
                va zamonaviy texnikalarni qulay tarzda
                xarid qiling.

            </p>


            <div class="hero-actions">

                <a href="products/index.php" class="btn btn-primary btn-lg">

                    🛍 Xarid qilish

                </a>

                <?php if (!isset($_SESSION['user_id'])): ?>

                    <a href="accounts/register.php" class="btn btn-light btn-lg">

                        Boshlash →

                    </a>

                <?php endif; ?>

            </div>


            <div class="hero-stats">

                <div class="stat-box">

                    <h4><?= $totalProducts ?></h4>

                    <span>Mahsulot</span>

                </div>

                <div class="stat-box">

                    <h4>24/7</h4>

                    <span>Xizmat</span>

                </div>

                <div class="stat-box">

                    <h4>100%</h4>

                    <span>Xavfsiz</span>

                </div>

            </div>

        </div>


        <div class="col-lg-5 text-center">

            <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=1200" class="hero-image">

        </div>

    </div>

</div>



<div class="section-top">

    <div>

        <h2>

            🔥 Trend mahsulotlar

        </h2>

        <p>

            Eng yangi texnologiyalar tanlovi

        </p>

    </div>

    <div class="counter-box">

        <?= $totalProducts ?>

        ta mahsulot

    </div>

</div>



<div class="row g-4">

    <?php while ($p = $products->fetch()): ?>

        <div class="col-xl-3 col-lg-4 col-md-6">

            <div class="product-card">

                <div class="image-wrap">

                    <img src="uploads/<?= htmlspecialchars($p['image']) ?>">


                    <div class="new-tag">

                        Yangi

                    </div>

                </div>


                <div class="card-body">

                    <a href="products/category.php?id=<?= $p['category_id'] ?>" class="category-tag">

                        <?= htmlspecialchars($p['category']) ?>

                    </a>


                    <h5>

                        <?= htmlspecialchars($p['name']) ?>

                    </h5>


                    <p class="text-muted">

                        <?= mb_strimwidth(strip_tags($p['description']), 0, 65, "...") ?>

                    </p>


                    <div class="price-row">

                        <div class="price">

                            <?= number_format($p['price']) ?>

                            so'm

                        </div>

                    </div>


                    <div class="d-grid gap-2 mt-3">

                        <a href="cart/add.php?id=<?= $p['id'] ?>" class="btn btn-success">

                            🛒 Savatchaga qo'shish

                        </a>

                        <a href="products/product.php?id=<?= $p['id'] ?>" class="btn btn-outline-success">

                            Ko'rish →

                        </a>

                    </div>

                </div>

            </div>

        </div>

    <?php endwhile; ?>

</div>



<div class="delivery-box">

    <h2>

        🚀 Ultra tez yetkazish

    </h2>

    <p>

        Buyurtmangiz bir necha bosqichli nazoratdan
        o‘tadi va xavfsiz tarzda manzilingizga
        yetkaziladi.

    </p>

</div>

<?php include "includes/footer.php"; ?>