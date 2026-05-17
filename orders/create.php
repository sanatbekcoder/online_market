<?php

require_once "../includes/auth.php";
require_once "../config/database.php";
require_once "../bot/send.php";


if (
    $_SERVER['REQUEST_METHOD']
    !== "POST"
) {

    header(
        "Location: ../cart/cart.php"
    );

    exit();

}


$user_id =
    $_SESSION['user_id'];


/* Tozalash */

$fullname =
    htmlspecialchars(
        trim(
            $_POST['fullname']
            ?? ''
        )
    );

$phone =
    preg_replace(
        '/[^0-9+]/',
        '',
        $_POST['phone']
        ?? ''
    );

$address =
    htmlspecialchars(
        trim(
            $_POST['address']
            ?? ''
        )
    );

$note =
    htmlspecialchars(
        trim(
            $_POST['note']
            ?? ''
        )
    );



/* Majburiy maydon */

if (

    empty($fullname)
    ||
    empty($phone)
    ||
    empty($address)

) {

    die(
        "Ma'lumotlarni to'liq kiriting"
    );

}



/* Savatcha tekshir */

$check =
    $conn->prepare(

        "SELECT COUNT(*)

FROM cart

WHERE user_id=?"

    );

$check->execute([
    $user_id
]);


if (
    $check->fetchColumn() == 0
) {

    die(
        "Savatcha bo'sh"
    );

}



$conn->beginTransaction();


try {


    /* Buyurtma */

    $order =
        $conn->prepare(

            "INSERT INTO orders

(

user_id,
fullname,
phone,
address,
note,
status

)

VALUES

(

?,
?,
?,
?,
?,
'korib_chiqilyapdi'

)"

        );


    $order->execute([

        $user_id,
        $fullname,
        $phone,
        $address,
        $note

    ]);


    $order_id =
        $conn->lastInsertId();



    /* Savat mahsulotlari */

    $cart =
        $conn->prepare(

            "SELECT

cart.*,

products.name,

products.price

FROM cart

JOIN products

ON cart.product_id=
products.id

WHERE user_id=?"

        );


    $cart->execute([
        $user_id
    ]);


    $productText = "";

    $total = 0;



    while (
        $item =
        $cart->fetch()
    ) {


        $sum =

            $item['price']

            *

            $item['quantity'];


        $total +=
            $sum;



        $productText .=

            "📦 "

            . $item['name']

            . " x "

            . $item['quantity']

            . " = "

            . number_format(
                $sum
            )

            . " so'm\n";



        $insert =
            $conn->prepare(

                "INSERT INTO order_items

(

order_id,
product_id,
quantity,
price

)

VALUES

(

?,
?,
?,
?

)"

            );


        $insert->execute([

            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']

        ]);

    }



    /* Savatchani tozalash */

    $delete =
        $conn->prepare(

            "DELETE
FROM cart

WHERE user_id=?"

        );


    $delete->execute([
        $user_id
    ]);



    $conn->commit();



    /* Telegram */

    try {

        sendTelegram(

            $order_id,

            $fullname,

            $phone,

            $address,

            $productText,

            $total

        );

    } catch (Exception $e) {
    }



    /* Buyurtmalar sahifasi */

    header(

        "Location: my_orders.php?success=1"

    );

    exit();


} catch (Exception $e) {


    $conn->rollBack();


    die(

        "Xatolik: "

        .

        htmlspecialchars(

            $e->getMessage()

        )

    );

}