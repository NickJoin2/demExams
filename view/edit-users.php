<?php
require_once '../vendor/AdminController.php';
require_once '../vendor/helpers.php';

isGuest();


$administration = new AdminController();
$data = $administration->adminUserEdit();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="background: #f5f5f5;">

<?php include './header.php'; ?>


<main class="mt-5 mb-5">
    <div class="container">

        <div class="border border-secondary border-opacity-50 rounded p-4">
            <h2 class="text-center mb-4">Пользователь <?= $data[0]['id'] ?></h2>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="fio">ФИО</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" value="<?= $data[0]['last_name'] . ' ' .$data[0]['first_name'] . ' ' . $data[0]['middle_name'];?>" type="text"
                              name="fio"
                              id="fio" disabled>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" value="<?= $data[0]['email'] ?>" type="text"
                              name="email"
                              id="email" disabled>
                </div>
            </div>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="login">Login</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $data[0]['login']; ?>" name="login"
                           id="login" disabled>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="phone">Телефон</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $data[0]['phone_mask'] ?>" name="phone"
                           id="phone" disabled>
                </div>
            </div>

            <?php

            echo $auth ? '<div class="d-flex gap-2 pt-3">
                <form class="w-100" action="../vendor/AdminController.php" method="POST">
                    <input type="hidden" name="usersCreate" value="'. $data[0]['id'] .'">
                    <input class="btn btn-success w-100" type="submit" name="userCreateBtn" value="Пользователь">
                </form>

                <form class="w-100" action="../vendor/AdminController.php" method="POST">
                    <input type="hidden" name="adminCreate" value="'. $data[0]['id'] .'">
                    <input class="btn btn-info w-100" type="submit" name="adminCreateBtn" value="Админ">
                </form>
                
                <form class="w-100" action="../vendor/AdminController.php" method="POST">
                    <input type="hidden" name="superAdminCreate" value="'. $data[0]['id'] .'">
                    <input class="btn btn-danger w-100" type="submit" name="superAdminCreateBtn" value="Супер админ">
                </form>

                <form class="w-100" action="../vendor/AdminController.php" method="POST">
                    <input type="hidden" name="block" value="'. $data[0]['id'] .'">
                    <input class="btn btn-warning w-100" type="submit" name="blockBtn" value="Заблокировать">
                </form>
            </div>' : ''; ?>

        </div>
    </div>


</main>


<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</body>
</html>