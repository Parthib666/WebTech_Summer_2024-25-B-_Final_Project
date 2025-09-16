<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../Commons/Login_page.php");
    } else {
        $user_id = $_SESSION["user_id"];
        $user_name = $_SESSION["username"];
        $user_email = $_SESSION["email"];
        $date = $_POST["date"];
        $timeStart = $_POST["timeStart"];
        $timeEnd = $_POST["timeEnd"];
        $tables = $_POST["tables"];
        if (isset($_POST["date"]) && isset($_POST["timeStart"]) && isset($_POST["timeEnd"]) && isset($_POST["tables"])) {
        include "../Config/db_connection.php";
        
        $sql = "INSERT INTO reservation (table_no, reservation_date, time_start, time_end,user_id) VALUES ($tables, '$date', '$timeStart', '$timeEnd', '$user_id')";
        $result = $conn->query($sql);
            echo "<script>alert('Booking successful!');</script>";
            $conn->close();
        }
        else {
            // Handle the case where not all fields are set
            echo "<script>alert('Please fill in all fields.');</script>";
        } 
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboard_user.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <?php include '../Includes/navbars/Navbar_user.php'; ?>

        <body>
            <div class="slider-container">
                <div class="slider">
                    <div class="slide">
                        <img src="../Images/user-dash-slider-1.webp" alt="">
                        <div class="slide-content" id="slide-content-1">
                            <p>Kickstart your day with our signature skillet of farm-fresh eggs, colorful bell peppers,
                                and garden-fresh herbs, perfectly cooked to awaken your taste buds.</p>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="../Images/user-dash-slider-2.webp" alt="">
                        <div class="slide-content" id="slide-content-2">
                            <p>Indulge in our sizzling beef stir fry served over a bed of fluffy steamed rice, seasoned
                                to perfection with vibrant peppers and a savory sauce that melts in your mouth.</p>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="../Images/user-dash-slider-3.webp" alt="">
                        <div class="slide-content" id="slide-content-3">
                            <p>Experience the comforting warmth of our rich, slow-simmered soup made with fresh
                                vegetables, hearty spices, and a touch of love in every spoonful.</p>
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
                document.addEventListener('DOMContentLoaded', function () {
                    const slider = document.querySelector('.slider');
                    const slides = document.querySelectorAll('.slide');
                    const navBtns = document.querySelectorAll('.nav-btn');

                    let currentSlide = 0;
                    const slideCount = slides.length;
                    let autoSlideInterval;

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

                    function nextSlide() {
                        currentSlide = (currentSlide + 1) % slideCount;
                        updateSlider();
                    }

                    function prevSlide() {
                        currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                        updateSlider();
                    }

                    function startAutoSlide() {
                        autoSlideInterval = setInterval(nextSlide, 3000);
                    }

                    function stopAutoSlide() {
                        clearInterval(autoSlideInterval);
                    }

                    navBtns.forEach((btn, index) => {
                        btn.addEventListener('click', () => {
                            currentSlide = index;
                            updateSlider();
                            stopAutoSlide();
                            startAutoSlide();
                        });
                    });

                    const sliderContainer = document.querySelector('.slider-container');
                    sliderContainer.addEventListener('mouseenter', stopAutoSlide);
                    sliderContainer.addEventListener('mouseleave', startAutoSlide);

                    startAutoSlide();
                });
            </script>
    </header>
    <main>

        <div class="dashboard-content-container">
            <div class="dashboard-item">
                <div class="dashboard-item-image"> <img src="../Images/Cooking-user-dash-item-1.webp" alt="Dish Image">
                </div>
                <div class="dashboard-item-content">
                    <center>
                        <h3>Experience The Modern Culinary Journey</h3>
                    </center>
                    <p>At Bennie, we take pride in offering modern European and Mediterranean-inspired dining
                        experience. Our menu features a variety of carefully crafted dishes, from fresh seafood like
                        grilled octopus, calamari and king prawns to tender meats, including our perfectly cooked ribeye
                        steak. Vegetarian and vegan guests can enjoy vibrant, seasonal plates such as aubergine or
                        broccoli tempura and roasted vegetable platters. Every dish is made with the freshest
                        ingredients and showcases the bold, natural flavours.</p>
                    <center><button>View Menu</button></center>
                </div>
            </div>
            <div class="dashboard-item">

                <div class="dashboard-item-content">
                    <center>
                        <h3>Vision</h3>
                    </center>
                    <p>To be a sanctuary where the art of nourishment meets the poetry of connection.

                        We believe in the simple magic of a shared meal—where earth's honest ingredients are transformed
                        into vibrant cuisine that tells a story of season and place.

                        Our space is designed as a haven of warmth and golden light, a modern hearth for making
                        memories. Here, every guest is welcomed as family, and every moment is slowed to the pace of
                        genuine conversation.

                        We don't just serve dinner; we craft an experience for the senses, hoping you leave not just
                        satisfied, but truly restored.</p>
                </div>
                <div class="dashboard-item-image"> <img src="../Images/Vision-user-dash.webp" alt="Dish Image"></div>
            </div>
        </div>

        <center>
            <div class="booking-section" id="booking">
                <center>
                    <h2>Book a Table</h2>
                </center>
                <form method="POST">
                    <div>
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div>
                        <label for="time">Time-start:</label>
                        <input type="time" id="time" name="timeStart" required>
                    </div>
                    <div>
                        <label for="time-end">Time-end:</label>
                        <input type="time" id="time-end" name="timeEnd" required>
                    </div>
                    <div>
                        <label for="tables">Number of tables:</label>
                        <input type="number" id="tables" name="tables" required>
                    </div>

                    <input class="submit-button" type="submit" value="Book Now">
                </form>
        </center>

        <div class="gallery">
            <center>
                <h2>Gallery</h2>
            </center>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="../Images/Gallery-interior-1.webp" alt="Gallery Image 1">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-food-1.webp" alt="Gallery Image 1">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-interior-2.webp" alt="Gallery Image 1">
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="../Images/Gallery-food-2.webp" alt="Gallery Image 2">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-interior-3.webp" alt="Gallery Image 1">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-food-3.webp" alt="Gallery Image 1">
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="../Images/Gallery-interior-4.webp" alt="Gallery Image 1">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-food-4.webp" alt="Gallery Image 1">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-interior-5.webp" alt="Gallery Image 1">
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="../Images/Gallery-food-5.webp" alt="Gallery Image 2">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-interior-6.webp" alt="Gallery Image 1">
                    </div>
                    <div class="gallery-image">
                        <img src="../Images/Gallery-food-6.webp" alt="Gallery Image 1">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include '../Includes/footers/Footer_user.php'; ?>
    </footer>
</body>

</html>