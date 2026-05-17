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


$search =
    trim($_GET['search'] ?? "");


if ($search) {

    $sql = $conn->prepare(

        "SELECT

users.*,

COUNT(
DISTINCT orders.id
) as order_count,

COALESCE(

SUM(
order_items.price
*
order_items.quantity
),

0

) as total_spent

FROM users

LEFT JOIN orders

ON users.id=
orders.user_id

LEFT JOIN order_items

ON orders.id=
order_items.order_id

WHERE

users.name LIKE ?

OR

users.email LIKE ?

OR

users.phone LIKE ?

GROUP BY users.id

ORDER BY users.id DESC"

    );


    $term =
        "%" . $search . "%";


    $sql->execute([

        $term,
        $term,
        $term

    ]);

} else {


    $sql =
        $conn->query(

            "SELECT

users.*,

COUNT(
DISTINCT orders.id
) as order_count,

COALESCE(

SUM(
order_items.price
*
order_items.quantity
),

0

) as total_spent

FROM users

LEFT JOIN orders

ON users.id=
orders.user_id

LEFT JOIN order_items

ON orders.id=
order_items.order_id

GROUP BY users.id

ORDER BY users.id DESC"

        );

}


include "../includes/header.php";

?>
<style>
    .users-box {

        background: white;

        padding: 35px;

        border-radius: 35px;

        box-shadow:
            0 20px 50px rgba(37,
                99,
                235,
                0.08);

    }


    /* HEADER */

    .users-header {

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        padding: 35px;

        border-radius: 30px;

        color: white;

        margin-block-end: 30px;

        position: relative;

        overflow: hidden;

    }


    .users-header::before {

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


    .users-header h2 {

        font-size: 35px;

        font-weight: 700;

        position: relative;

        z-index: 2;

    }


    /* SEARCH */

    .search-box {

        display: flex;

        gap: 10px;

        margin-block-start: 20px;

        position: relative;

        z-index: 2;

    }


    .search-box input {

        block-size: 56px;

        border: none;

        border-radius: 18px;

        padding: 15px;

    }


    .search-btn {

        inline-size: 150px;

        border-radius: 18px;

        background: white;

        color: #2563eb;

        font-weight: 700;

        border: none;

    }


    /* CARD */

    .user-card {

        padding: 25px;

        background: #f8fbff;

        border-radius: 30px;

        margin-block-end: 20px;

        transition: .35s;

        border: 1px solid #e0ecff;

    }


    .user-card:hover {

        transform:
            translateY(-6px);

        box-shadow:
            0 15px 35px rgba(37,
                99,
                235,
                0.10);

    }


    .user-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 15px;

    }


    /* AVATAR */

    .avatar {

        inline-size: 70px;

        block-size: 70px;

        border-radius: 50%;

        background:
            linear-gradient(135deg,
                #2563eb,
                #60a5fa);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 26px;

        font-weight: 700;

        color: white;

        box-shadow:
            0 10px 25px rgba(37,
                99,
                235,
                0.20);

    }


    .user-info h5 {

        font-weight: 700;

        margin-block-end: 5px;

    }


    .user-info div {

        color: #64748b;

    }


    /* BADGE */

    .role-badge {

        padding: 10px 16px;

        border-radius: 30px;

        font-weight: 700;

    }


    .admin {

        background: #ffe2e2;

        color: #dc2626;

    }


    .user {

        background: #dbeafe;

        color: #2563eb;

    }


    /* STATS */

    .stats-box {

        margin-block-start: 20px;

        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 15px;

    }


    .stat {

        padding: 20px;

        border-radius: 20px;

        background: white;

        text-align: center;

        box-shadow:
            0 8px 20px rgba(0,
                0,
                0,
                0.04);

    }


    .stat h4 {

        font-weight: 700;

        margin-block-start: 10px;

    }


    .money {

        font-size: 24px;

        font-weight: 700;

        color: #2563eb;

    }


    /* MOBILE */

    @media(max-inline-size:768px) {

        .users-box {

            padding: 20px;

        }

        .user-top {

            flex-direction: column;

            text-align: center;

        }

        .search-box {

            flex-direction: column;

        }

        .search-btn {

            inline-size: 100%;

        }

        .stats-box {

            grid-template-columns: 1fr;

        }

    }
</style>



<div class="users-box">

    <div class="users-header">

        <h2>

            👥 Foydalanuvchilar paneli

        </h2>

        <p>

            Barcha foydalanuvchilar va statistika

        </p>


        <form method="GET" class="search-box">

            <input type="text" name="search" class="form-control" placeholder="🔍 Ism, email yoki telefon..."
                value="<?= htmlspecialchars($search) ?>">


            <button class="search-btn">

                Qidirish

            </button>

        </form>

    </div>



    <?php while ($user = $sql->fetch()): ?>

        <div class="user-card">

            <div class="user-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="avatar">

                        <?= strtoupper(substr($user['name'], 0, 1)) ?>

                    </div>


                    <div class="user-info">

                        <h5>

                            <?= htmlspecialchars($user['name']) ?>

                        </h5>

                        <div>

                            📧 <?= htmlspecialchars($user['email']) ?>

                        </div>

                        <div>

                            📱 <?= htmlspecialchars($user['phone']) ?>

                        </div>

                    </div>

                </div>


                <?php if ($user['role'] == "admin"): ?>

                    <div class="role-badge admin">

                        ⚙ Admin

                    </div>

                <?php else: ?>

                    <div class="role-badge user">

                        👤 User

                    </div>

                <?php endif; ?>

            </div>


            <div class="stats-box">

                <div class="stat">

                    <div>

                        📦 Buyurtmalar

                    </div>

                    <h4>

                        <?= $user['order_count'] ?>

                    </h4>

                </div>


                <div class="stat">

                    <div>

                        💰 Xarid summasi

                    </div>

                    <div class="money">

                        <?= number_format($user['total_spent']) ?>

                        so'm

                    </div>

                </div>

            </div>

        </div>

    <?php endwhile; ?>

</div>