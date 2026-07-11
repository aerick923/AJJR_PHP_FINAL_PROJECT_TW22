<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if user is logged in
function require_login() {

    if (!isset($_SESSION['user_id'])) {

        header("Location: login.php");
        exit();

    }

}


// Check if user is admin/seller
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


// Check if user is buyer
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


// Get current logged in user
function current_user() {

    return $_SESSION['user_id'] ?? null;

}


// Logout function
function logout_user() {

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();

}

?>
