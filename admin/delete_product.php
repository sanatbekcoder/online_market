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



$get =
    $conn->prepare(

        "SELECT image
FROM products
WHERE id=?"

    );

$get->execute([
    $id
]);

$product =
    $get->fetch();



if (!$product) {

    die(
        "Mahsulot topilmadi"
    );

}



/* Rasmni o'chirish */

$file =
    "../uploads/"
    .
    $product['image'];


if (

    file_exists(
        $file
    )

    &&

    $product['image']
    != "no-image.png"

) {

    unlink(
        $file
    );

}



/* DB dan o'chirish */

$delete =
    $conn->prepare(

        "DELETE
FROM products

WHERE id=?"

    );


$delete->execute([
    $id
]);


header(
    "Location: products.php"
);

exit();