<?php
session_start();
require_once '../Config/db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: Login_Page.php");
    exit();
}

// Handle customer updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_customer'])) {
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    
    $query = "UPDATE users SET username='$username', email='$email', phone='$phone', address='$address' 
              WHERE user_id='$user_id'";
    
    if ($conn->query($query)) {
        $success_msg = "Customer updated successfully!";
    } else {
        $error_msg = "Error updating customer: " . $conn->error;
    }
}

// Get all customers
$customers_query = "SELECT * FROM users WHERE role = 'customer' ORDER BY user_id DESC";
$customers_result = $conn->query($customers_query);

// Get order counts for each customer
$order_counts = [];
$order_count_query = "SELECT user_id, COUNT(*) as order_count FROM `orders` GROUP BY user_id";
$order_count_result = $conn->query($order_count_query);
while ($row = $order_count_result->fetch_assoc()) {
    $order_counts[$row['user_id']] = $row['order_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../CSS/view_customer.css">
</head>
<body>
    <div class="customer-container">
        <div class="customer-header">
            <h1> Customer Management</h1>
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

        <div class="customers-table-container">
            <?php if ($customers_result->num_rows > 0): ?>
                <table class="customers-table">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Orders</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($customer = $customers_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $customer['user_id']; ?></td>
                                <td><?php echo $customer['username']; ?></td>
                                <td><?php echo $customer['email']; ?></td>
                                <td><?php echo $customer['phone']; ?></td>
                                <td><?php echo $customer['address']; ?></td>
                                <td>
                                    <span class="order-count">
                                        <?php echo $order_counts[$customer['user_id']] ?? 0; ?> orders
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-edit" onclick="toggleEditForm(<?php echo $customer['user_id']; ?>)">
                                     Edit
                                    </button>
                                </td>
                            </tr>
                            <!-- Edit Form (initially hidden) -->
                            <tr id="edit-form-<?php echo $customer['user_id']; ?>" style="display: none;">
                                <td colspan="7">
                                    <div class="edit-form">
                                        <h4>Edit Customer #<?php echo $customer['user_id']; ?></h4>
                                        <form method="POST" action="">
                                            <input type="hidden" name="user_id" value="<?php echo $customer['user_id']; ?>">
                                            
                                            <div class="form-group">
                                                <label for="username-<?php echo $customer['user_id']; ?>">Name</label>
                                                <input type="text" id="username-<?php echo $customer['user_id']; ?>" name="username" value="<?php echo $customer['username']; ?>" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="email-<?php echo $customer['user_id']; ?>">Email</label>
                                                <input type="email" id="email-<?php echo $customer['user_id']; ?>" name="email" value="<?php echo $customer['email']; ?>" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="phone-<?php echo $customer['user_id']; ?>">Phone</label>
                                                <input type="text" id="phone-<?php echo $customer['user_id']; ?>" name="phone" value="<?php echo $customer['phone']; ?>" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="address-<?php echo $customer['user_id']; ?>">Address</label>
                                                <input type="text" id="address-<?php echo $customer['user_id']; ?>" name="address" value="<?php echo $customer['address']; ?>" required>
                                            </div>
                                            
                                            <button type="submit" name="update_customer" class="btn">Update Customer</button>
                                            <button type="button" class="btn btn-cancel" onclick="toggleEditForm(<?php echo $customer['user_id']; ?>)">Cancel</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-customers">
                    <i class="fas fa-users" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3>No Customers Found</h3>
                    <p>There are no customer accounts in the system yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleEditForm(userId) {
            const form = document.getElementById('edit-form-' + userId);
            if (form.style.display === 'none') {
                form.style.display = 'table-row';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>