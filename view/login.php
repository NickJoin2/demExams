<?php
session_start();
require_once '../vendor/helpers.php';

isAuth();

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
        <h1 class="text-center mb-4">Авторизация</h1>

        <div class="mb-3">
            <label class="form-label" for="verifyData">login</label>
            <input class="form-control" type="text" name="verifyData" id="verifyData" <?php echo mayBeHasError('verify'); ?>  placeholder="Введите login...">
            <?php echo getErrorMessage('verify'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Пароль</label>
            <input class="form-control" type="password" name="password"  <?php echo mayBeHasError('password') ?> id="password" placeholder="Введите пароль...">
            <?php echo getErrorMessage('password'); ?>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <a class="ps-1" href="./registration.php">У вас еще нет аккаунта?</a>
            <a href="./reset-password.php">Забыли пароль?</a>
        </div>

        <div class="text-center mt-3">
            <button type="reset" class="btn btn-danger fw-bold">Очистить</button>
            <button type="submit" name="auth" class="btn btn-primary fw-bold">Войти</button>
        </div>

    </form>
</main>

<?php unset($_SESSION['validation']); ?>


<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
