<?php
session_start();
require_once '../Config/db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: Login_Page.php");
    exit();
}

// Get all orders with customer information
$orders_query = "SELECT o.*, u.username, u.email, u.phone 
                 FROM `order` o 
                 JOIN users u ON o.user_id = u.user_id 
                 ORDER BY o.order_id DESC";
$orders_result = $conn->query($orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin Panel</title>
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
        
        .orders-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-x: auto;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .orders-table th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            font-weight: 600;
        }
        
        .orders-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .orders-table tr:last-child td {
            border-bottom: none;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-completed {
            background: #e8f5e9;
            color: #28a745;
        }
        
        .status-pending {
            background: #fff8e1;
            color: #ffc107;
        }
        
        .status-processing {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .no-orders {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-clipboard-list"></i> Order Management</h1>
            <a href="../Admin/admin_dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="orders-table-container">
            <?php if ($orders_result->num_rows > 0): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders_result->fetch_assoc()): ?>
                            <?php
                            $statusClass = '';
                            switch($order['status']) {
                                case 'completed':
                                    $statusClass = 'status-completed';
                                    break;
                                case 'pending':
                                    $statusClass = 'status-pending';
                                    break;
                                case 'preparing':
                                    $statusClass = 'status-processing';
                                    break;
                                default:
                                    $statusClass = 'status-pending';
                            }
                            ?>
                            <tr>
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td>
                                    <div><?php echo htmlspecialchars($order['username']); ?></div>
                                    <div style="font-size: 12px; color: #6c757d;"><?php echo $order['email']; ?></div>
                                </td>
                                <td><span class='status <?php echo $statusClass; ?>'><?php echo ucfirst($order['status']); ?></span></td>
                                <td><?php echo ucfirst($order['order_type']); ?></td>
                                <td>৳<?php echo number_format($order['total'], 2); ?></td>
                                <td>৳<?php echo number_format($order['paid'], 2); ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['created_at'] ?? 'now')); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-orders">
                    <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3>No Orders Found</h3>
                    <p>There are no orders in the system yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>