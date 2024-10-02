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
    <title>Регистрация</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="wrapper">
    <?php include './header.php'; ?>
    <div class="content">

        <main class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <form class="border border-secondary border-opacity-50 rounded p-4" action="../vendor/UserController.php"
                  enctype="multipart/form-data"
                  method="POST" style="width: 600px;">
                <h1 class="text-center mb-4">Заявление</h1>

                <div class="mb-4">
                    <label class="form-label" for='first_name'>Имя</label>
                    <input class="form-control" type="text" name='first_name' id='first_name'
                        <?= mayBeHasError('first_name'); ?>
                           placeholder="Введите имя...">
                    <?= getErrorMessage('first_name'); ?>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="last_name">Фамилию</label>
                    <input class="form-control" type="text" name="last_name" id='last_name'
                        <?= mayBeHasError('last_name'); ?>
                           aria-invalid="false" placeholder="Введите фамилии...">
                    <?= getErrorMessage('last_name'); ?>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="middle_name">Отчество</label>
                    <input class="form-control" type="text" name="middle_name" id="middle_name"
                        <?= mayBeHasError('middle_name'); ?>
                           aria-invalid="false" placeholder="Введите ваше отчество...">
                    <?= getErrorMessage('middle_name'); ?>
                </div>


                <div class="mb-4">
                    <label class="form-label" for="phone">Телефон</label>
                    <input class="form-control" type="text" name="phone" id="phone"
                        <?= mayBeHasError('phone'); ?>
                           aria-invalid="false" placeholder="Введите ваш телефон..">
                    <?= getErrorMessage('phone'); ?>
                </div>


                <div class="mb-4">
                    <label for="formFile" class="form-label">Выберите фото</label>
                    <input class="form-control" type="file" name="file" id="formFile">
                    <?= getErrorMessage('file'); ?>
                </div>

                <div class="mt-4">
                    <a class="ps-1" href="./login.php">У вас уже есть аккаунт?</a>
                </div>

                <div class="text-center mt-3 d-flex gap-3">
                    <button class="btn btn-danger w-50" type="reset">Очистить</button>
                    <button class="btn btn-primary w-50" type="submit" name="registerInfo">Продолжить</button>
                </div>

            </form>
        </main>

    </div>
</div>

<?php unset($_SESSION['validation']); ?>

<script>
    const input = document.querySelector('#phone');

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
