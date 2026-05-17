<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


if (
    !isset($_GET['id'])
    ||
    !is_numeric($_GET['id'])
) {

    header(
        "Location: cart.php"
    );

    exit();

}


$id =
    (int) $_GET['id'];

$user_id =
    $_SESSION['user_id'];


/* Savatcha elementi usernikimi */

$check =
    $conn->prepare(

        "SELECT id

FROM cart

WHERE id=?
AND user_id=?"

    );


$check->execute([

    $id,
    $user_id

]);


$item =
    $check->fetch();


if (!$item) {

    die(
        "Ruxsat yo'q"
    );

}


/* O'chirish */

$sql =
    $conn->prepare(

        "DELETE
FROM cart

WHERE id=?
AND user_id=?"
);


$sql->execute([

    $id,
    $user_id

]);


header(
    "Location: cart.php?deleted=1"
);

exit();