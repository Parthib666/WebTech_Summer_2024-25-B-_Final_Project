<?php
// navbar.php

// Define the current page for active state highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<footer class="restaurant-footer">
        <div class="footer-container">
            <div class="footer-section footer-about">
                <h3>Savory Delights</h3>
                <p>Experience the finest culinary journey with our chef's specially curated menu, using locally-sourced ingredients and traditional cooking methods.</p>
                <div class="footer-social">
                    <a href="#" class="social-icon">fb</a>
                    <a href="#" class="social-icon">ig</a>
                    <a href="#" class="social-icon">tw</a>
                    <a href="#" class="social-icon">yt</a>
                </div>
            </div>
            
            <div class="footer-section footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Menu</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Reservations</a></li>
                    <li><a href="#">Gallery</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-section footer-hours">
                <h3>Opening Hours</h3>
                <p><span class="day">Monday - Thursday:</span> 11:00 AM - 10:00 PM</p>
                <p><span class="day">Friday - Saturday:</span> 11:00 AM - 11:00 PM</p>
                <p><span class="day">Sunday:</span> 10:00 AM - 9:00 PM</p>
                <p><span class="day">Brunch:</span> Weekends 10:00 AM - 2:00 PM</p>
            </div>
            
            <div class="footer-section footer-contact">
                <h3>Contact Us</h3>
                <p><span class="contact-icon">📍</span> 123 Gourmet Street, Culinary District</p>
                <p><span class="contact-icon">📞</span> (555) 123-4567</p>
                <p><span class="contact-icon">✉️</span> info@savorydelights.com</p>
                <p><span class="contact-icon">📱</span> Text Reservations: (555) 765-4321</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2023 Savory Delights Restaurant. All rights reserved. | Designed with passion for good food.</p>
        </div>
    </footer>