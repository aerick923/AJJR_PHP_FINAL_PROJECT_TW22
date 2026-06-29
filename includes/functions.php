<?php
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function format_price($price) {
    return "₱" . number_format($price, 2);
}

function cart_count() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }

    $count = 0;

    foreach ($_SESSION['cart'] as $item) {
        $count += $item;
    }

    return $count;
}
?>