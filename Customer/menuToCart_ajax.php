<?php
session_start();
include("../Config/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? '';
    $qty = $_POST['qty'] ?? 1;

    if ($action === 'add' && !empty($id) && !empty($qty)) {
    // Make sure cart exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If item exists, increase qty, otherwise set it
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }

    echo 'added';

    // Debug / response
    foreach ($_SESSION['cart'] as $itemId => $itemQty) {
        echo "ID: $itemId, Quantity: $itemQty<br>";
    }
} else {
    echo 'error';
}

}
?>