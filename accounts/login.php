<?php

session_start();

require_once "../config/database.php";


if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] =
        bin2hex(
            random_bytes(32)
        );

}


/* Login urinish limiti */

if (
    !isset(
    $_SESSION['login_attempt']
)
) {

    $_SESSION['login_attempt'] = 0;

}


if (
    !isset(
    $_SESSION['lock_time']
)
) {

    $_SESSION['lock_time'] = 0;

}


if (
    $_SESSION['login_attempt'] >= 5
    &&
    time() -
    $_SESSION['lock_time']
    < 300
) {

    die(
        "5 daqiqa kuting"
    );

}


if (isset($_POST['login'])) {


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


    $email = filter_var(

        trim($_POST['email']),

        FILTER_SANITIZE_EMAIL

    );

    $password =
        $_POST['password'];


    $sql = $conn->prepare(

        "SELECT *
FROM users
WHERE email=?
LIMIT 1"

    );

    $sql->execute([
        $email
    ]);

    $user = $sql->fetch();


    if (

        $user
        &&

        password_verify(

            $password,

            $user['password']

        )

    ) {

        session_regenerate_id(
            true
        );


        $_SESSION['user_id'] =
            $user['id'];

        $_SESSION['user_name'] =
            $user['name'];

        $_SESSION['role'] =
            $user['role'];


        /* hisobni tiklash */

        $_SESSION['login_attempt'] = 0;


        if (
            $user['role'] == "admin"
        ) {

            header(

                "Location: ../admin/dashboard.php"

            );

        } else {

            header(

                "Location: ../index.php"

            );

        }

        exit();


    } else {


        $_SESSION['login_attempt']++;

        $_SESSION['lock_time'] =
            time();

        $error =
            "Email yoki parol noto'g'ri";

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


    /* LOGIN */

    .login-box {

        max-inline-size: 520px;

        margin: 70px auto;

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

    .login-box::before {

        content: "";

        position: absolute;

        inline-size: 250px;
        block-size: 250px;

        border-radius: 50%;

        background:
            rgba(59,
                130,
                246,
                0.08);

        inset-block-start: -100px;
        inset-inline-end: -80px;

    }


    .login-title {

        text-align: center;

        margin-block-end: 30px;

        position: relative;

        z-index: 2;

    }


    .login-icon {

        font-size: 55px;

        margin-block-end: 15px;

        display: block;

    }


    .login-title h2 {

        font-weight: 700;

        color: #1e293b;

        margin-block-end: 8px;

    }


    .login-title p {

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

        .login-box {

            margin: 40px auto;

            padding: 25px;

        }

    }
</style>



<div class="login-box">

    <div class="login-title">

        <span class="login-icon">

            🚀

        </span>

        <h2>

            Tech Store Login

        </h2>

        <p>

            Hisobingizga xavfsiz kirish

        </p>

    </div>


    <?php if (isset($error)): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <form method="POST">

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


        <input type="email" name="email" class="form-control mb-3" placeholder="📧 Email manzilingiz" required>


        <input type="password" name="password" class="form-control mb-3" placeholder="🔒 Parolingiz" required>


        <button name="login" class="btn btn-success">

            Kirish →

        </button>

    </form>


    <div class="text-center mt-4">

        Hisobingiz yo'qmi?

        <a href="register.php" class="auth-link">

            Ro'yxatdan o'tish

        </a>

    </div>

</div>