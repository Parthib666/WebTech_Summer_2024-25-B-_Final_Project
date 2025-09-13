<?php
session_start();
require_once '../Config/db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: Login_Page.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_menu_item'])) {
        // Add new menu item
        $name = $conn->real_escape_string($_POST['name']);
        $price = $conn->real_escape_string($_POST['price']);
        $description = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
        $image = 'default.jpg'; // Default image
        
        $query = "INSERT INTO menu (name, price, image, description, category) 
                 VALUES ('$name', '$price', '$image', '$description', '$category')";
        
        if ($conn->query($query)) {
            $success_msg = "Menu item added successfully!";
        } else {
            $error_msg = "Error adding menu item: " . $conn->error;
        }
    } elseif (isset($_POST['update_menu_item'])) {
        // Update menu item
        $menu_item_id = $conn->real_escape_string($_POST['menu_item_id']);
        $name = $conn->real_escape_string($_POST['name']);
        $price = $conn->real_escape_string($_POST['price']);
        $description = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
        
        $query = "UPDATE menu SET 
                 name='$name', price='$price', description='$description', category='$category'
                 WHERE menu_item_id='$menu_item_id'";
        
        if ($conn->query($query)) {
            $success_msg = "Menu item updated successfully!";
        } else {
            $error_msg = "Error updating menu item: " . $conn->error;
        }
    } elseif (isset($_POST['delete_menu_item'])) {
        // Delete menu item
        $menu_item_id = $conn->real_escape_string($_POST['menu_item_id']);
        
        $query = "DELETE FROM menu WHERE menu_item_id='$menu_item_id'";
        
        if ($conn->query($query)) {
            $success_msg = "Menu item deleted successfully!";
        } else {
            $error_msg = "Error deleting menu item: " . $conn->error;
        }
    }
}

// Get all menu items
$menu_query = "SELECT * FROM menu ORDER BY category, name";
$menu_result = $conn->query($menu_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Admin Panel</title>
    <link rel="stylesheet" href="../CSS/menu_customer.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .admin-header h1 {
            color: #2c3e50;
        }
        
        .back-btn {
            background: #0d111d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn:hover {
            background: #2c3e50;
        }
        
        .admin-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .admin-card h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        .btn {
            background: #0d111d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #2c3e50;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .menu-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .menu-item-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            position: relative;
        }
        
        .menu-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .menu-item-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .menu-item-price {
            font-size: 16px;
            font-weight: 700;
            color: #e74c3c;
        }
        
        .menu-item-category {
            display: inline-block;
            padding: 4px 8px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .menu-item-description {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .menu-item-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .edit-btn {
            background: #3498db;
            color: white;
        }
        
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header>
        <?php include '../Includes/navbars/Navbar_user.php'; ?>
    </header>

    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1>Menu Management</h1>
                <a href="../Admin/admin_dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if (isset($success_msg)): ?>
                <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <?php if (isset($error_msg)): ?>
                <div class="alert alert-error"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <!-- Add New Menu Item Form -->
            <div class="admin-card">
                <h2>Add New Menu Item</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Item Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (BDT)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="starter">Starter</option>
                            <option value="main">Main Course</option>
                            <option value="dessert">Dessert</option>
                            <option value="beverages">Beverages</option>
                            <option value="quick_bites">Quick Bites</option>
                            <option value="special">Special</option>
                            <option value="sides">Sides</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="add_menu_item" class="btn">Add Menu Item</button>
                </form>
            </div>

            <!-- Current Menu Items -->
            <div class="admin-card">
                <h2>Current Menu Items</h2>
                
                <?php if ($menu_result->num_rows > 0): ?>
                    <div class="menu-items-grid">
                        <?php while ($item = $menu_result->fetch_assoc()): ?>
                            <div class="menu-item-card">
                                <div class="menu-item-header">
                                    <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="menu-item-price">৳<?php echo number_format($item['price'], 2); ?></div>
                                </div>
                                
                                <div class="menu-item-category"><?php echo ucfirst(str_replace('_', ' ', $item['category'])); ?></div>
                                
                                <?php if (!empty($item['description'])): ?>
                                    <div class="menu-item-description"><?php echo htmlspecialchars($item['description']); ?></div>
                                <?php endif; ?>
                                
                                <div class="menu-item-actions">
                                    <!-- Edit Form -->
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="menu_item_id" value="<?php echo $item['menu_item_id']; ?>">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                        <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                                        <input type="hidden" name="description" value="<?php echo htmlspecialchars($item['description']); ?>">
                                        <input type="hidden" name="category" value="<?php echo $item['category']; ?>">
                                        <button type="submit" name="update_menu_item" class="action-btn edit-btn">Edit</button>
                                    </form>
                                    
                                    <!-- Delete Form -->
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="menu_item_id" value="<?php echo $item['menu_item_id']; ?>">
                                        <button type="submit" name="delete_menu_item" class="action-btn delete-btn" 
                                                onclick="return confirm('Are you sure you want to delete this menu item?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No menu items found. Add your first menu item above.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <?php include '../Includes/footers/Footer_user.php'; ?>
    </footer>

    <script>
        // Pre-fill form when editing
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                if (form.querySelector('button[name="update_menu_item"]')) {
                    form.addEventListener('submit', function(e) {
                        if (e.submitter.name === 'update_menu_item') {
                            const itemId = form.querySelector('input[name="menu_item_id"]').value;
                            const name = form.querySelector('input[name="name"]').value;
                            const price = form.querySelector('input[name="price"]').value;
                            const description = form.querySelector('input[name="description"]').value;
                            const category = form.querySelector('input[name="category"]').value;
                            
                            // Fill the add form with these values for editing
                            document.getElementById('name').value = name;
                            document.getElementById('price').value = price;
                            document.getElementById('description').value = description;
                            document.getElementById('category').value = category;
                            
                            // Scroll to the add form
                            document.querySelector('.admin-card').scrollIntoView({
                                behavior: 'smooth'
                            });
                            
                            e.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>