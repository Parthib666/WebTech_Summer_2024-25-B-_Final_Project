<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
	header('Location: ../Commons/Login_Page.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Order History</title>
	<link rel="stylesheet" href="../CSS/navbar.css">
	<link rel="stylesheet" href="../CSS/footer_user.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
	<style>
		body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; }
		.container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee; padding: 2rem; }
		h2 { text-align: center; color: #4361ee; margin-bottom: 1.5rem; }
		#orders-content { min-height: 100px; }
		.order-card { border: 1px solid #e0e0e0; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; background: #f9f9f9; }
		.order-header { font-weight: bold; color: #333; margin-bottom: 0.5rem; }
		.order-status { color: #4361ee; font-weight: bold; }
		.order-details { font-size: 0.98rem; color: #555; }
		.no-orders { text-align: center; color: #aaa; margin-top: 2rem; }
		.reload-btn { display: block; margin: 0 auto 1.5rem auto; padding: 0.5rem 1.2rem; background: #4361ee; color: #fff; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; }
		.reload-btn:hover { background: #3651d3; }
	</style>
</head>
<body>
	<header><?php include '../Includes/navbars/Navbar_user.php'; ?></header>
	<main>
		<div class="container">
			<h2>Your Order History</h2>
			<button class="reload-btn" onclick="fetchOrders()">Reload Orders</button>
			<div id="orders-content"><div class="no-orders">Loading...</div></div>
		</div>
	</main>
	<footer><?php include '../Includes/footers/Footer_user.php'; ?></footer>
	<script>
	function fetchOrders() {
		var container = document.getElementById('orders-content');
		container.innerHTML = '<div class="no-orders">Loading...</div>';
		var xhr = new XMLHttpRequest();
		xhr.open('GET', 'ajax_all_orders.php', true);
		xhr.onload = function() {
			if (xhr.status === 200) {
				container.innerHTML = xhr.responseText;
			} else {
				container.innerHTML = '<div class="no-orders">Error loading orders.</div>';
			}
		};
		xhr.send();
	}
	document.addEventListener('DOMContentLoaded', fetchOrders);
	</script>
</body>
</html>
