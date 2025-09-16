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
    if (isset($_POST['add_inventory_item'])) {
        // Add new inventory item
            $name = $_POST['name'];
            $available = $_POST['available'];
            $unit = $_POST['unit'];
        
        $query = "INSERT INTO inventory (name, available, unit) 
                 VALUES ('$name', '$available', '$unit')";
        
        if ($conn->query($query)) {
            $success_msg = "Inventory item added successfully!";
        } else {
            $error_msg = "Error adding inventory item: " . $conn->error;
        }
    } elseif (isset($_POST['update_inventory_item'])) {
        // Update inventory item
        $inventory_id = $_POST['inventory_id'];
        $name = $_POST['name'];
        $available = $_POST['available'];
        $unit = $_POST['unit'];
        
        $query = "UPDATE inventory SET 
                 name='$name', available='$available', unit='$unit'
                 WHERE inventory_id='$inventory_id'";
        
        if ($conn->query($query)) {
            $success_msg = "Inventory item updated successfully!";
        } else {
            $error_msg = "Error updating inventory item: " . $conn->error;
        }
    } elseif (isset($_POST['delete_inventory_item'])) {
        // Delete inventory item
        $inventory_id = $_POST['inventory_id'];
        
        $query = "DELETE FROM inventory WHERE inventory_id='$inventory_id'";
        
        if ($conn->query($query)) {
            $success_msg = "Inventory item deleted successfully!";
        } else {
            $error_msg = "Error deleting inventory item: " . $conn->error;
        }
    }
}

// Get all inventory items
$inventory_query = "SELECT * FROM inventory ORDER BY name";
$inventory_result = $conn->query($inventory_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../CSS/view_inventory.css">
</head>
<body>
    <div class="inventory-container">
        <div class="inventory-header">
            <h1>Inventory Management</h1>
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

        <!-- Add New Inventory Item Form -->
        <div class="add-inventory-card">
            <h2>Add New Inventory Item</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="available">Quantity Available</label>
                    <input type="number" id="available" name="available" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="unit">Unit</label>
                    <select id="unit" name="unit" required>
                        <option value="piece">Piece</option>
                        <option value="kg">Kilogram (kg)</option>
                        <option value="g">Gram (g)</option>
                        <option value="lb">Pound (lb)</option>
                        <option value="oz">Ounce (oz)</option>
                        <option value="liter">Liter</option>
                        <option value="ml">Milliliter (ml)</option>
                        <option value="pack">Pack</option>
                        <option value="box">Box</option>
                    </select>
                </div>
                
                <button type="submit" name="add_inventory_item" class="btn">Add Inventory Item</button>
            </form>
        </div>

        <!-- Current Inventory Items -->
        <div class="add-inventory-card">
            <h2>Current Inventory Items</h2>
            
            <?php if ($inventory_result->num_rows > 0): ?>
                <div class="inventory-items-grid">
                    <?php while ($item = $inventory_result->fetch_assoc()): ?>
                        <div class="inventory-item-card">
                            <div class="inventory-item-header">
                                <div class="inventory-item-name"><?php echo $item['name']; ?></div>
                                <div class="inventory-item-stock"><?php echo $item['available']; ?> 
                                <span><?php echo ucfirst($item['unit']); ?></span></div>
                            </div>
                            
                            <?php if ($item['available'] < 10): ?>
                                <div class="stock-warning">Low stock! Please restock.</div>
                            <?php endif; ?>
                            
                            <div class="inventory-item-actions">
                                <!-- Edit Form -->
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_id']; ?>">
                                    <input type="hidden" name="name" value="<?php echo $item['name']; ?>">
                                    <input type="hidden" name="available" value="<?php echo $item['available']; ?>">
                                    <input type="hidden" name="unit" value="<?php echo $item['unit']; ?>">
                                    <button type="submit" name="update_inventory_item" class="action-btn edit-btn">Edit</button>
                                </form>
                                
                                <!-- Delete Form -->
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_id']; ?>">
                                    <button type="submit" name="delete_inventory_item" class="action-btn delete-btn" 
                                            onclick="return confirm('Are you sure you want to delete this inventory item?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No inventory items found. Add your first inventory item above.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Pre-fill form when editing
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                if (form.querySelector('button[name="update_inventory_item"]')) {
                    form.addEventListener('submit', function(e) {
                        if (e.submitter.name === 'update_inventory_item') {
                            const itemId = form.querySelector('input[name="inventory_id"]').value;
                            const name = form.querySelector('input[name="name"]').value;
                            const available = form.querySelector('input[name="available"]').value;
                            const unit = form.querySelector('input[name="unit"]').value;
                            
                            // Fill the add form with these values for editing
                            document.getElementById('name').value = name;
                            document.getElementById('available').value = available;
                            document.getElementById('unit').value = unit;
                            
                            
                            e.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>