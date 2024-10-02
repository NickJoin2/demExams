<?php
session_start();
require_once '../vendor/helpers.php';

isGuest();

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
<body>

<?php include './header.php'; ?>

<main class="d-flex justify-content-center align-items-center" style="min-height: 73vh;">
    <div class="container">

        <h1 class="text-center mb-5 pb-4">Создание заявления</h1>

        <form class="border border-secondary border-opacity-50 rounded p-4" action="../vendor/ViolationController.php" method="POST"
              enctype="multipart/form-data">
            <h2 class="text-center mb-4">Нарушение</h2>

            <div class="row mb-2">
                <div class="col-6 mt-2">
                    <label class="form-label" for="violation">Нарушение</label>
                    <input class="form-control" type="text" name="violation"
                        <?php echo mayBeHasError('violation') ?>
                           id="violation" placeholder="Пересечение двойной сплошной">
                    <?php echo getErrorMessage('violation'); ?>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="address">Адрес</label>
                    <input class="form-control" type="text" name="address"
                        <?php echo mayBeHasError('address') ?>
                           id="address" placeholder="Проспект Генерала Тюленева">
                    <?php echo getErrorMessage('address'); ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-6 mt-2">
                    <label class="form-label" for="dateViolation">Дата нарушения</label>
                    <input class="form-control" type="datetime-local" name="dateViolation"
                        <?php echo mayBeHasError('dateViolation') ?>
                           id="dateViolation" placeholder="12.12.2024">
                    <?php echo getErrorMessage('dateViolation'); ?>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="photoStatement">Фотографии</label>
                    <input class="form-control" type="file" name="photoStatement[]"
                           id="photoStatement" multiple>
                    <?php echo getErrorMessage('photoStatement'); ?>
                </div>
            </div>

            <div class="mt-2">
                <label class="form-label" for="videoStatement">Видео</label>
                <input class="form-control" type="file" name="videoStatement[]"
                       id="videoStatement" multiple accept="video/*">
                <?php echo getErrorMessage('videoStatement'); ?>
            </div>

            <h2 class="text-center mt-4 mb-4">Машина нарушителя</h2>

            <div class="row mb-2">
                <div class="col-6 mt-2">
                    <label class="form-label" for="carNumber">Номер авто</label>
                    <input class="form-control" type="text" name="carNumber"
                        <?php echo mayBeHasError('carNumber') ?>
                           id="carNumber" placeholder="X 000 XX 00">
                    <?php echo getErrorMessage('carNumber'); ?>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="carBrand">Бренд</label>
                    <input class="form-control" type="text" name="carBrand"
                        <?php echo mayBeHasError('carBrand') ?>
                           id="carBrand" placeholder="BMW, KIA, FIAT">
                    <?php echo getErrorMessage('carBrand'); ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-6 mt-2">
                    <label class="form-label" for="carModel">Модель</label>
                    <input class="form-control" type="text" name="carModel"
                        <?php echo mayBeHasError('carModel') ?>
                           id="carModel" placeholder="Prius, Logan, RAV4">
                    <?php echo getErrorMessage('carModel'); ?>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="carColor">Цвет</label>
                    <input class="form-control" type="text" name="carColor"
                        <?php echo mayBeHasError('carColor') ?> id="carColor" placeholder="Черный, Зеленый, Бежевый">
                    <?php echo getErrorMessage('carColor'); ?>
                </div>
            </div>

            <div class="d-flex gap-5 pt-3">
                <button type="reset" class="btn btn-danger w-50">Очистить</button>
                <button type="submit" class="btn btn-primary w-50" name="violationCreate">Создать</button>
            </div>
        </form>

    </div>
</main>

<?php unset($_SESSION['validation']) ?>

<script>
    const input = document.querySelector('#carNumber');

    input.addEventListener('input', function (e) {
        let value = input.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

        if (value.length > 1) {
            value = value.charAt(0) + ' ' + value.slice(1);
        }
        if (value.length > 5) {
            value = value.slice(0, 5) + ' ' + value.slice(5);
        }
        if (value.length > 8) {
            value = value.slice(0, 8) + ' ' + value.slice(8);
        }
        if (value.length > 11) {
            value = value.slice(0, 11) + ' ' + value.slice(11);
        }

        if (value.length > 11) {
            value = value.slice(0, 11);
        }

        input.value = value;
    });

</script>
<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>