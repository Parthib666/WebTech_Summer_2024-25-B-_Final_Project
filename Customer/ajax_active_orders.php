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
		echo 'Type: ' . htmlspecialchars($row['order_type']) . '<br>';
		echo 'Subtotal: BDT ' . htmlspecialchars($row['subTotal']) . '<br>';
		echo 'Discount: BDT ' . htmlspecialchars($row['discount']) . '<br>';
		echo 'Service Charge: BDT ' . htmlspecialchars($row['service_charge']) . '<br>';
		echo 'Delivery Charge: BDT ' . htmlspecialchars($row['delivery_charge']) . '<br>';
		echo '<strong>Total: BDT ' . htmlspecialchars($row['total']) . '</strong><br>';
		echo 'Paid: ' . ($row['paid'] == 1 ? 'Yes' : 'No') . '<br>';
		echo 'Date: ' . htmlspecialchars($row['date']) . '<br>';
		echo '</div>';
		echo '</div>';
	}
} else {
	echo '<div class="no-orders">No active orders found.</div>';
}
$stmt->close();
?>
