<?php
session_start();
require_once __DIR__ . '/helpers.php';

class Violation
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

    public function violationRead()
    {
        $user_id = $_SESSION['auth']['user_id'];

        $stmt = $this->connect->prepare("SELECT statement.id, statement.date_create, statement.address, statement.violation_type, car.number, status.name
        FROM statement INNER JOIN car ON statement.car_id = car.id
        INNER JOIN status ON statement.status_id = status.id
        WHERE statement.user_id = :user_id
        ORDER BY status.id ASC;");
        $stmt->execute(['user_id' => $user_id]);

        $dataViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dataViolations) !== 0) {
            $part = '';

            $statusMap = [
                'Новое' => ['btn-warning', 'В обработке'],
                'Принятое' => ['btn-primary', 'Принято в работу'],
                'Отклоненное' => ['btn-danger', 'Отклоненное'],
            ];

            foreach ($dataViolations as $dataViolation) {
                $date = new DateTime($dataViolation['date_create']);
                $newFormat = $date->format('Y-m-d');

                list($statusSelector, $statusText) = $statusMap[$dataViolation['name']] ?? ['btn-secondary', 'Неизвестный статус'];

                $part .= '<tr>
                <th scope="row">' . htmlspecialchars($dataViolation['id']) . '</th>
                <td>' . htmlspecialchars($newFormat) . '</td>
                <td>' . htmlspecialchars($dataViolation['number']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['address']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['violation_type']) . '</td>
                <td class="d-flex gap-1">
               <button class="btn ' . htmlspecialchars($statusSelector) . '">' . htmlspecialchars($statusText) . '</button>
                <a href="../view/edit-violation.php?id=' . $dataViolation['id'] . '" class="btn btn-success">Открыть</a>
                </td>
            </tr>';
            }

            echo '
        <div class="mt-5">
            <h1 class="text-center">Ваши заявления</h1>
        </div>
        <table class="table caption-top mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата подачи</th>
                    <th scope="col">Номер машины</th>
                    <th scope="col" class="w-25">Адрес</th>
                    <th scope="col" class="w-25">Тип нарушения</th>
                    <th scope="col">Статус</th>
                </tr>
            </thead>
            <tbody>
                ' . $part . '
            </tbody>
        </table>';
        } else {
            echo '<div class="d-flex justify-content-center align-items-center" style="min-height:  78vh;"><h1 class="text-center text-secondary">У вас нет записей</h1></div>';
        }
    }

    public function violation_create()
    {
        $violation = filter_input(INPUT_POST, 'violation', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dateViolation = filter_input(INPUT_POST, 'dateViolation', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $carNumber = filter_input(INPUT_POST, 'carNumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $carBrand = filter_input(INPUT_POST, 'carBrand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $carModel = filter_input(INPUT_POST, 'carModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $carColor = filter_input(INPUT_POST, 'carColor', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $photoStatement = $_FILES['photoStatement'];
        $videoStatement = $_FILES['videoStatement'];

        emptyField($violation, 'violation', 'Нарушение');
        emptyField($address, 'address', 'адрес');
        emptyField($dateViolation, 'dateViolation', 'дата нарушения');
        validationFieldEn($carNumber, 'carNumber', 'Номер авто');
        emptyField($carBrand, 'carBrand', 'бренд');
        emptyField($carModel, 'carModel', 'модель');
        emptyField($carColor, 'carColor', 'цвет');

        $targetDir = '../uploads/';

        if (!empty($photoStatement['name'][0])) {
            $imagePaths = [];
            $allowedExtensions = ['gif', 'png', 'jpg'];

            foreach ($photoStatement['name'] as $key => $name) {
                $tmpName = $photoStatement['tmp_name'][$key];

                $imageFileType = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (!in_array($imageFileType, $allowedExtensions)) {
                    $_SESSION['validation']['photoStatement'] = 'Можно загружать только фото';
                    continue;
                }
                $originalFileName = pathinfo($name, PATHINFO_FILENAME);
                $newFileName = "{$originalFileName}_" . uniqid() . ".{$imageFileType}";

                if (move_uploaded_file($tmpName, $targetDir . $newFileName)) {
                    $imagePaths[] = $newFileName;
                }
            }
        }

        // Сохранения изображения

        // Сохранения video
        if (!empty($videoStatement)) {
            $videoPaths = [];

            foreach ($videoStatement['name'] as $key => $name) {
                $tmpName = $videoStatement['tmp_name'][$key];

                if (empty($tmpName) || !is_uploaded_file($tmpName)) {
                    continue;
                }
                $imageFileType = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $mimeType = mime_content_type($tmpName);
                if (strpos($mimeType, 'video/') !== 0) {
                    $_SESSION['validation']['videoStatement'] = 'Можно загружать только видео';
                    continue;
                }

                $originalFileName = pathinfo($name, PATHINFO_FILENAME);
                $newFileName = "{$originalFileName}_" . uniqid() . ".{$imageFileType}";

                if (move_uploaded_file($tmpName, $targetDir . $newFileName)) {
                    $videoPaths[] = $newFileName;
                }
            }


            redirectError('../view/violation-create.php');
            $user_id = $_SESSION['auth']['user_id'];
            $stmt = $this->connect->prepare("INSERT INTO `car` (`number`,`brand`,`model`,`color`) VALUES (:number,:brand,:model,:color)");
            $stmt->execute(['number' => $carNumber, 'brand' => $carBrand, 'model' => $carModel, 'color' => $carColor]);

            $car_id = $this->connect->lastInsertId();
            $newViolationStatus = 1;

            $date = new DateTime($dateViolation);
            $formattedDate = $date->format('Y-m-d H:i:s');

            $currentTime = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $stmt = $this->connect->prepare("INSERT INTO `statement` (`address`,`violation_type`,`car_id`,`user_id`,`status_id`,`date_submission`,`date_create`) VALUES (:address,:violation_type,:car_id,:user_id,:status_id,:date_submission,:date_create)");
            $stmt->execute(['address' => $address, 'violation_type' => $violation, 'car_id' => $car_id, 'user_id' => $user_id, 'status_id' => $newViolationStatus, 'date_submission' => $formattedDate, 'date_create' => $currentTime]);
            $statement_Id = $this->connect->lastInsertId();

            foreach ($imagePaths as $path) {
                $stmt = $this->connect->prepare("INSERT INTO `photos` (`statement_id`,`photo_path`) VALUES (:statement_id,:photo_path)");
                $stmt->execute(['statement_id' => $statement_Id, 'photo_path' => $path]);
            }

            foreach ($videoPaths as $path) {
                $stmt = $this->connect->prepare("INSERT INTO `video` (`statement_id`,`video_path`) VALUES (:statement_id,:video_path)");
                $stmt->execute(['statement_id' => $statement_Id, 'video_path' => $path]);
            }

            redirect('../view/home.php');
        }

    }

    public function violationShow()
    {
        $id = intval($_GET['id']);
        $result = [];

        $stmt = $this->connect->prepare("SELECT statement.id,address,violation_type,date_submission,date_create,number,brand,model,color
        FROM statement INNER JOIN  car ON statement.car_id = car.id
        WHERE statement.id = :id;");
        $stmt->execute(['id' => $id]);
        $result['data'] = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->connect->prepare("SELECT photo_path
        FROM photos WHERE statement_id = :id;");
        $stmt->execute(['id' => $id]);
        $result['photos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->connect->prepare("SELECT video_path
        FROM video WHERE statement_id = :id;");
        $stmt->execute(['id' => $id]);
        $result['videos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function newViolation(){
        $stmt = $this->connect->prepare("SELECT statement.id, statement.date_create, statement.address, statement.violation_type, car.number, status.name
        FROM statement
        INNER JOIN car ON statement.car_id = car.id
        INNER JOIN status ON statement.status_id = status.id
        WHERE status.id = :id
        ORDER BY status.id ASC;");
        $stmt->execute(['id' => 1]);
        $dataViolationsNew = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (count($dataViolationsNew) !== 0) {
            $part = '';
            foreach ($dataViolationsNew as $dataViolation) {
                $date = new DateTime($dataViolation['date_create']);
                $newFormat = $date->format('Y-m-d');

                $part .= '<tr>
                <th scope="row">' . htmlspecialchars($dataViolation['id']) . '</th>
                <td>' . htmlspecialchars($newFormat) . '</td>
                <td>' . htmlspecialchars($dataViolation['number']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['address']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['violation_type']) . '</td>
                <td class="d-flex gap-1">
                <button class="btn btn-primary">Новое</button>
                <a href="../view/edit-violation.php?id=' . $dataViolation['id'] . '" class="btn btn-success">Открыть</a>
                </td>
            </tr>';
            }

            echo '
        <div class="mt-5">
            <h1 class="text-center">Новые заявления</h1>
        </div>
        <table class="table caption-top mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата подачи</th>
                    <th scope="col">Номер машины</th>
                    <th scope="col" class="w-25">Адрес</th>
                    <th scope="col" class="w-25">Тип нарушения</th>
                    <th scope="col">Статус</th>
                </tr>
            </thead>
            <tbody>
                ' . $part . '
            </tbody>
        </table>';
        } else {
            echo '<div class="d-flex justify-content-center align-items-center" style="min-height:  78vh;"><h1 class="text-center text-secondary">Нет новых заявлений</h1></div>';
        }
    }

    public function violationReject(){
        $id = $_POST['reject'];

        $stmt = $this->connect->prepare("UPDATE statement SET status_id = :reject WHERE id = :id");
        $stmt->execute(['reject' => 3, 'id' => $id]);

        redirect('../view/new-violation.php');
    }

    public function violationSuccess(){
        $id = $_POST['success'];

        $stmt = $this->connect->prepare("UPDATE statement SET status_id = :reject WHERE id = :id");
        $stmt->execute(['reject' => 2, 'id' => $id]);

        redirect('../view/new-violation.php');
    }

    public function violationAllRead()
    {
        $stmt = $this->connect->prepare("SELECT statement.id, statement.date_create, statement.address, statement.violation_type, car.number, status.name
        FROM statement INNER JOIN car ON statement.car_id = car.id
        INNER JOIN status ON statement.status_id = status.id
        ORDER BY status.id ASC;");
        $stmt->execute();

        $dataViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dataViolations) === 0) {
            echo '<div class="d-flex justify-content-center align-items-center" style="min-height:  78vh;"><h1 class="text-center text-secondary">У вас нет записей</h1></div>';
            return;
        }

        $part = '';

        $statusMap = [
            'Новое' => ['btn-warning', 'В обработке'],
            'Принятое' => ['btn-primary', 'Принято в работу'],
            'Отклоненное' => ['btn-danger', 'Отклоненное'],
        ];

        foreach ($dataViolations as $dataViolation) {
            $date = new DateTime($dataViolation['date_create']);
            $newFormat = $date->format('Y-m-d');

            list($statusSelector, $statusText) = $statusMap[$dataViolation['name']] ?? ['btn-secondary', 'Неизвестный статус'];

            $part .= '<tr>
                <th scope="row">' . htmlspecialchars($dataViolation['id']) . '</th>
                <td>' . htmlspecialchars($newFormat) . '</td>
                <td>' . htmlspecialchars($dataViolation['number']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['address']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['violation_type']) . '</td>
                <td class="d-flex gap-1">
                <button class="btn ' . htmlspecialchars($statusSelector) . '">' . htmlspecialchars($statusText) . '</button>
                <a href="../view/edit-violation.php?id=' . $dataViolation['id'] . '" class="btn btn-success">Открыть</a>
                </td>
            </tr>';
        }

        echo '
        <div class="mt-5">
            <h1 class="text-center">Ваши заявления</h1>
        </div>
        <table class="table caption-top mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата подачи</th>
                    <th scope="col">Номер машины</th>
                    <th scope="col" class="w-25">Адрес</th>
                    <th scope="col" class="w-25">Тип нарушения</th>
                    <th scope="col">Статус</th>
                </tr>
            </thead>
            <tbody>
                ' . $part . '
            </tbody>
        </table>';
    }

    public function violationRejectRead()
    {
        $stmt = $this->connect->prepare("SELECT statement.id, statement.date_create, statement.address, statement.violation_type, car.number, status.name
        FROM statement INNER JOIN car ON statement.car_id = car.id
        INNER JOIN status ON statement.status_id = status.id
        WHERE status.id = :id
        ORDER BY status.id ASC;");
        $stmt->execute(['id' => 3]);
        $dataViolationsNew = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($dataViolationsNew) === 0) {
            echo '<div class="d-flex justify-content-center align-items-center" style="min-height:  78vh;"><h1 class="text-center text-secondary">Нет новых заявлений</h1></div>';
            return;
        }

        $part = '';
        foreach ($dataViolationsNew as $dataViolation) {
            $date = new DateTime($dataViolation['date_create']);
            $newFormat = $date->format('Y-m-d');

            $part .= '<tr>
                <th scope="row">' . htmlspecialchars($dataViolation['id']) . '</th>
                <td>' . htmlspecialchars($newFormat) . '</td>
                <td>' . htmlspecialchars($dataViolation['number']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['address']) . '</td>
                <td class="w-25">' . htmlspecialchars($dataViolation['violation_type']) . '</td>
                <td class="d-flex gap-1">
                <button class="btn btn-danger">Отклоненное</button>
                <a href="../view/edit-violation.php?id=' . $dataViolation['id'] . '" class="btn btn-success">Открыть</a>
                </td>
            </tr>';
        }

        echo '
        <div class="mt-5">
            <h1 class="text-center">Новые заявления</h1>
        </div>
        <table class="table caption-top mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата подачи</th>
                    <th scope="col">Номер машины</th>
                    <th scope="col" class="w-25">Адрес</th>
                    <th scope="col" class="w-25">Тип нарушения</th>
                    <th scope="col">Статус</th>
                </tr>
            </thead>
            <tbody>
                ' . $part . '
            </tbody>
        </table>';

    }
}

$violation = new Violation();
if (isset($_POST['violationCreate'])) {
    $violation->violation_create();
} else if (isset($_POST['cancel'])) {
    $violation->violationReject();
} else if (isset($_POST['succeed'])) {
    $violation->violationSuccess();
}