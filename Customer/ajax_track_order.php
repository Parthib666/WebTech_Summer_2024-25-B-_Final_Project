<?php
session_start();
include '../Config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if ($order_id === '') {
    echo json_encode(['error' => 'Order ID required']);
    exit;
}

$sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ? AND status IN ('active', 'processing', 'preparing', 'out_for_delivery')";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'order' => $row]);
} else {
    echo json_encode(['success' => false, 'error' => 'No active order found for this ID.']);
}
$stmt->close();
?>
