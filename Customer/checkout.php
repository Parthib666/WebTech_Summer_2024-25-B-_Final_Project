<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Commons/Login_page.php');
    exit;
}
include '../Config/db_connection.php';
$orderSuccess = false;

// Handle order placement
if (isset($_POST['place_order'])) {
    // Get form data
    $order_type = $_POST['order_type'];
    $payment_method = $_POST['payment_method'];
    $promo_code = isset($_POST['promo_code']) ? $_POST['promo_code'] : '';
    
    // Calculate charges
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $subtotal = 0;
    
    // Calculate subtotal
    if (!empty($cart)) {
        $ids = array_keys($cart);
        $ids_str = implode(',', array_map('intval', $ids));
        $sql = "SELECT * FROM menu WHERE menu_item_id IN ($ids_str)";
        $result = $conn->query($sql);
        while ($item = $result->fetch_assoc()) {
            $item_id = $item['menu_item_id'];
            $qty = $cart[$item_id];
            $subtotal += $item['price'] * $qty;
        }
    }
    
    // Calculate other charges
    $discount = 0;
    $service_charge = 0;
    $delivery_charge = 0;
    
    // Apply promo code if exists
    if (!empty($promo_code)) {
        $sql = "SELECT * FROM promotion WHERE coupon_name = ? AND is_active = 1";
        $promo_stmt = $conn->prepare($sql);
        $promo_stmt->bind_param('s', $promo_code);
        $promo_stmt->execute();
        $promo_result = $promo_stmt->get_result();
        if ($promo_row = $promo_result->fetch_assoc()) {
            $discount = $subtotal * ($promo_row['discount'] / 100);
        }
        $promo_stmt->close();
    }
    
    // Calculate service and delivery charges
    $subtotal_after_discount = $subtotal - $discount;
    if ($order_type === 'dinein') {
        $service_charge = $subtotal_after_discount * 0.05;
    } elseif ($order_type === 'delivery') {
        $delivery_charge = 50;
    }
    
    $total_price = $subtotal_after_discount + $service_charge + $delivery_charge;
    $paid = ($payment_method === 'cash') ? 0 : 1;
    
    // Insert order
    $order_sql = "INSERT INTO orders (order_type, subTotal, discount, service_charge, delivery_charge, total, paid, user_id, date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("sdddddis", $order_type, $subtotal, $discount, $service_charge, $delivery_charge, $total_price, $paid, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        
        // Insert order items
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
        $orderSuccess = true;
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../CSS/menu_customer.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <link rel="stylesheet" href="../CSS/checkout.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
</head>

<body>
    <header><?php include '../Includes/navbars/Navbar_user.php'; ?></header>
    <main>
        <div class="checkout-container">
            <h2 style="text-align: center;">Checkout</h2>
            
            <!-- Display cart items -->
            <div style="margin-bottom: 20px;">
                <h3>Your Order</h3>
                <div id="cart-items">
                    <?php
                    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                    if (empty($cart)) {
                        echo '<p>Your cart is empty</p>';
                    } else {
                        $ids = array_keys($cart);
                        $ids_str = implode(',', array_map('intval', $ids));
                        $sql = "SELECT * FROM menu WHERE menu_item_id IN ($ids_str)";
                        $result = $conn->query($sql);
                        
                        while ($item = $result->fetch_assoc()) {
                            $item_id = $item['menu_item_id'];
                            $qty = $cart[$item_id];
                            echo '<div class="cart-item">';
                            echo '<span>' . $item['name'] . ' x ' . $qty . '</span>';
                            echo '<span id="item-price' . $item_id . '">BDT ' . number_format($item['price'] * $qty, 2) . '</span>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            
            <?php if ($orderSuccess): ?>
                <div class="order-success">Your order has been placed successfully!</div>
                <center><a href="../Customer/menu_customer.php" class="checkout-btn">Continue Shopping</a></center>
            <?php else: ?>
                <form method="post" class="checkout-form">
                    <div class="checkout-summary" style="margin:1.5rem 0;padding:1rem;background:#f8f8f8;border-radius:6px;">
                        <label><strong>Subtotal:</strong></label>
                        <span id="subtotal">BDT 0</span><br>
                        <label for="order_type"><strong>Order Type:</strong></label>
                        <select id="order_type" name="order_type" style="margin-bottom:10px;" onchange="updateCharges()">
                            <option value="dinein">Dine-in</option>
                            <option value="takeaway">Takeaway</option>
                            <option value="delivery">Delivery</option>
                        </select><br>
                        <label><strong>Promo Code:</strong></label>
                        <input type="text" id="promo_code" name="promo_code" style="width:120px;" oninput="updateCharges()">
                        <label><strong>Discount:</strong></label>
                        <span id="discount">BDT 0</span><br>
                        <label><strong>Service Charge:</strong></label>
                        <span id="service_charge">BDT 0</span><br>
                        <label><strong>Delivery Charge:</strong></label>
                        <span id="delivery_charge">BDT 0</span><br>
                        <label><strong>Total Price:</strong></label>
                        <span id="total_price">BDT 0</span><br>
                    </div>
                    
                    <label for="payment_method"><strong>Payment Method:</strong></label>
                    <select id="payment_method" name="payment_method" style="margin-bottom:10px;">
                        <option value="cash">Cash on Delivery</option>
                        <option value="card">Card</option>
                        <option value="mobile">Mobile Banking</option>
                    </select><br>
                    
                    <center><button type="submit" name="place_order" class="checkout-btn">Place Order</button></center>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <footer><?php include '../Includes/footers/Footer_user.php'; ?></footer>
    <script>
        function updateCharges() {
            var orderType = document.getElementById('order_type').value;
            var promoCode = document.getElementById('promo_code').value;
            
            // Calculate charges based on order type and promo code
            var subtotal = 0;
            var cartItems = document.querySelectorAll('#cart-items .cart-item');
            
            cartItems.forEach(function(item) {
                var priceText = item.querySelector('span[id^="item-price"]').textContent;
                // Remove any extra characters
                var price = parseFloat(priceText.replace(/[^\d.]/g, ''));
                console.log(price);
                subtotal += price;
            });
            
            document.getElementById('subtotal').innerText = 'BDT ' + subtotal.toFixed(2);
            
            // Calculate discount
            var discount = 0;
            if (promoCode !== '') {
                // validate promo code and get discount
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'checkout_charges.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var parts = xhr.responseText.split('|');
                        discount = parseFloat(parts[0]);
                        document.getElementById('discount').innerText = 'BDT ' + discount.toFixed(2);
                        // Update service/delivery/total based on new discount
                        var subtotal = 0;
                        var cartItems = document.querySelectorAll('#cart-items .cart-item');
                        cartItems.forEach(function(item) {
                            var priceText = item.querySelector('span:last-child').textContent;
                            var price = parseFloat(priceText.replace(/[^\d.]/g, ''));
                            subtotal += price;
                        });
                        var orderType = document.getElementById('order_type').value;
                        var serviceCharge = 0;
                        var deliveryCharge = 0;
                        if (orderType === 'dinein') {
                            serviceCharge = (subtotal - discount) * 0.05;
                        } else if (orderType === 'delivery') {
                            deliveryCharge = 50;
                        }
                        document.getElementById('service_charge').innerText = 'BDT ' + serviceCharge.toFixed(2);
                        document.getElementById('delivery_charge').innerText = 'BDT ' + deliveryCharge.toFixed(2);
                        var total = subtotal - discount + serviceCharge + deliveryCharge;
                        document.getElementById('total_price').innerText = 'BDT ' + total.toFixed(2);
                    }
                };
                xhr.send('order_type=' + encodeURIComponent(orderType) + '&promo_code=' + encodeURIComponent(promoCode));
            } else {
                document.getElementById('discount').innerText = 'BDT 0.00';
                // ...existing code for service/delivery/total...
                var subtotal = 0;
                var cartItems = document.querySelectorAll('#cart-items .cart-item');
                cartItems.forEach(function(item) {
                    var priceText = item.querySelector('span:last-child').textContent;
                    var price = parseFloat(priceText.replace('BDT ', ''));
                    subtotal += price;
                });
                var orderType = document.getElementById('order_type').value;
                var serviceCharge = 0;
                var deliveryCharge = 0;
                if (orderType === 'dinein') {
                    serviceCharge = subtotal * 0.05;
                } else if (orderType === 'delivery') {
                    deliveryCharge = 50;
                }
                document.getElementById('service_charge').innerText = 'BDT ' + serviceCharge.toFixed(2);
                document.getElementById('delivery_charge').innerText = 'BDT ' + deliveryCharge.toFixed(2);
                var total = subtotal + serviceCharge + deliveryCharge;
                document.getElementById('total_price').innerText = 'BDT ' + total.toFixed(2);
            }
        }
        
        // Initialize charges on page load
        window.onload = function() {
            updateCharges();
        };
    </script>
</body>

</html>
<?php $conn->close(); ?>