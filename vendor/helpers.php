<?php

// redirect-----------------------------------
function redirect($path)
{
    header('Location: ' . $path);
    exit();
}
// redirect-----------------------------------


// validation-----------------------------------
function validationField($fieldValue, $key, $field)
{
    if (empty(trim($fieldValue))) {
        $_SESSION['validation'][$key] = ucfirst($field) . ' не может быть пустым';
    } elseif (!preg_match("/^[а-яА-Я]+$/u", $fieldValue)) {
        $_SESSION['validation'][$key] = ucfirst($field) . ' поле должно содержать только русские буквы';
    }
}


function validationFieldEn($field,$key, $fieldValue)
{
    if (empty(trim($fieldValue))) {
        $_SESSION['validation'][$key] = ucfirst($field) . ' не может быть пустым';
    } elseif (!preg_match("/^[0-9a-zA-Z]+$/u", $fieldValue)) {
        $_SESSION['validation'][$key] = ucfirst($field) . ' поле должно содержать только латинские буквы и цифры';
    }
}

function emptyField($field, $key, $fieldValue) {
    if (empty(trim($field))) {
        $_SESSION['validation'][$key] = 'Поле '. $fieldValue .' должно быть заполнено';
    }
}

function redirectError($path)
{
    if (!empty($_SESSION['validation'])) {
        redirect($path);
    }
}
// validation-----------------------------------


//validationWrite--------------------------------
function mayBeHasError($fieldName)
{
    if (isset($_SESSION['validation'][$fieldName])) {
        return 'aria-invalid="true"';
    }
    return '';
}

function getErrorMessage(string $fieldName)
{
    $error = $_SESSION['validation'][$fieldName] ?? '';
    return $error ? "<div class='text-danger error'>$error</div>" : null;
}
//validationWrite--------------------------------

//photo----------------------------------------------------
function viewPhoto()
{
    $photo = $_SESSION['auth']['photo_path'];

    if (!$photo) {
        return './img/user.svg';
    }

    return '../uploads/' . $photo;
}
//photo----------------------------------------------------


//isAuth-----------------------------------------------------
function isGuest()
{
    $auth = isset($_SESSION['auth']) ? $_SESSION['auth'] : null;

    if ($auth === null) {
        redirect('./login.php');
        exit();
    }
}

function isAuth()
{
    $auth = isset($_SESSION['auth']) ? $_SESSION['auth'] : null;

    if ($auth !== null) {
        redirect('./home.php');
        exit();
    }
}
//isAuth-----------------------------------------------------
