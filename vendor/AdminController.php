<?php

session_start();
require __DIR__ . '/helpers.php';


class AdminController
{
    private $connect;

    public function __construct()
    {
        try {
            $this->connect = new PDO('mysql:host=localhost;dbname=grechdem', 'root', '');
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . htmlspecialchars($e->getMessage()));
        }
    }

    public function adminUsersRead()
    {
        $id = $_SESSION['auth']['user_id'];
        $stmt = $this->connect->prepare("SELECT user_info.first_name, user_info.last_name, user_info.middle_name, user_info.phone_mask, users.id, users.email, users.role_id
    FROM users 
    INNER JOIN user_info ON users.id = user_info.user_id
    INNER JOIN role ON users.role_id = role.id
    WHERE user_info.user_id != :id");
        $stmt->execute(['id' => $id]);

        $dataUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Карта ролей
        $roleMap = [
            1 => ['Пользователь', 'btn-warning'],
            2 => ['Админ', 'btn-primary'],
            3 => ['Супер админ', 'btn-danger'],
            4 => ['Заблокирован', 'btn-dark']
        ];

        // Начало сборки строк таблицы
        $part = '';
        foreach ($dataUsers as $dataUser) {
            // Безопасное извлечение статуса
            list($statusText, $statusSelector) = $roleMap[$dataUser['role_id']] ?? ['Неизвестный статус', 'btn-secondary'];

            // Создание строки таблицы
            $part .= '<tr>
            <th scope="row">' . htmlspecialchars($dataUser['id']) . '</th>
            <td>' . htmlspecialchars($dataUser['first_name']) . '</td>
            <td>' . htmlspecialchars($dataUser['last_name']) . '</td>
            <td>' . htmlspecialchars($dataUser['middle_name']) . '</td>
            <td>' . htmlspecialchars($dataUser['phone_mask']) . '</td>
            <td>' . htmlspecialchars($dataUser['email']) . '</td>
            <td class="d-flex gap-1">
                <button class="btn ' . htmlspecialchars($statusSelector) . '">' . htmlspecialchars($statusText) . '</button>
                <a href="../view/edit-users.php?id=' . htmlspecialchars($dataUser['id']) . '" class="btn btn-success">Открыть</a>
            </td>
        </tr>';
        }

        // Вывод таблицы
        echo '
    <div class="mt-5">
        <h1 class="text-center">Управление пользователями</h1>
    </div>
    <table class="table caption-top mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Имя</th>
                <th scope="col">Фамилия</th>
                <th scope="col">Отчество</th>
                <th scope="col">Номер телефона</th>
                <th scope="col">Email</th>
                <th scope="col">Статус</th>
            </tr>
        </thead>
        <tbody>
            ' . $part . '
        </tbody>
    </table>';
    }


    public function adminUserEdit(){
        $id = intval($_GET['id']);

        $stmt = $this->connect->prepare("SELECT user_info.first_name, user_info.last_name, user_info.middle_name, user_info.phone_mask,users.id,users.login, users.email,users.role_id
        FROM users INNER JOIN  user_info ON users.id = user_info.user_id
        INNER JOIN role ON users.role_id = role.id
        WHERE users.id = :id");
        $stmt->execute(['id' => $id]);

        $dataUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dataUsers;
    }

    public function addSuperAdmin()
    {
        $id = $_POST['superAdminCreate'];
        $superAdmin = 3;
        $stmt = $this->connect->prepare('UPDATE users SET role_id = :role WHERE id = :id');
        $stmt->execute(['role' => $superAdmin, 'id' => $id]);
        redirect('../view/users-control.php');
    }

    public function addAdmin()
    {
        $id = $_POST['adminCreate'];
        $admin = 2;
        $stmt = $this->connect->prepare('UPDATE users SET role_id = :role WHERE id = :id');
        $stmt->execute(['role' => $admin, 'id' => $id]);

        redirect('../view/users-control.php');
    }

    public function addUser() {
        $id = $_POST['usersCreate'];
        $admin = 1;
        $stmt = $this->connect->prepare('UPDATE users SET role_id = :role WHERE id = :id');
        $stmt->execute(['role' => $admin, 'id' => $id]);

        redirect('../view/users-control.php');
    }

    public function blockUser() {
        $id = $_POST['block'];
        $blocked = 4;
        $stmt = $this->connect->prepare('UPDATE users SET role_id = :role WHERE id = :id');
        $stmt->execute(['role' => $blocked, 'id' => $id]);

        redirect('../view/users-control.php');
    }

}

$admin = new AdminController();

if (isset($_POST['superAdminCreateBtn'])) {
    $admin->addSuperAdmin();
} else if (isset($_POST['adminCreateBtn'])) {
    $admin->addAdmin();
} else if(isset($_POST['userCreateBtn'])) {
    $admin->addUser();
} else if(isset($_POST['blockBtn'])) {
    $admin->blockUser();
}