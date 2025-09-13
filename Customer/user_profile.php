<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../Commons/Login_Page.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --border-color: #e0e0e0;
            --hover-color: #f0f5ff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fafafa;
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }
        
        main {
            min-height: 30vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .profile-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            width: 100%;
            transition: var(--transition);
        }
        
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .profile-icon {
            font-size: 4.5rem;
            color: var(--primary-color);
            background: var(--secondary-color);
            border-radius: 50%;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }
        
        #user-info {
            text-align: center;
        }
        
        #user-info h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        #user-info p {
            margin: 0;
            color: #666;
            font-size: 1rem;
        }
        
        .profile-options {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .profile-options button {
            padding: 1rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            cursor: pointer;
            text-align: left;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition);
        }
        
        .profile-options button:hover {
            background-color: var(--hover-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .profile-options button i {
            margin-left: 0.5rem;
            color: #888;
            transition: var(--transition);
        }
        
        .profile-options button:hover i {
            color: var(--primary-color);
            transform: translateX(4px);
        }
        
        @media (max-width: 576px) {
            .profile-container {
                padding: 1.5rem;
            }
            
            .profile-icon {
                font-size: 3.5rem;
                padding: 1.25rem;
            }
            
            #user-info h4 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <?php include '../Includes/navbars/Navbar_user.php'; ?>
    </header>
    <main>
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-icon">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div id="user-info">
                    <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
                    <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>
            </div>
            
            <div class="profile-options">
                <button onclick="window.location.href='manage_profile.php'">
                    Manage Profile <i class="fas fa-chevron-right"></i>
                </button>
                <button onclick="window.location.href='track_order.php'">
                    Track Order <i class="fas fa-chevron-right"></i>
                </button>
                <button onclick="window.location.href='order_history.php'">
                    Order History <i class="fas fa-chevron-right"></i>
                </button>
                <button onclick="window.location.href='Logout.php'" style="color: #e74c3c;">
                    Logout <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </main>
    <footer>
        <?php include '../Includes/footers/Footer_user.php'; ?>
    </footer>
</body>
</html>