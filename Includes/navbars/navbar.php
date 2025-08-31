<?php
// navbar.php

// Define the current page for active state highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="navbar.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="home.php">DineSmart</a>
            </div>
            <ul class="nav-menu">
                <li>Menu</li>
                <li>Special Offers</li>
                <li>Contact Us</li>
                <li>Book Table</li>
            </ul>
            <div class="nav-user-options">
                <i class="fa-solid fa-user"></i>
                <i class="fa-solid fa-cart-plus"></i>
                <i class="fa-solid fa-heart"></i>
                <button class="btn-order">Order Now</button>
            </div>
        </div>
    </nav>
</body>

</html>