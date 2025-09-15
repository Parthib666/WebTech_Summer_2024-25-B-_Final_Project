<?php
session_start();
require_once '../Config/db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../Commons/Login_Page.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new menu item
    if (isset($_POST['add_menu_item'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $image = 'default.jpg'; // Default image

        $target_dir = "../Images/menu/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (isset($_FILES["menu_image"]) && $_FILES["menu_image"]["error"] == 0) {
            $file_extension = pathinfo($_FILES["menu_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            $check = getimagesize($_FILES["menu_image"]["tmp_name"]);
            if ($check !== false && move_uploaded_file($_FILES["menu_image"]["tmp_name"], $target_file)) {
                $image = $new_filename;
            }
        }

        $sql = "INSERT INTO menu (name, price, image, description, category) 
                VALUES ('$name', '$price', '$image', '$description', '$category')";

        if ($conn->query($sql)) {
            $success_msg = "Menu item added successfully!";
        } else {
            $error_msg = "Error adding menu item: " . $conn->error;
        }
    }
    // Update menu item
    elseif (isset($_POST['update_menu_item'])) {
        $menu_item_id = $_POST['menu_item_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $image_query = "";

        $target_dir = "../Images/menu/";
        if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] == 0) {
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES["menu_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            $check = getimagesize($_FILES["menu_image"]["tmp_name"]);
            if ($check !== false && move_uploaded_file($_FILES["menu_image"]["tmp_name"], $target_file)) {
                $image_query = ", image='$new_filename'";

                // Delete old image if it's not the default
                $old_image_query = "SELECT image FROM menu WHERE menu_item_id='$menu_item_id'";
                $old_image_result = $conn->query($old_image_query);
                if ($old_image_result && $old_image_result->num_rows > 0) {
                    $old_image = $old_image_result->fetch_assoc()['image'];
                    if ($old_image != 'default.jpg' && file_exists($target_dir . $old_image)) {
                        unlink($target_dir . $old_image);
                    }
                }
            }
        }

        $query = "UPDATE menu SET 
                  name='$name', price='$price', description='$description', category='$category'
                  $image_query WHERE menu_item_id='$menu_item_id'";

        if ($conn->query($query)) {
            $success_msg = "Menu item updated successfully!";
        } else {
            $error_msg = "Error updating menu item: " . $conn->error;
        }
    }
    // Delete menu item
    elseif (isset($_POST['delete_menu_item'])) {
        $menu_item_id = $_POST['menu_item_id'];

        // Get image name to delete it from server
        $image_query = "SELECT image FROM menu WHERE menu_item_id='$menu_item_id'";
        $image_result = $conn->query($image_query);
        if ($image_result && $image_result->num_rows > 0) {
            $image = $image_result->fetch_assoc()['image'];
            if ($image != 'default.jpg') {
                $target_dir = "../Images/menu/";
                if (file_exists($target_dir . $image)) {
                    unlink($target_dir . $image);
                }
            }
        }

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

// Check if we're in edit mode
$edit_mode = false;
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_item_id = $_GET['edit'];
    $edit_query = "SELECT * FROM menu WHERE menu_item_id='$edit_item_id'";
    $edit_result = $conn->query($edit_query);
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_mode = true;
        $edit_item = $edit_result->fetch_assoc();
    }
}
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
    <link rel="stylesheet" href="../CSS/manage_menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

            <!-- Add/Edit Menu Item Form -->
            <div class="admin-card">
                <h2><?php echo $edit_mode ? 'Edit Menu Item' : 'Add New Menu Item'; ?></h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="menu_item_id" value="<?php echo $edit_item['menu_item_id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="name">Item Name</label>
                        <input type="text" id="name" name="name" value="<?php echo $edit_mode ? htmlspecialchars($edit_item['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (BDT)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $edit_mode ? $edit_item['price'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?php echo $edit_mode ? htmlspecialchars($edit_item['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="starter" <?php echo ($edit_mode && $edit_item['category'] == 'starter') ? 'selected' : ''; ?>>Starter</option>
                            <option value="main" <?php echo ($edit_mode && $edit_item['category'] == 'main') ? 'selected' : ''; ?>>Main Course</option>
                            <option value="dessert" <?php echo ($edit_mode && $edit_item['category'] == 'dessert') ? 'selected' : ''; ?>>Dessert</option>
                            <option value="beverages" <?php echo ($edit_mode && $edit_item['category'] == 'beverages') ? 'selected' : ''; ?>>Beverages</option>
                            <option value="quick_bites" <?php echo ($edit_mode && $edit_item['category'] == 'quick_bites') ? 'selected' : ''; ?>>Quick Bites</option>
                            <option value="special" <?php echo ($edit_mode && $edit_item['category'] == 'special') ? 'selected' : ''; ?>>Special</option>
                            <option value="sides" <?php echo ($edit_mode && $edit_item['category'] == 'sides') ? 'selected' : ''; ?>>Sides</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="menu_image">Item Image</label>
                        <input type="file" id="menu_image" name="menu_image" accept="image/*">
                        <div class="image-preview" id="imagePreview">
                            <?php if ($edit_mode && $edit_item['image'] && $edit_item['image'] != 'default.jpg'): ?>
                                <img src="../Images/menu/<?php echo $edit_item['image']; ?>" alt="Current image">
                            <?php else: ?>
                                <span>Image Preview</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($edit_mode): ?>
                        <button type="submit" name="update_menu_item" class="btn">Update Menu Item</button>
                        <a href="manage_menu.php" class="btn cancel-btn">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="add_menu_item" class="btn">Add Menu Item</button>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Current Menu Items -->
            <div class="admin-card">
                <h2>Current Menu Items</h2>
                
                <?php if ($menu_result->num_rows > 0): ?>
                    <div class="menu-items-grid">
                        <?php while ($item = $menu_result->fetch_assoc()): ?>
                            <div class="menu-item-card">
                                <?php if ($item['image'] && $item['image'] != 'default.jpg'): ?>
                                    <img src="../Images/menu/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="menu-item-image" onerror="this.style.display='none'">
                                <?php endif; ?>
                                
                                <div class="menu-item-header">
                                    <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="menu-item-price">৳<?php echo number_format($item['price'], 2); ?></div>
                                </div>
                                
                                <div class="menu-item-category"><?php echo ucfirst(str_replace('_', ' ', $item['category'])); ?></div>
                                
                                <?php if (!empty($item['description'])): ?>
                                    <div class="menu-item-description"><?php echo htmlspecialchars($item['description']); ?></div>
                                <?php endif; ?>
                                
                                <div class="menu-item-actions">
                                    <!-- Edit Button -->
                                    <a href="manage_menu.php?edit=<?php echo $item['menu_item_id']; ?>" class="action-btn edit-btn">
                                        Edit
                                    </a>
                                    
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
        // Image preview functionality
        const menuImageInput = document.getElementById('menu_image');
        const imagePreview = document.getElementById('imagePreview');
        
        menuImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    imagePreview.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = reader.result;
                    imagePreview.appendChild(img);
                });
                reader.readAsDataURL(file);
            }
        });
        
        // Scroll to form when in edit mode
        <?php if ($edit_mode): ?>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.admin-card form').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        <?php endif; ?>
    </script>
</body>
</html>