<?php
session_start();
require '../vendor/helpers.php';

isAuth();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Изменить пароль</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include './header.php' ?>

<main class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <form class="border border-secondary border-opacity-50 rounded p-4" action="../vendor/UserController.php" method="POST" style="width: 600px;">
        <h1 class="text-center mb-4">Изменить пароль</h1>

        <div class="mb-3">
            <label class="form-label" for="newPasswordUser">Введите новый пароль</label>
            <input class="form-control" type="password" name="newPassword" id="newPassword" <?php echo mayBeHasError('newPassword') ?> placeholder="Введите старый пароль...">
            <?php echo getErrorMessage('newPassword'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="newPasswordUserConfirm">Подтвердите пароль</label>
            <input class="form-control" type="password" name="newPasswordConfirm" id="newPasswordConfirm" <?php echo mayBeHasError('newPasswordConfirm') ?> placeholder="Введите новый пароль...">
            <?php echo getErrorMessage('newPasswordConfirm'); ?>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <button type="reset" class="btn btn-danger">Очистить</button>
            <button type="submit" class="btn btn-success" name="newPasswordUserBtn">Изменить</button>
        </div>

    </form>
</main>

<?php unset($_SESSION['validation']); ?>

<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

