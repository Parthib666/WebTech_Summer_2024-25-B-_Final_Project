<?php
session_start();
include '../Config/db_connection.php';
if (!isset($_SESSION['user_id'])) {
    echo '<div class="no-orders">Not logged in.</div>';
    exit;
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = ? AND status IN ('pending', 'preparing', 'ready', 'delivering') ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="order-card">';
        echo '<div class="order-header">Order #' . htmlspecialchars($row['order_id']) . ' <span class="order-status">' . htmlspecialchars($row['status']) . '</span></div>';
        echo '<div class="order-details">';
        echo 'Type: <span class="order-type">' . htmlspecialchars($row['order_type']) . '</span><br>';
        echo 'Subtotal: <span class="order-subtotal">₹' . htmlspecialchars($row['subTotal']) . '</span><br>';
        echo 'Discount: <span class="order-discount">₹' . htmlspecialchars($row['discount']) . '</span><br>';
        echo 'Service Charge: <span class="order-service-charge">₹' . htmlspecialchars($row['service_charge']) . '</span><br>';
        echo 'Delivery Charge: <span class="order-delivery-charge">₹' . htmlspecialchars($row['delivery_charge']) . '</span><br>';
        echo '<strong>Total: <span class="order-total">₹' . htmlspecialchars($row['total']) . '</span></strong><br>';
        echo 'Paid: <span class="order-paid">' . ($row['paid'] == 1 ? 'Yes' : 'No') . '</span><br>';
        echo 'Date: <span class="order-date">' . htmlspecialchars($row['date']) . '</span><br>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="no-orders">No active orders found.</div>';
}
$stmt->close();
?>
