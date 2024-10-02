<?php
require_once '../vendor/ViolationController.php';
require_once '../vendor/helpers.php';

isGuest();

$violation = new Violation();

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


<main class="">
    <div class="container">

        <?php $violation->violationAllRead(); ?>

    </div>
</main>


<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
