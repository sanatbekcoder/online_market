<?php

session_start();

require_once "../includes/auth.php";
require_once "../config/database.php";


$user_id = $_SESSION['user_id'];

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] =
        bin2hex(
            random_bytes(32)
        );

}


/* User olish */

$sql = $conn->prepare(

    "SELECT *
FROM users
WHERE id=?"

);

$sql->execute([
    $user_id
]);

$user = $sql->fetch();



if (isset($_POST['save'])) {


    if (

        !hash_equals(

            $_SESSION['csrf_token'],

            $_POST['csrf_token']

        )

    ) {

        die(
            "So'rov rad etildi"
        );

    }


    $name =
        trim($_POST['name']);

    $phone =
        trim($_POST['phone']);

    $current_password =
        $_POST['current_password'];

    $new_password =
        $_POST['new_password'];


    /* Username va telefon */

    $update = $conn->prepare(

        "UPDATE users

SET name=?,
phone=?

WHERE id=?"

    );

    $update->execute([

        $name,
        $phone,
        $user_id

    ]);


    /* Parol o'zgarishi */

    if (
        !empty(
        $current_password
    )
        &&
        !empty(
        $new_password
    )
    ) {

        if (

            password_verify(

                $current_password,

                $user['password']

            )

        ) {

            if (
                strlen(
                    $new_password
                ) >= 6
            ) {

                $newHash =
                    password_hash(

                        $new_password,

                        PASSWORD_DEFAULT

                    );

                $change = $conn->prepare(

                    "UPDATE users

SET password=?

WHERE id=?"

                );

                $change->execute([

                    $newHash,
                    $user_id

                ]);

                $success =
                    "Parol muvaffaqiyatli yangilandi";

            } else {

                $error =
                    "Yangi parol kamida 6 ta belgi bo'lsin";

            }

        } else {

            $error =
                "Hozirgi parol noto'g'ri";

        }

    }


    $_SESSION['user_name'] = $name;

    $success =
        "Profil yangilandi";


    $sql->execute([
        $user_id
    ]);

    $user =
        $sql->fetch();

}


include "../includes/header.php";

?>
<style>
    .profile-page {

        padding: 20px 0;

    }


    /* MAIN */

    .profile-box {

        max-inline-size: 850px;

        margin: 50px auto;

        padding: 0;

        border-radius: 35px;

        overflow: hidden;

        background: white;

        box-shadow:
            0 25px 60px rgba(37,
                99,
                235,
                0.12);

    }


    /* TOP */

    .profile-top {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 50px 40px;

        text-align: center;

        position: relative;

        overflow: hidden;

    }


    .profile-top::before {

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


    .profile-top::after {

        content: "";

        position: absolute;

        inline-size: 200px;
        block-size: 200px;

        border-radius: 50%;

        background:
            rgba(255,
                255,
                255,
                0.08);

        inset-block-end: -100px;
        inset-inline-start: -50px;

    }


    /* AVATAR */

    .avatar {

        inline-size: 110px;

        block-size: 110px;

        margin: auto;

        border-radius: 50%;

        background: white;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 40px;

        font-weight: 700;

        color: #2563eb;

        box-shadow:
            0 10px 25px rgba(0,
                0,
                0,
                0.15);

        margin-block-end: 20px;

        position: relative;

        z-index: 2;

    }


    .profile-name {

        color: white;

        font-size: 30px;

        font-weight: 700;

        position: relative;

        z-index: 2;

    }


    .profile-sub {

        color: rgba(255,
                255,
                255,
                0.85);

        position: relative;

        z-index: 2;

    }


    /* BODY */

    .profile-content {

        padding: 40px;

    }


    /* LABEL */

    .profile-content label {

        font-weight: 700;

        margin-block-end: 8px;

        color: #334155;

        display: block;

    }


    /* INPUT */

    .form-control {

        block-size: 58px;

        border-radius: 16px;

        border: 1px solid #dbeafe;

        padding: 15px;

        transition: .3s;

    }


    .form-control:focus {

        border-color: #2563eb;

        box-shadow:
            0 0 0 4px rgba(37,
                99,
                235,
                0.10);

    }


    /* PASSWORD SECTION */

    .pass-box {

        background: #f8fbff;

        padding: 25px;

        border-radius: 25px;

        margin-block-start: 25px;

        border: 1px solid #dbeafe;

    }


    .pass-title {

        font-size: 20px;

        font-weight: 700;

        margin-block-end: 20px;

        color: #2563eb;

    }


    /* BUTTON */

    .save-btn {

        block-size: 58px;

        inline-size: 100%;

        border: none;

        border-radius: 16px;

        font-weight: 600;

        color: white;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        transition: .3s;

        margin-block-start: 20px;

    }


    .save-btn:hover {

        transform:
            translateY(-2px);

        box-shadow:
            0 12px 30px rgba(37,
                99,
                235,
                0.25);

    }


    /* ALERT */

    .alert {

        border-radius: 16px;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .profile-content {

            padding: 25px;

        }

        .profile-top {

            padding: 35px 20px;

        }

        .avatar {

            inline-size: 90px;
            block-size: 90px;

            font-size: 30px;

        }

        .profile-name {

            font-size: 24px;

        }

    }
</style>



<div class="profile-box">

    <div class="profile-top">

        <div class="avatar">

            <?= strtoupper(substr($user['name'], 0, 1)) ?>

        </div>

        <div class="profile-name">

            <?= htmlspecialchars($user['name']) ?>

        </div>

        <div class="profile-sub">

            ⚡ Shaxsiy hisob boshqaruvi

        </div>

    </div>


    <div class="profile-content">

        <?php if (isset($error)): ?>

            <div class="alert alert-danger">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <?php if (isset($success)): ?>

            <div class="alert alert-success">

                <?= htmlspecialchars($success) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


            <label>👤 Ism</label>

            <input name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control mb-3" required>


            <label>📧 Email</label>

            <input value="<?= htmlspecialchars($user['email']) ?>" class="form-control mb-3" disabled>


            <label>📱 Telefon</label>

            <input name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control">


            <div class="pass-box">

                <div class="pass-title">

                    🔐 Xavfsizlik

                </div>

                <input type="password" name="current_password" placeholder="Hozirgi parol" class="form-control mb-3">


                <input type="password" name="new_password" placeholder="Yangi parol" class="form-control">

            </div>


            <button name="save" class="save-btn">

                Saqlash →

            </button>

        </form>

    </div>

</div>