<?php
require_once '../vendor/helpers.php';

$auth = isset($_SESSION['auth']) ? $_SESSION['auth'] : null;

$admin = null;
$superAdmin = null;

if (isset($_SESSION['admin'])) {
    $_SESSION['admin'] > 1 ? $admin = $_SESSION['admin'] : null;
    $_SESSION['admin'] === 3 ? $superAdmin = $_SESSION['admin'] : null;
}
?>


<style>
    .navbar-nav {
        display: flex;
        justify-content: center;
        width: 100%;
    }

</style>

<header class="bg-light">
    <div class="container">
        <nav class="navbar navbar-expand-xl navbar-light">
            <a class="navbar-brand d-none d-xl-block" href="./home.php">
                <span class="fw-bold text-primary">Нарушениям</span>
                <span class="fw-bold text-danger">Нет</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-xl-flex justify-content-center" id="navbarNav">
                <div id="navbarText" class="<?php echo $auth ? '' : 'd-none'; ?>">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                        echo $admin ? '
                        <li class="nav-item">
                            <a class="nav-link text-center active" href="./processed-violation.php">Общие</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center active" href="./new-violation.php">Новые заявления</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center active" href="./reject-violation.php">Отклоненные</a>
                        </li>
                        ' : '';

                        echo $superAdmin ? '
                        <li class="nav-item">
                            <a class="nav-link text-center active" href="./users-control.php">Управления пользователями</a>
                        </li>' : '';
                        ?>
                    </ul>
                </div>


            </div>

            <div class="dropdown position-absolute end-0 <?php echo $auth ? '' : 'd-none'; ?>">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= viewPhoto() ?>" alt="mdo" width="42" height="42" class="rounded-circle border border-grey">
                </a>
                <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="./settings.php">Профиль</a></li>
                    <li>
                        <form action="../vendor/UserController.php" method="POST">
                            <button class="dropdown-item btn-outline-danger" name="logout">Выйти</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
