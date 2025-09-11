<?php
// checkout_charges.php
include '../Config/db_connection.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $order_type = isset($_POST['order_type']) ? $_POST['order_type'] : 'dinein';
    $promo_code = isset($_POST['promo_code']) ? trim($_POST['promo_code']) : '';
    $subtotal = 0;
    $item_details = [];
    if (!empty($cart)) {
        $ids = array_keys($cart);
        $ids_str = implode(',', array_map('intval', $ids));
        $sql = "SELECT * FROM menu WHERE menu_item_id IN ($ids_str)";
        $result = $conn->query($sql);
        while ($item = $result->fetch_assoc()) {
            $item_id = $item['menu_item_id'];
            $qty = $cart[$item_id];
            $item_total = $item['price'] * $qty;
            $subtotal += $item_total;
            $item_details[] = $item['name'] . " x " . $qty . " = BDT " . $item_total;
        }
    }
    // Promo code logic
    $discount = 0;
    if ($promo_code !== '') {
        $promo_sql = "SELECT * FROM promotion WHERE coupon_name = ? AND is_active = 1";
        $promo_stmt = $conn->prepare($promo_sql);
        $promo_stmt->bind_param('s', $promo_code);
        $promo_stmt->execute();
        $promo_result = $promo_stmt->get_result();
        if ($promo_row = $promo_result->fetch_assoc()) {
            $discount = $subtotal * ($promo_row['discount'] / 100);
        }
        $promo_stmt->close();
    }
    // Charges logic
    $service_charge = 0;
    $delivery_charge = 0;
    $subtotal_after_discount = $subtotal - $discount;
    if ($order_type === 'dinein') {
        $service_charge = $subtotal_after_discount * 0.05; // 5% service charge after discount
    } elseif ($order_type === 'takeaway') {
        $service_charge = 0; // No service charge
    } elseif ($order_type === 'delivery') {
        $service_charge = 0;
        $delivery_charge = 50; // Flat delivery charge
    }
    $total_price = $subtotal_after_discount + $service_charge + $delivery_charge;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$user_id) {
        header('Location: ../Commons/Login_page.php');
        exit;
    }
    $items_str = implode(', ', $item_details);
    // Optionally, update promo usage table here
    // $stmt = $conn->prepare("INSERT INTO promo_usage (user_id, promo_code, used_on, subtotal, discount, service_charge, delivery_charge, total_price, order_type, items) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");
    // $stmt->bind_param('isddddddss', $user_id, $promo_code, $subtotal, $discount, $service_charge, $delivery_charge, $total_price, $order_type, $items_str);
    // $stmt->execute();
    // $stmt->close();
    echo round($discount,2) . '|' . round($service_charge,2) . '|' . round($delivery_charge,2) . '|' . round($total_price,2) . '|' . $items_str;
    exit;
}
?>
