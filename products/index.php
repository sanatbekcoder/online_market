<?php

session_start();

require_once "../config/database.php";

$search = $_GET['search'] ?? "";

if ($search) {

    $sql = $conn->prepare(

        "SELECT products.*,
categories.name as category

FROM products

LEFT JOIN categories

ON products.category_id=categories.id

WHERE products.name LIKE ?

ORDER BY products.id DESC"

    );

    $sql->execute([
        "%" . $search . "%"
    ]);

    $products = $sql;

} else {

    $products = $conn->query(

        "SELECT products.*,
categories.name as category

FROM products

LEFT JOIN categories

ON products.category_id=categories.id

ORDER BY products.id DESC"

    );

}

include "../includes/header.php";

?>
<style>
    body {
        background:
            linear-gradient(180deg,
                #f8fbff,
                #edf4ff);

        font-family: 'Poppins', sans-serif;
    }


    /* TOP */

    .page-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 50px;

        border-radius: 35px;

        margin-block-end: 40px;

        color: white;

        position: relative;

        overflow: hidden;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.18);

    }


    .page-header::before {

        content: "";

        position: absolute;

        inline-size: 280px;
        block-size: 280px;

        background:
            rgba(255,
                255,
                255,
                0.10);

        border-radius: 50%;

        inset-block-start: -100px;
        inset-inline-end: -100px;

    }


    .page-header h1 {

        font-weight: 700;

        font-size: 45px;

        position: relative;

        z-index: 2;

    }


    .page-header p {

        opacity: .9;

        position: relative;

        z-index: 2;

    }


    /* SEARCH */

    .search-box {

        background: white;

        padding: 8px;

        border-radius: 20px;

        display: flex;

        gap: 10px;

        box-shadow:
            0 10px 25px rgba(0,
                0,
                0,
                0.08);

        position: relative;

        z-index: 2;

    }


    .search-box input {

        border: none;

        block-size: 52px;

        border-radius: 15px;

        padding: 15px;

        box-shadow: none !important;

    }


    /* CARD */

    .product-card {

        border: none;

        border-radius: 30px;

        overflow: hidden;

        block-size: 100%;

        background: white;

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


    /* CATEGORY */

    .category-tag {

        display: inline-block;

        padding: 8px 14px;

        border-radius: 20px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 13px;

        font-weight: 600;

        margin-block-end: 15px;

    }


    /* PRICE */

    .price {

        font-size: 25px;

        font-weight: 700;

        color: #2563eb;

        margin: 15px 0;

    }


    /* BUTTON */

    .btn-success {

        background: #2563eb;

        border: none;

        border-radius: 15px;

        font-weight: 600;

        padding: 12px;

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


    /* EMPTY */

    .empty-box {

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


    .empty-icon {

        font-size: 60px;

        margin-block-end: 20px;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .page-header {

            padding: 30px;

            text-align: center;

        }

        .page-header h1 {

            font-size: 32px;

        }

    }
</style>



<div class="page-header">

    <div class="row align-items-center">

        <div class="col-lg-7">

            <h1>

                🚀 Tech Store

            </h1>

            <p>

                Eng yangi texnologiyalar va premium qurilmalar

            </p>

        </div>


        <div class="col-lg-5">

            <form method="GET">

                <div class="search-box">

                    <input type="text" name="search" class="form-control" placeholder="🔍 Mahsulot qidiring..."
                        value="<?= htmlspecialchars($search) ?>">


                    <button class="btn btn-success">

                        Qidirish

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<div class="row g-4">

    <?php if ($products->rowCount() > 0): ?>

        <?php while ($p = $products->fetch()): ?>

            <div class="col-xl-4 col-md-6">

                <div class="product-card">

                    <img src="../uploads/<?= $p['image'] ?>">


                    <div class="card-body">

                        <div class="category-tag">

                            <?= $p['category'] ?>

                        </div>


                        <h4>

                            <?= htmlspecialchars($p['name']) ?>

                        </h4>


                        <p class="text-muted">

                            <?= mb_strimwidth(
                                strip_tags($p['description']),
                                0,
                                75,
                                "..."
                            ) ?>

                        </p>


                        <div class="price">

                            <?= number_format($p['price']) ?>

                            so'm

                        </div>


                        <div class="d-flex gap-2">

                            <a href="../cart/add.php?id=<?= $p['id'] ?>" class="btn btn-success flex-fill">

                                🛒 Savatcha

                            </a>


                            <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline-success flex-fill">

                                Ko'rish →

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="col-12">

            <div class="empty-box">

                <div class="empty-icon">

                    📦

                </div>

                <h3>

                    Mahsulot topilmadi

                </h3>

                <p class="text-muted">

                    Qidiruv bo'yicha natija mavjud emas

                </p>

            </div>

        </div>

    <?php endif; ?>

</div>
<?php include "../includes/footer.php"; ?>