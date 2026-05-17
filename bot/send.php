<?php

require_once "../config/database.php";

function sendTelegram(
    $order_id,
    $fullname,
    $phone,
    $address,
    $productText,
    $total
) {

    global $conn;


    $token = "token";
    $chat_id = "tg_id";


    /* Bugungi statistika */

    $todayOrders =
        $conn->query(

            "SELECT COUNT(*)

FROM orders

WHERE DATE(created_at)=CURDATE()"

        )->fetchColumn();


    $todayMoney =
        $conn->query(

            "SELECT
COALESCE(

SUM(
order_items.price*
order_items.quantity
),

0

)

FROM orders

JOIN order_items

ON orders.id=
order_items.order_id

WHERE DATE(
orders.created_at
)=CURDATE()"

        )->fetchColumn();



    $text =

        "🛒 YANGI BUYURTMA\n\n" .

        "🆔 Buyurtma: #" . $order_id . "\n\n" .

        "👤 Ism: " . $fullname . "\n" .

        "📞 Telefon: " . $phone . "\n" .

        "📍 Manzil:\n" . $address . "\n\n" .

        "📦 Mahsulotlar:\n\n" .

        $productText . "\n" .

        "━━━━━━━━━━\n" .

        "💰 Jami: "

        . number_format($total)

        . " so'm\n\n" .

        "📊 BUGUNGI STATISTIKA\n\n" .

        "📦 Buyurtmalar: "

        . $todayOrders . "\n" .

        "💵 Savdo: "

        . number_format(
            $todayMoney
        )

        . " so'm";



    $url =

        "https://api.telegram.org/bot"

        . $token .

        "/sendMessage";


    $data = [

        "chat_id" => $chat_id,

        "text" => $text

    ];


    $options = [

        "http" => [

            "header" =>
                "Content-Type: application/x-www-form-urlencoded\r\n",

            "method" => "POST",

            "content" =>
                http_build_query(
                    $data
                )

        ]

    ];


    $context =
        stream_context_create(
            $options
        );


    file_get_contents(

        $url,

        false,

        $context

    );

}