<?php
// place_order.php
include '../Config/db_connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $order_type = isset($_POST['order_type']) ? $_POST['order_type'] : 'dinein';
    $promo_code = isset($_POST['promo_code']) ? trim($_POST['promo_code']) : '';
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $service_charge = isset($_POST['service_charge']) ? floatval($_POST['service_charge']) : 0;
    $delivery_charge = isset($_POST['delivery_charge']) ? floatval($_POST['delivery_charge']) : 0;
    $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if ($_SESSION['user_id'] != $user_id) {
        echo 'error';
        exit;
    } else {
        echo 'order_type: ' . $order_type . "\n";
        echo 'promo_code: ' . $promo_code . "\n";
        echo 'subtotal: ' . $subtotal . "\n";
        echo 'discount: ' . $discount . "\n";
        echo 'service_charge: ' . $service_charge . "\n";
        echo 'delivery_charge: ' . $delivery_charge . "\n";
        echo 'total_price: ' . $total_price . "\n";
        echo 'user_id: ' . $user_id . "\n";
        // $order_sql = "INSERT INTO orders (order_type, subTotal, discount, service_charge, delivery_charge, total, paid, user_id, date) VALUES ('$order_type', $subtotal, $discount, $service_charge, $delivery_charge, $total_price, 0, $user_id, NOW())";
        // $conn->query($order_sql);
        // Clear the cart after placing the order
        unset($_SESSION['cart']);
        echo 'success';
        
        exit;
    }
}
?>