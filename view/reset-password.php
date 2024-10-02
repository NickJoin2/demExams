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
    <title>Изменить пароль</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include './header.php' ?>

<main class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <form class="border border-secondary border-opacity-50 rounded p-4" action="../vendor/UserController.php" method="POST" style="width: 600px;">
        <h1 class="text-center mb-4">Смена пароля</h1>

        <div class="mb-3">
            <label class="form-label" for="verifyLoginReset">Введите login</label>
            <input class="form-control" type="text" name="verifyLoginReset" id="verifyLoginReset" <?php echo mayBeHasError('login'); ?> placeholder="Подтвердите login...">
            <?= getErrorMessage('login'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="verifyEmailReset">Введите email</label>
            <input class="form-control" type="email" name="verifyEmailReset" id="verifyEmailReset" <?php echo mayBeHasError('verifyEmailReset'); ?> <?php echo mayBeHasError('emailReset'); ?> placeholder="Введите email...">
            <?= getErrorMessage('verifyEmailReset'); ?>
            <?= getErrorMessage('emailReset'); ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="verifyPhoneReset">Введите номер телефона</label>
            <input class="form-control" type="text" name="verifyPhoneReset" id="verifyPhoneReset" <?php echo mayBeHasError('phone'); ?> placeholder="Введите номер телефона...">
            <?= getErrorMessage('phone'); ?>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <button type="reset" class="btn btn-danger">Очистить</button>
            <button type="submit" class="btn btn-success" name="resetPasswordInfo">Изменить</button>
        </div>

    </form>
</main>

<?php unset($_SESSION); ?>

<script>
    const input = document.querySelector('#verifyPhoneReset');

    input.addEventListener('input', function (e) {

        let value = input.value.replace(/\D/g, '');

        if (value.length > 1) {
            value = '+7 (' + value.slice(1);
        } else if (value.length === 1) {
            value = '+7 ((' + value;
        }

        if (value.length > 7) {
            value = value.slice(0, 7) + ') - ' + value.slice(7);
        }
        if (value.length > 14) {
            value = value.slice(0, 14) + ' - ' + value.slice(14);
        }

        if (value.length > 19) {
            value = value.slice(0, 19) + ' - ' + value.slice(19);
        }

        if (value.length > 24) {
            value = value.slice(0, 24)
        }
        input.value = value;
    });

</script>

<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

