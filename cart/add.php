<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


if (
    !isset($_GET['id'])
    ||
    !is_numeric($_GET['id'])
) {

    header(
        "Location: ../products/index.php"
    );

    exit();

}


$product_id =
    (int) $_GET['id'];

$user_id =
    $_SESSION['user_id'];



/* Mahsulot mavjudligini tekshirish */

$product = $conn->prepare(

    "SELECT id
FROM products
WHERE id=?"

);

$product->execute([
    $product_id
]);


if (
    !$product->fetch()
) {

    die(
        "Mahsulot topilmadi"
    );

}



/* Savatchada bormi */

$check = $conn->prepare(

    "SELECT *

FROM cart

WHERE user_id=?
AND product_id=?"

);


$check->execute([

    $user_id,
    $product_id

]);


$item =
    $check->fetch();



if ($item) {


    /* Miqdor oshirish */

    $sql = $conn->prepare(

        "UPDATE cart

SET quantity=
quantity+1

WHERE id=?"

    );


    $sql->execute([

        $item['id']

    ]);


} else {


    /* Yangi qo'shish */

    $sql = $conn->prepare(

        "INSERT INTO cart

(user_id,
product_id,
quantity)

VALUES(?,?,1)"

    );


    $sql->execute([

        $user_id,
        $product_id

    ]);

}


/* Qaytarish */

header(

    "Location: cart.php?success=1"

);

exit();