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
                    <p>Indulge in our sizzling beef stir fry served over a bed of fluffy steamed rice, seasoned to perfection with vibrant peppers and a savory sauce that melts in your mouth.</p>
                </div>
            </div>
            <div class="slide">
                <img src="../Images/user-dash-slider-2.jpg" alt="">
                <div class="slide-content">
                    <p>Experience the comforting warmth of our rich, slow-simmered soup made with fresh vegetables, hearty spices, and a touch of love in every spoonful.</p>
                </div>
            </div>
            <div class="slide">
                <img src="../Images/user-dash-slider-3.jpg" alt="">
                <div class="slide-content">
                    <p>Kickstart your day with our signature skillet of farm-fresh eggs, colorful bell peppers, and garden-fresh herbs, perfectly cooked to awaken your taste buds.</p>
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
                autoSlideInterval = setInterval(nextSlide, 3000);
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