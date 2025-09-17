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
include("../Config/db_connection.php");
// User is logged in, show the menu
$sql = "SELECT * FROM menu ORDER BY category, name";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[$row['category']][] = $row;

}
$conn->close();
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
    <div id="menu" class="menu-container">
         <?php
                foreach ($categories as $category => $items) {
                    echo '<div id="menu-item-category"><h2 style="width:100%;text-align:center;">' . strtoupper(htmlspecialchars($category)) . '</h2></div>';
                    foreach ($items as $item) {
                        echo '<div class="menu-card">';
                        if (!empty($item['image'])) {
                            echo '<img src="../Images/menu_img/' . htmlspecialchars($item['image']) . ".webp" . '" alt="' . htmlspecialchars($item['name']) . '">';
                        }
                        echo '<h3>' . htmlspecialchars($item['name']) . '</h3>';
                        echo '<p>' . htmlspecialchars($item['description']) . '</p>';
                        echo '<strong>BDT ' . htmlspecialchars($item['price']) . '</strong>';
                        echo '<button class="add-to-cart" data-id="' . $item['menu_item_id'] . '" onclick="AddToCart(this)">Add to Cart</button>';
                        echo '</div>';
                    }
                }
                ?>
    </div>
    <div id="cart-popup" style="position:fixed;left:90%;top:4.5rem;transform:translateX(-50%);background:#00134bff;color:#fff;padding:1rem 2rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.15);font-size:1.1rem;z-index:2100;display:none;transition:opacity 0.5s;">
        Item added to cart!
    </div>
    <script>
    // Attach add to cart button handlers
    function AddToCart(button) {
                showCartPopup();
                var id = button.getAttribute('data-id');
                console.log(id);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'menuToCart_ajax.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.status === 200) {
                        if (xhr.responseText.trim() === 'added') {
                            showCartPopup();
                        }
                    }
                };
                xhr.send('action=add&id=' + encodeURIComponent(id));
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
    </script>
    </main>

    <footer>
        <?php include '../Includes/footers/Footer_user.php'; ?>
    </footer>
</body>
</html>
