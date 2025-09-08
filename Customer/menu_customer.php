<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="../CSS/menu_customer.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
</head>
<body>

    <header>
        <?php include '../Includes/navbars/Navbar_user.php'; ?>
    </header>
    <main>
    <!-- Menu Container -->
    <div id="menu" class="menu-container"></div>
    <!-- Popup Notification -->
    <div id="cart-popup" style="position:fixed;left:90%;top:4.5rem;transform:translateX(-50%);background:#00134bff;color:#fff;padding:1rem 2rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.15);font-size:1.1rem;z-index:2100;display:none;opacity:0;transition:opacity 0.5s;">Item added to cart!</div>
    <!-- View Cart Button -->
    <button id="view-cart-btn" onclick="window.location.href='view_cart.php'">View Cart</button>

    <script>
    // Load menu items dynamically
    function loadMenu() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'menu_itms_customer.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('menu').innerHTML = xhr.responseText;
                attachAddToCartHandlers();
            }
        };
        xhr.send('action=load');
    }

    // Attach add to cart button handlers
    function attachAddToCartHandlers() {
        var buttons = document.querySelectorAll('.add-cart-btn');
        buttons.forEach(function(btn) {
            btn.onclick = function() {
                var itemId = this.getAttribute('data-id');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'menu_itms_customer.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementById('view-cart-btn').style.display = 'block';
                        showCartPopup();
                    }
                };
                xhr.send('action=add&id=' + itemId);
            };
        });
    }

    function showCartPopup() {
        var popup = document.getElementById('cart-popup');
        popup.style.display = 'block';
        setTimeout(function() {
            popup.style.opacity = 1;
        }, 10);
        setTimeout(function() {
            popup.style.opacity = 0;
            setTimeout(function() {
                popup.style.display = 'none';
            }, 500);
        }, 3000);
    }

    // Initial load
    window.onload = loadMenu;
    </script>
    </main>

    <footer>
        <?php include '../Includes/footers/Footer_user.php'; ?>
    </footer>
</body>
</html>
