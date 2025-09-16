<?php
// navbar.php

// Define the current page for active state highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../customer/dashboard_customer.php">DineSmart</a>
            </div>
            <ul class="nav-menu">
                <li><a href="../Customer/menu_customer.php" style="color: inherit; text-decoration: none;">Menu</a></li>
                <li><a href="../Commons/special_offers.php" style="color: inherit; text-decoration: none;">Special Offers</a></li>
                <li><a href="../Customer/contacts.php" style="color: inherit; text-decoration: none;">Contact Us</a></li>
                <li><a href="../Customer/dashboard_customer.php#booking" style="color: inherit; text-decoration: none;">Book Table</a></li>
            </ul>
            <div class="nav-user-options">
                <a href="../Customer/view_cart.php" style="color: inherit; text-decoration: none;"><i class="fa-solid fa-cart-plus" style="scale: 1.3; padding: 5px;"></i></a>
                <a href="../Customer/favourites.php" style="color: inherit; text-decoration: none;"><i class="fa-solid fa-heart" style="scale: 1.3; padding: 5px;"></i></a>
                <a href="../Customer/user_profile.php" style="color: inherit; text-decoration: none;"><i class="fa-solid fa-user" style="scale: 1.3; padding: 5px;"></i></a>
                <button class="btn-order"><strong>Order Now</strong></button>
            </div>

    </nav>
