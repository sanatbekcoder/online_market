<?php

session_start();

require_once "../config/database.php";


if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] =
        bin2hex(
            random_bytes(32)
        );

}


if (isset($_POST['register'])) {


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


    $name = htmlspecialchars(
        trim($_POST['name'])
    );

    $email = filter_var(

        trim($_POST['email']),

        FILTER_SANITIZE_EMAIL

    );

    $phone = preg_replace(

        '/[^0-9+]/',

        '',

        $_POST['phone']

    );

    $password =
        $_POST['password'];


    /* Parol tekshirish */

    if (
        strlen(
            $password
        ) < 6
    ) {

        $error =
            "Parol kamida 6 ta belgidan iborat bo'lsin";

    } else {


        $check = $conn->prepare(

            "SELECT id
FROM users
WHERE email=?"

        );

        $check->execute([
            $email
        ]);


        if (
            $check->rowCount() > 0
        ) {

            $error =
                "Bu email oldin ishlatilgan";

        } else {


            $hashedPassword =
                password_hash(

                    $password,

                    PASSWORD_DEFAULT

                );


            $sql = $conn->prepare(

                "INSERT INTO users

(name,email,phone,password)

VALUES(?,?,?,?)"

            );


            $sql->execute([

                $name,

                $email,

                $phone,

                $hashedPassword

            ]);


            header(
                "Location: login.php"
            );

            exit();

        }

    }

}


include "../includes/header.php";

?>


<style>
    body {

        background:
            linear-gradient(180deg,
                #f8fbff,
                #edf4ff);

    }


    /* REGISTER */

    .register-box {

        max-inline-size: 550px;

        margin: 60px auto;

        background:
            linear-gradient(135deg,
                rgba(255, 255, 255, .95),
                rgba(239, 246, 255, .95));

        backdrop-filter: blur(15px);

        padding: 45px;

        border-radius: 35px;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.10);

        position: relative;

        overflow: hidden;

    }


    /* DEKOR */

    .register-box::before {

        content: "";

        position: absolute;

        inline-size: 260px;
        block-size: 260px;

        border-radius: 50%;

        background:
            rgba(59,
                130,
                246,
                0.08);

        inset-block-start: -100px;
        inset-inline-end: -80px;

    }


    .register-title {

        text-align: center;

        margin-block-end: 30px;

        position: relative;

        z-index: 2;

    }


    .register-icon {

        font-size: 55px;

        display: block;

        margin-block-end: 15px;

    }


    .register-title h2 {

        font-weight: 700;

        color: #1e293b;

        margin-block-end: 8px;

    }


    .register-title p {

        color: #64748b;

    }


    /* INPUT */

    .form-control {

        block-size: 58px;

        border-radius: 16px;

        border: 1px solid #dbeafe;

        padding: 15px;

        background: white;

        transition: .3s;

        position: relative;

        z-index: 2;

    }


    .form-control:focus {

        border-color: #2563eb;

        box-shadow:
            0 0 0 4px rgba(37,
                99,
                235,
                0.10);

    }


    /* BUTTON */

    .btn-success {

        block-size: 58px;

        inline-size: 100%;

        border: none;

        border-radius: 16px;

        font-weight: 600;

        background:
            linear-gradient(135deg,
                #2563eb,
                #3b82f6);

        transition: .3s;

        position: relative;

        z-index: 2;

    }


    .btn-success:hover {

        background:
            linear-gradient(135deg,
                #1d4ed8,
                #2563eb);

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

        border-radius: 15px;

        position: relative;

        z-index: 2;

    }


    /* LINK */

    .auth-link {

        color: #2563eb;

        font-weight: 600;

        text-decoration: none;

    }


    .auth-link:hover {

        text-decoration: underline;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .register-box {

            margin: 40px auto;

            padding: 25px;

        }

    }
</style>



<div class="register-box">

    <div class="register-title">

        <span class="register-icon">

            🚀

        </span>

        <h2>

            Tech Store Account

        </h2>

        <p>

            Bir necha soniyada yangi hisob yarating

        </p>

    </div>


    <?php if (isset($error)): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <form method="POST">

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


        <input name="name" class="form-control mb-3" placeholder="👤 Ismingiz" required>


        <input type="email" name="email" class="form-control mb-3" placeholder="📧 Email manzilingiz" required>


        <input name="phone" class="form-control mb-3" placeholder="📱 +998901234567" required>


        <input type="password" name="password" class="form-control mb-3" placeholder="🔒 Parol yarating" required>


        <button name="register" class="btn btn-success">

            Ro'yxatdan o'tish →

        </button>

    </form>


    <div class="text-center mt-4">

        Hisobingiz bormi?

        <a href="login.php" class="auth-link">

            Kirish

        </a>

    </div>

</div>