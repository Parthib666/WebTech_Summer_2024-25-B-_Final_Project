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
                <div class="slide-content" id="slide-content-1">
                    <p>Kickstart your day with our signature skillet of farm-fresh eggs, colorful bell peppers, and garden-fresh herbs, perfectly cooked to awaken your taste buds.</p>
                </div>
            </div>
            <div class="slide">
                <img src="../Images/user-dash-slider-2.jpg" alt="">
                <div class="slide-content" id="slide-content-2">
                <p>Indulge in our sizzling beef stir fry served over a bed of fluffy steamed rice, seasoned to perfection with vibrant peppers and a savory sauce that melts in your mouth.</p>  
                </div>
            </div>
            <div class="slide">
                <img src="../Images/user-dash-slider-3.jpg" alt="">
                <div class="slide-content" id="slide-content-3">
                <p>Experience the comforting warmth of our rich, slow-simmered soup made with fresh vegetables, hearty spices, and a touch of love in every spoonful.</p>
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
    <main>
        <div class="dashboard-content-container">
            <div class="dashboard-item">
                <div class="dashboard-item-image"> <img src="../Images/Cooking-user-dash-item-1.jpg" alt="Dish Image"></div>
                <div class="dashboard-item-content">
                    <center><h3>Experience The Modern Culinary Journey</h3></center>
                    <p>At Bennie, we take pride in offering  modern European and Mediterranean-inspired dining experience. Our menu features a variety of carefully crafted dishes, from fresh seafood like grilled octopus, calamari and king prawns to tender meats, including our perfectly cooked ribeye steak. Vegetarian and vegan guests can enjoy vibrant, seasonal plates such as aubergine or broccoli tempura and roasted vegetable platters. Every dish is made with the freshest ingredients and showcases the bold, natural flavours.</p>
                    <center><button>View Menu</button></center>
                </div>
            </div>
            <div class="dashboard-item">
                
                <div class="dashboard-item-content">
                    <center><h3>Vision</h3></center>
                    <p>To be a sanctuary where the art of nourishment meets the poetry of connection.

We believe in the simple magic of a shared meal—where earth's honest ingredients are transformed into vibrant cuisine that tells a story of season and place.

Our space is designed as a haven of warmth and golden light, a modern hearth for making memories. Here, every guest is welcomed as family, and every moment is slowed to the pace of genuine conversation.

We don't just serve dinner; we craft an experience for the senses, hoping you leave not just satisfied, but truly restored.</p>
                    <center><button>Book a Table</button></center>
                </div>
                <div class="dashboard-item-image"> <img src="../Images/Vision-user-dash.jpg" alt="Dish Image"></div>
            </div>
            <div class="dashboard-item">
                <center><h3>Support</h3></center>
                <p>Get help and support for your orders.</p>
            </div>
        </div>
    </main>
</body>
</html>