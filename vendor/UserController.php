<?php
session_start();
require_once './helpers.php';
$_SESSION['validation'] = [];

class UserAuth
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

    public function register_info() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $phone_mask = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $file = $_FILES['file'];

            validationField($first_name, 'first_name', 'Имя');
            validationField($last_name, 'last_name', 'Фамилия');
            validationField($middle_name, 'middle_name', 'Отчество');

            if (empty(trim($phone_mask))) {
                $_SESSION['validation']['phone'] = 'Поле телефон не может быть пустым';
            } else {
                $phone_clean = preg_replace('/[^\d]/', '', $phone_mask);
                $stmt = $this->connect->prepare("SELECT * FROM `user_info` WHERE `phone_clean` = :phone_clean");
                $stmt->execute(['phone_clean' => $phone_clean]);
                if ($stmt->fetchColumn()) {
                    $_SESSION['validation']['phone'] = 'Номер телефона занят';
                }
            }

            if ($file['error'] === UPLOAD_ERR_OK) {
                $targetDir = '../uploads/';
                $allowedExtensions = ['gif', 'png', 'jpg'];
                $originalFileName = pathinfo($file['name'], PATHINFO_FILENAME);
                $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($imageFileType, $allowedExtensions)) {
                    $_SESSION['validation']['file'] = 'Можно загружать только фото';
                } else {
                    $newFileName = "{$originalFileName}_" . uniqid() . ".{$imageFileType}";
                    $targetFile = $targetDir . $newFileName;

                    $_SESSION['user_info'] = [
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'middle_name' => $middle_name,
                        'phone_mask' => $phone_mask,
                        'phone_clean' => $phone_clean,
                        'file' => $newFileName,
                    ];

                    move_uploaded_file($file['tmp_name'], $targetFile);
                    redirect('../view/registration.php');
                }
            } else {
                $_SESSION['validation']['file'] = 'Ошибка загрузки файла.';
            }

            redirectError('../view/registration-info.php');
        }
    }

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            validationFieldEn($login, 'login', 'login');

            $stmt = $this->connect->prepare("SELECT * FROM users WHERE login = :login");
            $stmt->execute(['login' => $login]);
            if ($stmt->fetchColumn()) {
                $_SESSION['validation']['login'] = 'Логин занят';
            }


            if (empty(trim($email))) {
                $_SESSION['validation']['email'] = 'Введите почту';
            } else {
                $stmt = $this->connect->prepare("SELECT email FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                if ($stmt->fetchColumn()) {
                    $_SESSION['validation']['email'] = 'Почта уже зарегистрирована';
                }
            }

            if (empty(trim($password))) {
                $_SESSION['validation']['password'] = 'Введите пароль';
            } elseif (strlen($password) < 6 || strlen($password) > 18) {
                $_SESSION['validation']['password'] = 'Пароль должен быть не менее 6 и не более 18 символов';
            }

            if (empty(trim($passwordConfirm))) {
                $_SESSION['validation']['passwordConfirm'] = 'Подтвердите пароль';
            } elseif ($password !== $passwordConfirm) {
                $_SESSION['validation']['passwordConfirm'] = 'Пароли не совпадают';
            }

            redirectError('../view/registration.php');

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role_id = 1;

            $first_name = $_SESSION['user_info']['first_name'];
            $last_name = $_SESSION['user_info']['last_name'];
            $middle_name = $_SESSION['user_info']['middle_name'];
            $phone_mask = $_SESSION['user_info']['phone_mask'];
            $phone_clean = $_SESSION['user_info']['phone_clean'];
            $file = $_SESSION['user_info']['file'];

            $stmt = $this->connect->prepare("INSERT INTO users  (login,email,password,role_id) VALUES (:login, :email, :hashedPassword, :role_id)");
            $stmt->execute(['login' => $login, 'email' => $email, 'hashedPassword' => $hashedPassword, 'role_id' => $role_id]);

            $info_id = $this->connect->lastInsertId();

            $stmt = $this->connect->prepare("INSERT INTO user_info (first_name,last_name,middle_name,phone_clean,phone_mask,user_id,photo_path)
            VALUES (:first_name, :last_name, :middle_name, :phone_clean, :phone_mask, :user_id, :photo_path)");

            unset($_SESSION['register_info']);

            $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name, 'middle_name' => $middle_name, 'phone_clean' => $phone_clean, 'phone_mask' => $phone_mask, 'user_id' => $info_id, 'photo_path' => $file]);
            redirect('../view/login.php');
        }
    }

    public function login()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $verify = filter_input(INPUT_POST, 'verifyData', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty($verify)) {
                $_SESSION['validation']['verify'] = 'Поле должно быть заполнено';
            } else {
                $stmt = $this->connect->prepare("SELECT `id`, `password` FROM `users` WHERE `login` = :verify OR `email` = :verify");
                $stmt->execute(['verify' => $verify]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    $_SESSION['validation']['verify'] = 'Почта не зарегистрирована';
                }
            }

            if (empty(trim($password))) {
                $_SESSION['validation']['password'] = 'Введите пароль';
            } elseif (strlen($password) < 6 || strlen($password) > 18) {
                $_SESSION['validation']['password'] = 'Пароль должен быть не меньше 6 символов и не больше 18 символов';
            }

            if (empty($_SESSION['validation'])) {
                if (password_verify($password, $user['password'])) {
                    $stmt = $this->connect->prepare("SELECT * FROM `users` INNER JOIN `user_info` ON users.id = user_info.user_id WHERE users.id = :id ");
                    $stmt->execute(['id' => $user['id']]);
                    $auth = $stmt->fetch(PDO::FETCH_ASSOC);

                    $blocked = 4;

                    if ($auth['role_id'] === $blocked) {
                        $_SESSION['validation']['verify'] = 'Пользователь заблокирован';
                        redirect('../view/login.php');
                    }

                    $_SESSION['auth'] = $auth;
                    if ($auth !== 1) {
                        $_SESSION['admin'] = $auth['role_id'];
                    }

                    redirect('../view/home.php');
                } else {
                    $_SESSION['validation']['password'] = 'Неверный пароль';
                }
            }

            redirectError('../view/login.php');
        }
    }

    public function resetPasswordInfo()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $emailReset = filter_input(INPUT_POST, 'verifyEmailReset', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $loginReset = filter_input(INPUT_POST, 'verifyLoginReset', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $phoneReset = filter_input(INPUT_POST, 'verifyPhoneReset', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty(trim($emailReset))) {
                $_SESSION['validation']['email'] = 'Введите почту';
            } elseif (!filter_var($emailReset, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['validation']['emailReset'] = 'Неверный формат почты';
            }

            if (empty(trim($phoneReset))) {
                $_SESSION['validation']['phone'] = 'Поле телефон не может быть пустым';
            }

            validationFieldEn($loginReset, 'verifyLoginReset', 'login');

            redirectError('../view/reset-password.php');

            $stmt = $this->connect->prepare("SELECT users.id FROM users INNER JOIN user_info ON users.id = user_info.user_id 
            WHERE users.login = :login AND users.email = :email AND user_info.phone_mask = :phone;");
            $stmt->execute(['login' => $loginReset, 'email' => $emailReset, 'phone' => $phoneReset]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $_SESSION['validation']['verifyEmailReset'] = 'Пользователь не найден';
                redirect('../view/reset-password.php');
            }

            $_SESSION['id'] = $data['id'];
            redirect('../view/new-password.php');
        }
    }

    public function newPasswordUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $passwordNew = filter_input(INPUT_POST, 'newPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $passwordConfirmNew = filter_input(INPUT_POST, 'newPasswordConfirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty(trim($passwordNew))) {
                $_SESSION['validation']['newPassword'] = 'Введите пароль';
            } elseif (strlen($passwordNew) < 6 || strlen($passwordNew) > 18) {
                $_SESSION['validation']['newPassword'] = 'Пароль должен быть не менее 6 и не более 18 символов';
            }

            if (empty(trim($passwordConfirmNew))) {
                $_SESSION['validation']['newPasswordConfirm'] = 'Подтвердите пароль';
            } elseif ($passwordConfirmNew !== $passwordNew) {
                $_SESSION['validation']['newPasswordConfirm'] = 'Пароли не совпадают';
            }

            redirectError('../view/new-password.php');

            $id = $_SESSION['id'];
            $passwordHash = password_hash($passwordNew, PASSWORD_DEFAULT);

            $stmt = $this->connect->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $passwordHash, 'id' => $id]);

            unset($_SESSION['id']);
            redirect('../view/login.php');
        }
    }

    public function settingUpdate()
    {
        $login = filter_input(INPUT_POST, 'loginSetting', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'loginSetting', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $first_name = filter_input(INPUT_POST, 'first_nameSetting', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = filter_input(INPUT_POST, 'first_nameSetting', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $middle_name = filter_input(INPUT_POST, 'middle_nameSetting', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, 'phoneSetting', FILTER_SANITIZE_STRING);
        $photoProfile = $_FILES['photoProfileSetting'];

        $id = $_SESSION['auth']['user_id'];

        validationField($first_name, 'first_nameSetting', 'Имя');
        validationField($last_name, 'first_nameSetting', 'Фамилия');
        validationField($middle_name, 'middle_nameSetting', 'Отчество');

        if (empty(trim($email))) {
            $_SESSION['validation']['email'] = 'Введите почту';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['validation']['email'] = 'Некорректный адрес электронной почты';
        } else {
            $stmt = $this->connect->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
            $stmt->execute(['email' => $email, 'id' => $id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $_SESSION['validation']['email'] = 'Почта уже зарегистрирована';
            }
        }

        validationFieldEn($login,'loginSetting', "Login");
        $stmt = $this->connect->prepare("SELECT id FROM users WHERE login = :login AND id != :id");
        $stmt->execute(['login' => $login, 'id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            $_SESSION['validation']['login'] = 'Логин занят';
        }

        if (empty(trim($phone))) {
            $_SESSION['validation']['phone_mask'] = 'Поле телефон не может быть пустым';
        } else {
            $stmt = $this->connect->prepare("SELECT id FROM user_info WHERE phone_mask = :phone_mask AND id = :id");
            $stmt->execute(['phone_mask' => $phone, 'id' => $id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $_SESSION['validation']['phone_mask'] = 'Телефон занят';
            }
        }

        $phone_clean = preg_replace('/[^\d]/', '', $phone);

        if (isset($photoProfile) && $photoProfile['error'] === UPLOAD_ERR_OK) {
            $targetDir = '../uploads/';
            $allowedExtensions = ['gif', 'png', 'jpg'];
            $originalFileName = pathinfo($photoProfile['name'], PATHINFO_FILENAME);
            $imageFileType = strtolower(pathinfo($photoProfile['name'], PATHINFO_EXTENSION));

            if (!in_array($imageFileType, $allowedExtensions)) {
                $_SESSION['validation']['file'] = 'Можно загружать только фото';
            }

            $newFileName = "{$originalFileName}_" . uniqid() . ".{$imageFileType}";
            $targetFile = $targetDir . $newFileName;

            if (!move_uploaded_file($photoProfile['tmp_name'], $targetFile)) {
                $_SESSION['validation']['file'] = 'Ошибка загрузки файла';
            }

            $stmt = $this->connect->prepare("UPDATE user_info SET photo_path = :photo_path WHERE user_id = :id");
            $stmt->execute(['photo_path' => $newFileName, 'id' => $id]);
        }

        redirectError('../view/settings.php');

        $stmt = $this->connect->prepare("UPDATE users SET login = :login WHERE id = :id");
        $stmt->execute(['login' => $login, 'id' => $id]);

        $stmt = $this->connect->prepare("UPDATE `user_info` SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, phone_clean = :phone_clean, phone_mask = :phone_mask WHERE user_id = :id");
        $stmt->execute(['first_name' => $first_name, 'middle_name' => $middle_name, 'last_name' => $last_name, 'phone_clean' => $phone_clean, 'phone_mask' => $phone, 'id' => $id
        ]);

        $stmt = $this->connect->prepare("SELECT * FROM users INNER JOIN user_info ON users.id = user_info.user_id WHERE users.id = :id ");
        $stmt->execute(['id' => $id]);
        $auth = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['auth'] = $auth;

        redirect('../view/settings.php');
    }

    public function logout()
    {
        session_start();
        if (session_id() !== '') {
            $_SESSION = [];
            session_destroy();
        }
        header('Location: ../view/login.php', true, 302);
        exit();
    }
}


$userAuth = new UserAuth();
if (isset($_POST['logout'])) {
    $userAuth->logout();
} else if (isset($_POST['auth'])) {
    $userAuth->login();
} else if (isset($_POST['registerInfo'])) {
    $userAuth->register_info();
} else if (isset($_POST['registrationCreate'])) {
    $userAuth->register();
} else if (isset($_POST['resetPasswordInfo'])) {
    $userAuth->resetPasswordInfo();
} else if (isset($_POST['newPasswordUserBtn'])) {
    $userAuth->newPasswordUser();
} else if (isset($_POST['settingUpdate'])) {
    $userAuth->settingUpdate();
}
