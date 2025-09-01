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
