<?php
// place_order.php
include '../Config/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Get form data
    $order_type = $_POST['orderType'];
    $payment_method = $_POST['paymentMethod'];
    $promo_code = isset($_POST['promo_code']) ? $_POST['promo_code'] : '';
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $service_charge = isset($_POST['serviceCharge']) ? floatval($_POST['serviceCharge']) : 0;
    $delivery_charge = isset($_POST['deliveryCharge']) ? floatval($_POST['deliveryCharge']) : 0;
    $total_price = isset($_POST['totalPrice']) ? floatval($_POST['totalPrice']) : 0;
    $user_id = $_SESSION['user_id'];
    
    // Determine payment status
    $paid = ($payment_method === 'cash') ? 0 : 1;
    
    // Insert order
    $order_sql = "INSERT INTO orders (order_type, subTotal, discount, service_charge, delivery_charge, total, paid, user_id, date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("sdddddis", $order_type, $subtotal, $discount, $service_charge, $delivery_charge, $total_price, $paid, $user_id);
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        
        // Insert order items
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        
        if (!empty($cart)) {
            $ids = array_keys($cart);
            $ids_str = implode(',', array_map('intval', $ids));
            $sql = "SELECT * FROM menu WHERE menu_item_id IN ($ids_str)";
            $result = $conn->query($sql);
            
            while ($item = $result->fetch_assoc()) {
                $item_id = $item['menu_item_id'];
                $qty = $cart[$item_id];
                $price = $item['price'];
                
                $item_sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
                $item_stmt = $conn->prepare($item_sql);
                $item_stmt->bind_param("iiid", $order_id, $item_id, $qty, $price);
                $item_stmt->execute();
                $item_stmt->close();
            }
        }
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        echo "success:Order placed successfully. Order ID: " . $order_id;
    } else {
        echo "error:Failed to place order. Please try again.";
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>