<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/dashboard_user.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <?php include '../Includes/navbars/Navbar_user.php'; ?>
        <body>
    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="../Images/user-dash-slider-1.jpg" alt="">
                <div class="slide-content">
                    <h3>Beautiful Mountain Landscape</h3>
                    <p>Experience the breathtaking views of majestic mountains and serene valleys.</p>
                </div>
            </div>
            <div class="slide">
                <img src="../Images/user-dash-slider-2.jpg" alt="">
                <div class="slide-content">
                    <h3>Tropical Beach Paradise</h3>
                    <p>Relax on pristine sandy beaches with crystal clear waters and stunning sunsets.</p>
                </div>
            </div>
            <div class="slide">
                <img src="../Images/user-dash-slider-3.jpg" alt="">
                <div class="slide-content">
                    <h3>Enchanted Forest</h3>
                    <p>Explore lush green forests with diverse wildlife and peaceful walking trails.</p>
                </div>
            </div>
            
        </div>
        
        <div class="navigation">
            <div class="nav-btn active"></div>
            <div class="nav-btn"></div>
            <div class="nav-btn"></div>
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.slider');
            const slides = document.querySelectorAll('.slide');
            const navBtns = document.querySelectorAll('.nav-btn');
            
            let currentSlide = 0;
            const slideCount = slides.length;
            let autoSlideInterval;
            
            // Function to update slider position
            function updateSlider() {
                slider.style.transform = `translateX(-${currentSlide * 25}%)`;
                
                // Update navigation buttons
                navBtns.forEach((btn, index) => {
                    if (index === currentSlide) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
            
            // Function for next slide
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slideCount;
                updateSlider();
            }
            
            // Function for previous slide
            function prevSlide() {
                currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                updateSlider();
            }
            
            // Set up auto-sliding
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000);
            }
            
            // Stop auto-sliding
            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }
            
            // Add event listeners to navigation buttons
            navBtns.forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlider();
                    stopAutoSlide();
                    startAutoSlide();
                });
            });
            
            // Pause auto-slide when hovering over slider
            const sliderContainer = document.querySelector('.slider-container');
            sliderContainer.addEventListener('mouseenter', stopAutoSlide);
            sliderContainer.addEventListener('mouseleave', startAutoSlide);
            
            // Start auto-sliding initially
            startAutoSlide();
        });
    </script>
    </header>
</body>
</html>