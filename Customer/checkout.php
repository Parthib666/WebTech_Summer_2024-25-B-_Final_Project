<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Customer/Login_page.php');
    exit;
}
include '../Config/db_connection.php';
include '../Customer/place_order.php';
$orderSuccess = false;
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
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
    <style>
        /* Checkout Container */
        .checkout-container {
            max-width: 600px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        /* Checkout Form */
        .checkout-form {
            margin-top: 2rem;
        }

        .checkout-form input,
        .checkout-form textarea,
        .checkout-form select {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-family: inherit;
            font-size: 1rem;
        }

        .checkout-btn {
            padding: 0.75rem 2rem;
            background: #0d111d;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background: #1a243d;
        }

        /* Order Success Message */
        .order-success {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
            padding: 1rem;
            background: #f8fff8;
            border: 1px solid #28a745;
            border-radius: 4px;
        }

        /* Order Summary */
        #cart-list {
            list-style-type: none;
            padding: 0;
            margin: 0 0 1.5rem 0;
        }

        #cart-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        /* Price Summary */
        .checkout-container>div:has(strong) {
            margin: 1.5rem 0;
            padding: 1rem;
            background: #f8f8f8;
            border-radius: 6px;
            line-height: 1.8;
        }

        .checkout-container>div:has(strong) select {
            width: auto;
            margin-left: 0.5rem;
        }

        .checkout-container>div:has(strong) input[type="text"] {
            width: 120px;
            display: inline-block;
            margin-bottom: 0;
        }

        .checkout-container>div:has(strong) button {
            padding: 0.25rem 0.75rem;
            background: #0d111d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .checkout-container>div:has(strong) button:hover {
            background: #1a243d;
        }
    </style>
</head>

<body>
    <header><?php include '../Includes/navbars/Navbar_user.php'; ?></header>
    <main>
        <div class="checkout-container">
            <h2 style="text-align: center;">Checkout</h2>
            <?php if ($orderSuccess): ?>
                <div class="order-success">Your order has been placed successfully!</div>
            <?php else: ?>
                <div class="checkout-summary" style="margin:1.5rem 0;padding:1rem;background:#f8f8f8;border-radius:6px;">
                    <label><strong>Subtotal:</strong></label>
                    <span id="subtotal">BDT 0</span><br>
                    <label for="order_type"><strong>Order Type:</strong></label>
                    <select id="order_type" name="order_type" style="margin-bottom:10px;">
                        <option value="dinein">Dine-in</option>
                        <option value="takeaway">Takeaway</option>
                        <option value="delivery">Delivery</option>
                    </select><br>
                    <label><strong>Promo Code:</strong></label>
                    <input type="text" id="promo_code" name="promo_code" style="width:120px;">
                    <button type="button" onclick="applyPromo()">Apply</button><br>
                    <label><strong>Discount:</strong></label>
                    <span id="discount">BDT 0</span><br>
                    <label><strong>Service Charge:</strong></label>
                    <span id="service_charge">BDT 0</span><br>
                    <label><strong>Delivery Charge:</strong></label>
                    <span id="delivery_charge">BDT 0</span><br>
                    <label><strong>Total Price:</strong></label>
                    <span id="total_price">BDT 0</span><br>
                    <div id="item_details"></div>
                </div>
                <form method="post" class="checkout-form">
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
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'checkout_charges.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var parts = xhr.responseText.split('|');
                    // parts[0]: discount, parts[1]: service_charge, parts[2]: delivery_charge, parts[3]: total_price, parts[4]: item_details
                    document.getElementById('discount').innerText = 'BDT ' + parseFloat(parts[0]).toFixed(2);
                    document.getElementById('service_charge').innerText = 'BDT ' + parseFloat(parts[1]).toFixed(2);
                    document.getElementById('delivery_charge').innerText = 'BDT ' + parseFloat(parts[2]).toFixed(2);
                    document.getElementById('total_price').innerText = 'BDT ' + parseFloat(parts[3]).toFixed(2);
                    document.getElementById('item_details').innerHTML = '<strong>Items:</strong><br>' + parts[4];
                    // Calculate subtotal: total_price + discount - service_charge - delivery_charge
                    var subtotal = parseFloat(parts[3]) + parseFloat(parts[0]) - parseFloat(parts[1]) - parseFloat(parts[2]);
                    document.getElementById('subtotal').innerText = 'BDT ' + subtotal.toFixed(2);
                }
            };
            xhr.send('order_type=' + encodeURIComponent(orderType) + '&promo_code=' + encodeURIComponent(promoCode));
        }
        document.getElementById('order_type').addEventListener('change', updateCharges);
        document.getElementById('promo_code').addEventListener('input', updateCharges);
        window.onload = updateCharges;
        function applyPromo() {
            updateCharges();
        }
        function placeOrder() {
            var paymentMethod = document.getElementById('payment_method').value;
            var orderType = document.getElementById('order_type').value;
            var subtotal = document.getElementById('subtotal').value.replace('BDT ', '');
            var discount = document.getElementById('discount').value.replace('BDT ', '');
            var serviceCharge = document.getElementById('service_charge').value.replace('BDT ', '');
            var deliveryCharge = document.getElementById('delivery_charge').value.replace('BDT ', '');
            var totalPrice = document.getElementById('total_price').value.replace('BDT ', '');
            var paymentMethod = document.getElementById('payment_method').value;
            var paid = false;
            if (paymentMethod == '') {
                alert('Please select a payment method.');
                return;
            } else if (paymentMethod == 'card' || paymentMethod == 'mobile') {
                // Redirect to card/mobile payment gateway
                paid = true; // Assume payment is successful for demo purposes
            }
        }
    </script>
</body>

</html>
<?php $conn->close(); ?>