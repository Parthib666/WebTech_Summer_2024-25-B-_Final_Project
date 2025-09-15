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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333333;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
        
        .customers-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-x: auto;
        }
        
        .customers-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .customers-table th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            font-weight: 600;
        }
        
        .customers-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .customers-table tr:last-child td {
            border-bottom: none;
        }
        
        .order-count {
            display: inline-block;
            padding: 4px 8px;
            background: #e3f2fd;
            border-radius: 12px;
            font-size: 12px;
            color: #1976d2;
        }
        
        .no-customers {
            text-align: center;
            padding: 40px;
            color: #6c757d;
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
        
        .edit-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border: 1px solid #e9ecef;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn {
            background: #0d111d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 5px;
        }
        
        .btn:hover {
            background: #2c3e50;
        }
        
        .btn-edit {
            background: #3498db;
        }
        
        .btn-edit:hover {
            background: #2980b9;
        }
        
        .btn-cancel {
            background: #6c757d;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-users"></i> Customer Management</h1>
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
                                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                <td><?php echo $customer['email']; ?></td>
                                <td><?php echo $customer['phone']; ?></td>
                                <td><?php echo htmlspecialchars($customer['address']); ?></td>
                                <td>
                                    <span class="order-count">
                                        <?php echo $order_counts[$customer['user_id']] ?? 0; ?> orders
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-edit" onclick="toggleEditForm(<?php echo $customer['user_id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
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
                                                <input type="text" id="username-<?php echo $customer['user_id']; ?>" name="username" value="<?php echo htmlspecialchars($customer['username']); ?>" required>
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
                                                <input type="text" id="address-<?php echo $customer['user_id']; ?>" name="address" value="<?php echo htmlspecialchars($customer['address']); ?>" required>
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