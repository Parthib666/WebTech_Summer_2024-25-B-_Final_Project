<?php
// navbar.php

// Define the current page for active state highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="home.php">DineSmart</a>
            </div>
            <ul class="nav-menu">
                <li><a href="../Customer/menu_customer.php" style="color: inherit; text-decoration: none;">Menu</a></li>
                <li><a href="../Commons/special_offers.php" style="color: inherit; text-decoration: none;">Special Offers</a></li>
                <li><a href="../Customer/contacts.php" style="color: inherit; text-decoration: none;">Contact Us</a></li>
                <li><a href="../Customer/booking.php" style="color: inherit; text-decoration: none;">Book Table</a></li>
            </ul>
            <div class="nav-user-options">
                <i class="fa-solid fa-user"></i>
                <i class="fa-solid fa-cart-plus"></i>
                <i class="fa-solid fa-heart"></i>
                <button class="btn-order">Order Now</button>
            </div>
        </div>
    </nav>
