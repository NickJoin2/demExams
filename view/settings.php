<?php
session_start();
require_once '../vendor/helpers.php';

isGuest();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Настройки</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>

<?php include './header.php'; ?>


<main class="d-flex justify-content-center align-items-center" style="min-height: 93vh;">
    <div class="container">
        <h1 class="text-center pt-4 mb-5">Ваш профиль</h1>

        <form class="border border-secondary border-opacity-50 rounded p-4" action="../vendor/UserController.php"
              method="POST" enctype="multipart/form-data">
            <div class="row mb-2">

                <div class="col-6 mt-2">
                    <label class="form-label" for="loginSetting">Login</label>
                    <input class="form-control" type="text" value="<?= $_SESSION['auth']['login'] ?>"
                        <?php echo mayBeHasError('login') ?>
                           name="loginSetting" id="loginSetting" disabled>
                    <?php echo getErrorMessage('login'); ?>
                </div>
                <div class="col-6 mt-2">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" value="<?= $_SESSION['auth']['email'] ?>"
                           name="emailSetting" id="email"
                           <?php echo mayBeHasError('email') ?>
                           disabled>
                    <?php echo getErrorMessage('email'); ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-6 mt-2">
                    <label class="form-label" for="first_name">Имя</label>
                    <input class="form-control" type="text" value="<?= $_SESSION['auth']['first_name'] ?>"
                        <?php echo mayBeHasError('first_name') ?>
                           name="first_nameSetting" id="first_name" disabled>
                    <?php echo getErrorMessage('first_name'); ?>
                </div>
                <div class="col-6 mt-2">
                    <label class="form-label" for="last_name">Фамилия</label>
                    <input class="form-control" type="text" value="<?= $_SESSION['auth']['last_name'] ?>"
                        <?php echo mayBeHasError('last_name') ?>
                           name="last_nameSetting" id="last_name" disabled>
                    <?php echo getErrorMessage('last_name'); ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-6 mt-2">
                    <label class="form-label" for="middle_name">Отчество</label>
                    <input class="form-control" type="text" value="<?= $_SESSION['auth']['middle_name'] ?>"
                        <?php echo mayBeHasError('middle_name') ?>
                           name="middle_nameSetting" id="middle_name" disabled>
                    <?php echo getErrorMessage('middle_name'); ?>
                </div>
                <div class="col-6 mt-2">
                    <label class="form-label" for="phone">Номер телефона</label>
                    <input class="form-control" type="text" value="<?= $_SESSION['auth']['phone_mask'] ?>"
                        <?php echo mayBeHasError('phone_mask') ?>
                           name="phoneSetting" id="phone" disabled>
                    <?php echo getErrorMessage('phone_mask'); ?>
                </div>
            </div>

            <div class="mt-3 d-flex align-items-center gap-4 ">
                <div class="mt-2 col-6 text-center" style="margin: 0 auto;">
                    <img src="../uploads/<?= $_SESSION['auth']['photo_path'] ?>" alt="User Photo"
                         style="width: 311px; height: 311px; border-radius: 50%">
                </div>
                <div class="col-6 mt-2">
                    <label class="form-label" for="photo">Фотография</label>
                    <input class="form-control" type="file" name="photoProfileSetting" id="photo" disabled>
                    <?php echo getErrorMessage('password'); ?>
                </div>
            </div>

            <div class="d-flex gap-5 pt-3">
                <button type="reset" class="btn btn-primary w-50" id="refactor">Изменить</button>
                <button type="submit" class="btn btn-success w-50" id="save" name="settingUpdate" disabled>Сохранить</button>
            </div>
        </form>

        <?php unset($_SESSION['validation']) ?>
    </div>
</main>

<script>
    const formControls = document.querySelectorAll('.form-control');
    const refactorButton = document.querySelector('#refactor');
    const saveButton = document.querySelector('#save')

    let isDisabled = true;

    refactorButton.addEventListener('click', () => {
        isDisabled = !isDisabled;

        formControls.forEach(control => {
            control.disabled = isDisabled;
        });

        saveButton.disabled = isDisabled

    });


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
