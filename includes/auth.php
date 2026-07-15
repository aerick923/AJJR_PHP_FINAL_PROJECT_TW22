<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {

    if (!isset($_SESSION['user_id'])) {

        header("Location: login.php");
        exit();

    }

}

function require_admin() {

    if (!isset($_SESSION['user_id'])) {

        header("Location: ../login.php");
        exit();

    }


    if ($_SESSION['role'] !== 'admin') {

        header("Location: ../index.php");
        exit();

    }

}

function require_buyer() {

    if (!isset($_SESSION['user_id'])) {

        header("Location: login.php");
        exit();

    }


    if ($_SESSION['role'] !== 'buyer') {

        header("Location: index.php");
        exit();

    }

}

function current_user() {

    return $_SESSION['user_id'] ?? null;

}

function logout_user() {

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();

}

?>