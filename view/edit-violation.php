<?php
require_once '../vendor/ViolationController.php';
require_once '../vendor/helpers.php';

isGuest();

$admin = $_SESSION['admin'];

$violation = new Violation();
$data = $violation->violationShow();

$date_submission = date("Y-m-d H:i", strtotime($data['data']['date_submission']));
$date_create = date("Y-m-d H:i", strtotime($data['data']['date_create']));

$first_name = $_SESSION['auth']['first_name'];
$last_name = $_SESSION['auth']['last_name'];
$middle_name = $_SESSION['auth']['middle_name'];

$phone_mask = $_SESSION['auth']['phone_mask'];

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


<style>
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: transparent;
    }

    .carousel-control-prev,
    .carousel-control-next {
        filter: invert(1);
    }
</style>

<?php include './header.php'; ?>


<main class="mt-5 mb-5">
    <div class="container">

        <div class="border border-secondary border-opacity-50 rounded p-4">
            <h2 class="text-center mb-4">Заявление # <?= $data['data']['id'] ?></h2>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="address">Адрес</label>
                    <textarea class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text" rows="1"
                              name="address"
                              id="address" disabled><?= $data['data']['address'] ?></textarea>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="address">Тип нарушения</label>
                    <textarea class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text" rows="1"
                              name="typeViolation"
                              id="address" disabled><?= $data['data']['violation_type'] ?></textarea>
                </div>
            </div>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="numberAuto">Номер авто</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $data['data']['number'] ?>" name="numberAuto"
                           id="numberAuto" disabled>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="brand">Бренд</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $data['data']['brand'] ?>" name="brand"
                           id="brand" disabled>
                </div>
            </div>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="model">Модель</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $data['data']['model'] ?>" name="model"
                           id="model" disabled>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="color">Цвет</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $data['data']['color'] ?>" name="color"
                           id="color" disabled>
                </div>
            </div>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="date_submission">Дата нарушения</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $date_submission ?>" name="date_submission"
                           id="date_submission" disabled>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="dateCreate">Дата создания</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $date_create; ?>" name="dateCreate"
                           id="dateCreate" disabled>
                </div>
            </div>

            <div class="mb-2 row">
                <div class="col-6 mt-2">
                    <label class="form-label" for="fio">ФИО заявителя</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $last_name . ' ' . $first_name . ' ' . $middle_name ?>" name="fio"
                           id="fio" disabled>
                </div>

                <div class="col-6 mt-2">
                    <label class="form-label" for="phone">Телефон</label>
                    <input class="form-control border-0 border-bottom rounded-0 bg-transparent" type="text"
                           value="<?= $phone_mask ?>" name="phone"
                           id="phone" disabled>
                </div>
            </div>


            <?php
            if (count($data['photos']) !== 0) {
                $part = '';

                foreach ($data['photos'] as $index => $photo) {
                    $activeClass = ($index === 0) ? ' active' : '';
                    $part .= '
            <div class="carousel-item' . $activeClass . '">
                <img src="../uploads/' . htmlspecialchars($photo['photo_path']) . '" class="d-block w-100" alt="..." style="object-fit: contain; max-width: 1296px; max-height: 729px; width: auto; margin: auto;">
            </div>';
                }

                echo '<h2 class="text-center pt-3">Изображение</h2>
          <div id="carouselExamplePhotos" class="carousel slide mt-3" data-bs-ride="carousel">
              <div class="carousel-inner">
                  ' . $part . '
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExamplePhotos"
                      data-bs-slide="prev">
                  <span class="carousel-control-prev-icon text-danger" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExamplePhotos"
                      data-bs-slide="next">
                  <span class="carousel-control-next-icon text-danger" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
              </button>
          </div>';
            } else {
                echo '<div class="mt-5 fs-2 text-center">Нет фото</div>';
            }

            if (count($data['videos']) !== 0) {
                $part = '';

                foreach ($data['videos'] as $index => $video) {
                    $activeClass = ($index === 0) ? ' active' : '';
                    $part .= '
            <div class="carousel-item' . $activeClass . '">
                <video controls class="d-block w-100" style="object-fit: contain; max-width: 1296px; max-height: 729px; width: auto; margin: auto;">
                    <source src="../uploads/' . htmlspecialchars($video['video_path']) . '" type="video/mp4">
                    Ваш браузер не поддерживает видео.
                </video>
            </div>';
                }

                echo '<h2 class="text-center pt-3">Видео</h2>
          <div id="carouselExampleVideo" class="carousel slide mt-3" data-bs-ride="carousel">
              <div class="carousel-inner">
                  ' . $part . '
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleVideo" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon text-danger" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleVideo" data-bs-slide="next">
                  <span class="carousel-control-next-icon text-danger" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
              </button>
          </div>';
            } else {
                echo '<div class="mt-5 fs-2 text-center">Нет видео</div>';
            }
            ?>


        <?php

           echo $admin ? '<div class="d-flex gap-4 pt-3">
                <form class="w-100" action="../vendor/ViolationController.php" method="POST">
                    <input type="hidden" name="success" value="' .$data['data']['id'] . '">
                    <input class="btn btn-success w-100" type="submit" name="succeed" value="Одобрить">
                </form>

                <form class="w-100" action="../vendor/ViolationController.php" method="POST">
                    <input type="hidden" name="reject" value="' . $data['data']['id'] . '">
                    <input class="btn btn-danger w-100" type="submit" name="cancel" value="Отклонить">
                </form>
            </div>' : ''; ?>

        </div>
    </div>


</main>


<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</body>
</html>