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


/* Kategoriyalar */

$categories =
    $conn->query(
        "SELECT * FROM categories"
    );


if (isset($_POST['save'])) {


    $name =
        htmlspecialchars(
            trim($_POST['name'])
        );

    $description =
        htmlspecialchars(
            trim($_POST['description'])
        );

    $price =
        (float) $_POST['price'];

    $category_id =
        (int) $_POST['category_id'];


    /* Rasm */

    $image = "no-image.png";


    if (
        isset($_FILES['image'])
        &&
        $_FILES['image']['error'] == 0
    ) {

        $allowed = [

            "jpg",
            "jpeg",
            "png",
            "webp"

        ];


        $ext =
            strtolower(

                pathinfo(

                    $_FILES['image']['name'],

                    PATHINFO_EXTENSION

                )

            );


        if (
            in_array(
                $ext,
                $allowed
            )
        ) {

            $image =

                time()

                . "_"

                .

                uniqid()

                . "."

                . $ext;


            move_uploaded_file(

                $_FILES['image']['tmp_name'],

                "../uploads/"
                . $image

            );

        }

    }


    $sql =
        $conn->prepare(

            "INSERT INTO products

(

name,
description,
price,
image,
category_id

)

VALUES

(?,?,?,?,?)"

        );


    $sql->execute([

        $name,
        $description,
        $price,
        $image,
        $category_id

    ]);


    header(
        "Location: products.php"
    );

    exit();

}



$products =
    $conn->query(

        "SELECT products.*,

categories.name as category

FROM products

LEFT JOIN categories

ON products.category_id=
categories.id

ORDER BY products.id DESC"

    );


include "../includes/header.php";

?>
<style>
    .admin-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 40px;

        border-radius: 35px;

        color: white;

        margin-block-end: 35px;

        position: relative;

        overflow: hidden;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.18);

    }

    .admin-header::before {

        content: "";

        position: absolute;

        inline-size: 250px;
        block-size: 250px;

        border-radius: 50%;

        background:
            rgba(255,
                255,
                255,
                0.10);

        inset-block-start: -100px;
        inset-inline-end: -80px;

    }

    .admin-header h2 {

        font-size: 38px;

        font-weight: 700;

        position: relative;

        z-index: 2;

    }


    /* FORM */

    .admin-box {

        background: white;

        padding: 35px;

        border-radius: 35px;

        margin-block-end: 40px;

        box-shadow:
            0 15px 40px rgba(37,
                99,
                235,
                0.08);

    }

    .admin-box h3 {

        font-weight: 700;

        margin-block-end: 25px;

        color: #1e293b;

    }


    .admin-box .form-control,
    .admin-box select {

        block-size: 58px;

        border-radius: 18px;

        border: 1px solid #dbeafe;

        margin-block-end: 15px;

        padding: 15px;

    }


    .admin-box textarea {

        block-size: 130px !important;

        padding-block-start: 15px;

    }


    .admin-box .form-control:focus {

        border-color: #2563eb;

        box-shadow:
            0 0 0 4px rgba(37,
                99,
                235,
                0.10);

    }


    /* BUTTON */

    .save-btn {

        block-size: 58px;

        inline-size: 100%;

        border: none;

        border-radius: 18px;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        font-weight: 700;

        color: white;

        transition: .3s;

    }


    .save-btn:hover {

        transform:
            translateY(-2px);

        box-shadow:
            0 15px 30px rgba(37,
                99,
                235,
                0.25);

    }


    /* PRODUCTS */

    .products-title {

        font-size: 35px;

        font-weight: 700;

        margin-block-end: 25px;

    }


    .product-card {

        border: none;

        border-radius: 30px;

        overflow: hidden;

        background: white;

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
            translateY(-8px);

        box-shadow:
            0 20px 40px rgba(37,
                99,
                235,
                0.12);

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


    .category-tag {

        display: inline-block;

        padding: 8px 14px;

        background: #eff6ff;

        color: #2563eb;

        border-radius: 20px;

        font-size: 13px;

        font-weight: 700;

        margin-block-end: 15px;

    }


    .product-price {

        font-size: 24px;

        font-weight: 700;

        color: #2563eb;

        margin: 15px 0;

    }


    .action-btn {

        border-radius: 15px;

        font-weight: 700;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .admin-header {

            text-align: center;

            padding: 30px;

        }

        .admin-header h2 {

            font-size: 30px;

        }

        .admin-box {

            padding: 20px;

        }

    }
</style>



<div class="admin-header">

    <h2>

        ⚙ Mahsulot boshqaruvi

    </h2>

    <p>

        Mahsulotlarni qo'shish va boshqarish paneli

    </p>

</div>



<div class="admin-box">

    <h3>

        🛍 Yangi mahsulot qo'shish

    </h3>

    <form method="POST" enctype="multipart/form-data">

        <input name="name" class="form-control" placeholder="📱 Mahsulot nomi" required>


        <textarea name="description" class="form-control" placeholder="📝 Tavsif" required></textarea>


        <input type="number" name="price" class="form-control" placeholder="💰 Narx" required>


        <select name="category_id" class="form-control" required>

            <option value="">

                Kategoriya tanlang

            </option>

            <?php while ($cat = $categories->fetch()): ?>

                <option value="<?= $cat['id'] ?>">

                    <?= $cat['name'] ?>

                </option>

            <?php endwhile; ?>

        </select>


        <input type="file" name="image" class="form-control">


        <button name="save" class="save-btn">

            🚀 Saqlash

        </button>

    </form>

</div>



<div class="products-title">

    📦 Mahsulotlar

</div>


<div class="row g-4">

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


                    <div class="product-price">

                        <?= number_format($p['price']) ?>

                        so'm

                    </div>


                    <div class="d-flex gap-2">

                        <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-warning flex-fill action-btn">

                            ✏️ Tahrirlash

                        </a>


                        <a href="delete_product.php?id=<?= $p['id'] ?>" class="btn btn-danger flex-fill action-btn"
                            onclick="return confirm('Rostdan o‘chirasizmi?')">

                            🗑 O‘chirish

                        </a>

                    </div>

                </div>

            </div>

        </div>

    <?php endwhile; ?>

</div>