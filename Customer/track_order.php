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
    <title>Active Orders</title>
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #f9f9f9;
            --text-color: #333;
            --border-color: #e0e0e0;
            --hover-color: #f0f5ff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #eee;
            padding: 2rem;
        }

        h2 {
            text-align: center;
            color: #4361ee;
            margin-bottom: 1.5rem;
        }

        #orders-content {
            min-height: 100px;
            position: relative;
        }

        .placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #4361ee;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s;
        }

        .placeholder.active {
            opacity: 1;
            pointer-events: auto;
        }

        .order-card {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f9f9f9;
        }

        .order-header {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .order-status {
            color: #4361ee;
            font-weight: bold;
        }

        .order-details {
            font-size: 0.98rem;
            color: #555;
        }

        .no-orders {
            text-align: center;
            color: #aaa;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <header><?php include '../Includes/navbars/Navbar_user.php'; ?></header>
    <main>
        <div class="container">
            <h2>Your Active Orders</h2>
            <div id="orders-content">
                <div class="placeholder" id="orders-placeholder">
                    <div class="order-card"></div>
                    <div class="order-header">Order # <span class="order-status"></span></div>
                    <div class="order-details"></div>
                    <p>Type: <?php echo htmlspecialchars($row['order_type']); ?><br>
</p>
                    </div>

                </div>
            </div>
    </main>
    <footer><?php include '../Includes/footers/Footer_user.php'; ?></footer>
    <script>
        function fetchOrders() {
            var container = document.getElementById('orders-content');
            var placeholder = document.getElementById('orders-placeholder');
            placeholder.classList.add('active');
            setTimeout(function () {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'ajax_active_orders.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        container.innerHTML = xhr.responseText + '<div class="placeholder" id="orders-placeholder">Loading...</div>';
                    } else {
                        container.innerHTML = '<div class="no-orders">Error loading orders.</div><div class="placeholder" id="orders-placeholder">Loading...</div>';
                    }
                    setTimeout(function () {
                        var newPlaceholder = document.getElementById('orders-placeholder');
                        if (newPlaceholder) newPlaceholder.classList.remove('active');
                    }, 400);
                };
                xhr.send();
            }, 300);
        }
        document.addEventListener('DOMContentLoaded', function () {
            fetchOrders();
            setInterval(fetchOrders, 3000);
        });
    </script>
</body>

</html>