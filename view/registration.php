<?php
session_start();
require_once '../vendor/helpers.php';

isAuth();

$reg_info = $_SESSION['user_info'];

if (!$reg_info) {
    redirect("./registration-info.php");
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include './header.php' ?>

<main class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <form class="border border-secondary border-opacity-50 rounded p-4" action="../vendor/UserController.php" method="POST" style="width: 600px;">
        <h1 class="text-center mb-4">Регистрация</h1>

        <div class="mb-3">
            <label class="form-label" for="login">Login</label>
            <input class="form-control" type="text" name="login" id="login"  <?= mayBeHasError('login') ?> placeholder="Введите login...">
            <?= getErrorMessage('login'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="text" name="email" id="email"  <?= mayBeHasError('email') ?> placeholder="Введите email...">
            <?= getErrorMessage('email'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Пароль</label>
            <input class="form-control" type="password" name="password"  <?= mayBeHasError('password') ?> id="password" placeholder="Введите пароль...">
            <?= getErrorMessage('password'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="passwordConfirm">Подтвердите пароль</label>
            <input class="form-control" type="password" name="passwordConfirm"  <?= mayBeHasError('passwordConfirm') ?> id="passwordConfirm" placeholder="Подтвердите пароль...">
            <?= getErrorMessage('passwordConfirm'); ?>
        </div>

        <div class="mt-4">
            <a class="ps-1" href="./login.php">У вас уже есть аккаунт?</a>
        </div>

        <div class="text-center mt-3 d-flex gap-3">
            <button type="reset" class="btn btn-danger fw-bold w-50">Очистить</button>
            <button type="submit" name="registrationCreate" class="btn btn-primary fw-bold w-50">Зарегистрироваться</button>
        </div>

    </form>
</main>

<?php unset($_SESSION); ?>


<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

