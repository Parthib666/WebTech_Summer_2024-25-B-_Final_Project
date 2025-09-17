<?php
session_start();
include '../Config/db_connection.php';

// Cart structure: $_SESSION['cart'][item_id] = quantity
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Your Cart</title>
	<link rel="stylesheet" href="../CSS/menu_customer.css">
	<link rel="stylesheet" href="../CSS/navbar.css">
	<link rel="stylesheet" href="../CSS/footer_user.css">
	<script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
	<style>
		.cart-container {
			max-width: 600px;
			margin: 2rem auto;
			background: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
			padding: 2rem;
		}

		.cart-item {
			display: flex;
			align-items: center;
			margin-bottom: 1rem;
		}

		.cart-item img {
			width: 80px;
			height: 60px;
			object-fit: cover;
			border-radius: 6px;
			margin-right: 1rem;
		}

		.cart-total {
			font-size: 1.2rem;
			font-weight: bold;
			margin-top: 2rem;
		}

		.checkout-btn {
			margin-top: 2rem;
			padding: 0.75rem 2rem;
			background: #0d111d;
			color: #fff;
			border: none;
			border-radius: 6px;
			cursor: pointer;
			font-size: 1rem;
		}

		.btn-qty {
			background: none;
			border: none;
			cursor: pointer;
			font-size: 1rem;
		}
	</style>
</head>

<body>
	<header>
		<?php include '../Includes/navbars/Navbar_user.php'; ?>
	</header>
	<main>
		<div class="cart-container">
			<h2>Your Cart</h2>
			<?php if (empty($cart)) {
				echo '<p>Your cart is empty.</p>';
			} else {
				$ids = array_keys($cart);
				$ids_str = implode(',', array_map('intval', $ids));
				$sql = "SELECT * FROM menu WHERE menu_item_id IN ($ids_str)";
				$result = $conn->query($sql);
				$total = 0;
				while ($item = $result->fetch_assoc()) {
					$item_id = $item['menu_item_id'];
					$qty = $cart[$item_id];
					$subtotal = $item['price'] * $qty;
					$total += $subtotal;
					echo '<div class="cart-item">';
					if (!empty($item['image'])) {
						echo '<img src="../Images/menu_img/' . htmlspecialchars($item['image']) . '.webp" alt="' . htmlspecialchars($item['name']) . '">';
					}
					echo '<div class="cart-item-details">';
					echo '<h4>' . htmlspecialchars($item['name']) . '</h4>';
					echo '<p>BDT ' . htmlspecialchars($item['price']) . ' x ' . $qty . '</p>';
					echo '<form method="post" style="display:flex;align-items:center;gap:8px;margin-bottom:0.5rem;">';
					echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
					echo '<button type="submit" name="decrease_qty" class="btn-qty" title="Decrease quantity">-</button>';
					echo '<span style="padding:0 10px;">' . $qty . '</span>';
					echo '<button type="submit" name="increase_qty" class="btn-qty" title="Increase quantity">+</button>';
					echo '<button type="submit" name="delete_item" class="btn-qty" title="Remove from cart" style="margin-left:10px;border-radius:4px;"><i class="fa-solid fa-trash"></i></button>';
					echo '</form>';
					echo '</div>';
					echo '</div>';
				}
				echo '<div class="cart-total">Total: BDT ' . $total . '</div>';
				echo '<form method="post"><button type="submit" name="checkout" class="checkout-btn">Checkout</button></form>';
			}
			?>
		</div>
	</main>
	<footer>
		<?php include '../Includes/footers/Footer_user.php'; ?>
	</footer>
</body>

</html>';
<?php
// Handle increase quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['increase_qty'], $_POST['item_id'])) {
	$item_id = intval($_POST['item_id']);
	if (isset($_SESSION['cart'][$item_id])) {
		$_SESSION['cart'][$item_id]++;
	}
	echo '<script>window.location.href="view_cart.php";</script>';
	exit;
}

// Handle decrease quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decrease_qty'], $_POST['item_id'])) {
	$item_id = intval($_POST['item_id']);
	if (isset($_SESSION['cart'][$item_id]) && $_SESSION['cart'][$item_id] > 1) {
		$_SESSION['cart'][$item_id]--;
	}
	echo '<script>window.location.href="view_cart.php";</script>';
	exit;
}

// Handle delete item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'], $_POST['item_id'])) {
	$item_id = intval($_POST['item_id']);
	unset($_SESSION['cart'][$item_id]);
	echo '<script>window.location.href="view_cart.php";</script>';
	exit;
}

// Handle checkout (simple example: clear cart)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
	echo '<script>window.location.href="checkout.php";</script>';
	exit;
}
$conn->close();
?>