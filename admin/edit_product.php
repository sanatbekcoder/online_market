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


$id =
    (int) ($_GET['id'] ?? 0);


$sql =
    $conn->prepare(

        "SELECT *
FROM products
WHERE id=?"

    );

$sql->execute([
    $id
]);

$product =
    $sql->fetch();


if (!$product) {

    die(
        "Mahsulot topilmadi"
    );

}


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

    $image =
        $product['image'];



    if (
        isset($_FILES['image'])
        &&
        $_FILES['image']['error'] == 0
    ) {

        $ext =
            strtolower(

                pathinfo(

                    $_FILES['image']['name'],

                    PATHINFO_EXTENSION

                )

            );

        $allow = [

            "jpg",
            "jpeg",
            "png",
            "webp"

        ];


        if (
            in_array(
                $ext,
                $allow
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



    $update =
        $conn->prepare(

            "UPDATE products

SET

name=?,
description=?,
price=?,
image=?,
category_id=?

WHERE id=?"

        );


    $update->execute([

        $name,
        $description,
        $price,
        $image,
        $category_id,
        $id

    ]);


    header(
        "Location: products.php"
    );

    exit();

}


include "../includes/header.php";

?>
<style>
    .edit-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 40px;

        border-radius: 35px;

        margin-block-end: 35px;

        color: white;

        position: relative;

        overflow: hidden;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.18);

    }

    .edit-header::before {

        content: "";

        position: absolute;

        inline-size: 250px;
        block-size: 250px;

        background:
            rgba(255,
                255,
                255,
                0.10);

        border-radius: 50%;

        inset-block-start: -100px;
        inset-inline-end: -80px;

    }

    .edit-header h2 {

        font-size: 38px;

        font-weight: 700;

        position: relative;

        z-index: 2;

    }


    .admin-box {

        background: white;

        padding: 35px;

        border-radius: 35px;

        box-shadow:
            0 15px 40px rgba(37,
                99,
                235,
                0.08);

    }


    .form-control {

        block-size: 58px;

        border-radius: 18px;

        border: 1px solid #dbeafe;

        padding: 15px;

        margin-block-end: 15px;

    }


    textarea.form-control {

        block-size: 140px !important;

        padding-block-start: 15px;

    }


    .form-control:focus {

        border-color: #2563eb;

        box-shadow:
            0 0 0 4px rgba(37,
                99,
                235,
                0.10);

    }


    .preview-box {

        background: #f8fbff;

        padding: 20px;

        border-radius: 25px;

        text-align: center;

        margin-block-end: 20px;

        border: 1px solid #e0ecff;

    }


    .preview-image {

        inline-size: 180px;

        block-size: 180px;

        object-fit: cover;

        border-radius: 25px;

        box-shadow:
            0 10px 25px rgba(0,
                0,
                0,
                0.08);

    }


    .save-btn {

        block-size: 58px;

        inline-size: 100%;

        border: none;

        border-radius: 18px;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        color: white;

        font-weight: 700;

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


    @media(max-inline-size:768px) {

        .edit-header {

            padding: 30px;

            text-align: center;

        }

        .admin-box {

            padding: 20px;

        }

        .preview-image {

            inline-size: 100%;

            block-size: 230px;

        }

    }
</style>



<div class="edit-header">

    <h2>

        ✏️ Mahsulot tahrirlash

    </h2>

    <p>

        Mahsulot ma'lumotlarini yangilang

    </p>

</div>



<div class="admin-box">

    <form method="POST" enctype="multipart/form-data">

        <input name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>"
            placeholder="📱 Mahsulot nomi" required>


        <textarea name="description" class="form-control" placeholder="📝 Tavsif"
            required><?= htmlspecialchars($product['description']) ?></textarea>


        <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" placeholder="💰 Narx"
            required>


        <select name="category_id" class="form-control">

            <?php while ($cat = $categories->fetch()): ?>

                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? "selected" : "" ?>>

                    <?= $cat['name'] ?>

                </option>

            <?php endwhile; ?>

        </select>


        <div class="preview-box">

            <h5>

                📷 Joriy rasm

            </h5>

            <img src="../uploads/<?= $product['image'] ?>" class="preview-image">

        </div>


        <input type="file" name="image" class="form-control">


        <button name="save" class="save-btn">

            🚀 O'zgarishlarni saqlash

        </button>

    </form>

</div>