<?php
session_start();
require_once '../Config/db_connection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../Commons/Login_Page.php");
    exit();
}

// Total orders count
$totalOrdersQuery = "SELECT COUNT(*) as order_count FROM `orders`";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = $totalOrdersResult->fetch_assoc()['order_count'];

// Completed orders count
$completedOrdersQuery = "SELECT COUNT(*) as completed_count FROM `orders` WHERE status = 'completed'";
$completedOrdersResult = $conn->query($completedOrdersQuery);
$completedOrders = $completedOrdersResult->fetch_assoc()['completed_count'];

// Pending orders count
$pendingOrdersQuery = "SELECT COUNT(*) as pending_count FROM `orders` WHERE status = 'pending'";
$pendingOrdersResult = $conn->query($pendingOrdersQuery);
$pendingOrders = $pendingOrdersResult->fetch_assoc()['pending_count'];

// Total revenue from completed orders
$revenueQuery = "SELECT SUM(total) as total_revenue FROM `orders` WHERE status = 'completed'";
$revenueResult = $conn->query($revenueQuery);
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0;

// Fetch recent orders (last 5 orders) - ordered by order_id descending
$recentOrdersQuery = "SELECT o.order_id, u.username as customer, 
                     o.total as amount, o.status, o.order_type,
                     o.subTotal, o.discount, o.service_charge, o.delivery_charge,
                     o.paid, o.user_id
                     FROM `orders` o
                     JOIN users u ON o.user_id = u.user_id
                     ORDER BY o.order_id DESC 
                     LIMIT 5";
$recentOrdersResult = $conn->query($recentOrdersQuery);

// Calculate customer satisfaction rate based on completed vs total orders
if ($totalOrders > 0) {
    $satisfactionRate = round(($completedOrders / $totalOrders) * 100);
} else {
    $satisfactionRate = 0;
}

// Get total customers count
$customersQuery = "SELECT COUNT(*) as customer_count FROM users WHERE role = 'customer'";
$customersResult = $conn->query($customersQuery);
$totalCustomers = $customersResult->fetch_assoc()['customer_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management Dashboard</title>
    <!-- Font Awesome CDN -->
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
            line-height: 1.6;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #0d111d;
            color: white;
            padding: 20px 0;
        }
        
        .brand {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .brand h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .sidebar-menu li:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu li.active {
            background: #3498db;
            border-left: 4px solid white;
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        /* Icons */
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .header h2 {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eeeeee;
        }
        
        .card-header h3 {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }
        
        .card-header .icon {
            color: #3498db;
            font-size: 18px;
        }
        
        .stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0d111d;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .stat-change {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #28a745;
        }
        
        /* Orders Table */
        .orders-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-header h3 {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
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
        
        .status-canceled {
            background: #ffebee;
            color: #dc3545;
        }
        
        .btn {
            background: #0d111d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

          .btn {
            background: #0d111d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            gap: 8px;
            margin-top: 15px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #2c3e50;
        }
    
        
        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 10px;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                gap: 10px;
            }
            
            .sidebar-menu li {
                padding: 10px 15px;
                white-space: nowrap;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="brand">
                <h1>Admin Panel</h1>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="#"><span class="icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard</a></li>
                <li><a href="../Customer/menu_customer.php"><span class="icon"><i class="fas fa-utensils"></i></span> Menu</a></li>
                <li><a href="#"><span class="icon"><i class="fas fa-clipboard-list"></i></span> Orders</a></li>
                <li><a href="#"><span class="icon"><i class="fas fa-users"></i></span> Customers</a></li>
                <li><a href="#"><span class="icon"><i class="fas fa-chart-line"></i></span> Reports</a></li>
                <li><a href="#"><span class="icon"><i class="fas fa-cog"></i></span> Settings</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Dashboard Overview</h2>
                <div class="user-info">
                    <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 2); ?></div>
                    <span><?php echo $_SESSION['username']; ?> (Admin)</span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>Total Orders</h3>
                        <span class="icon"><i class="fas fa-box"></i></span>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $totalOrders; ?></div>
                        <!-- <div class="stat-change">
                            <span>📊</span>
                            <span>All orders</span>
                        </div> -->
                    </div>
                    <div class="stat-label">Total orders placed</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Completed Orders</h3>
                        <span class="icon"><i class="fas fa-check-circle"></i></span>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $completedOrders; ?></div>
                        <!-- <div class="stat-change">
                            <span>📊</span>
                            <span>Successfully delivered</span>
                        </div> -->
                    </div>
                    <div class="stat-label">Completed orders</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Pending Orders</h3>
                        <span class="icon"><i class="fas fa-hourglass-half"></i></span>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $pendingOrders; ?></div>
                        <!-- <div class="stat-change">
                            <span>📊</span>
                            <span>Awaiting processing</span>
                        </div> -->
                    </div>
                    <div class="stat-label">Orders in progress</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Total Revenue</h3>
                        <span class="icon"><i class="fas fa-money-bill-wave"></i></span>
                    </div>
                    <div class="stat">
                        <div class="stat-value">৳<?php echo number_format($totalRevenue, 2); ?></div>
                        <!-- <div class="stat-change">
                            <span>📊</span>
                            <span>From completed orders</span>
                        </div> -->
                    </div>
                    <div class="stat-label">Total revenue generated</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Customer Satisfaction</h3>
                        <span class="icon"><i class="fas fa-smile"></i></span>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $satisfactionRate; ?>%</div>
                        <!-- <div class="stat-change">
                            <span>⭐</span>
                            <span>Based on order completion</span>
                        </div> -->
                    </div>
                    <div class="stat-label">Satisfaction rate</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Total Customers</h3>
                        <span class="icon"><i class="fas fa-users"></i></span>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $totalCustomers; ?></div>
                        <!-- <div class="stat-change">
                            <span>📊</span>
                            <span>Registered customers</span>
                        </div> -->
                    </div>
                    <div class="stat-label">Total customer accounts</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="orders-table-container">
                <div class="table-header">
                    <h3>Recent Orders</h3>
                    <button class="btn"><span>+</span> New Order</button>
                </div>
                
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($recentOrdersResult->num_rows > 0) {
                            while($order = $recentOrdersResult->fetch_assoc()) {
                                $statusClass = '';
                                switch($order['status']) {
                                    case 'completed':
                                        $statusClass = 'status-completed';
                                        break;
                                    case 'pending':
                                    case 'preparing':
                                        $statusClass = 'status-pending';
                                        break;
                                    case 'canceled':
                                        $statusClass = 'status-canceled';
                                        break;
                                    default:
                                        $statusClass = 'status-pending';
                                }
                                
                                echo "<tr>
                                    <td>#ORD-" . $order['order_id'] . "</td>
                                    <td>" . htmlspecialchars($order['customer']) . " (ID: " . $order['user_id'] . ")</td>
                                    <td>" . number_format($order['amount'], 2) . " BDT</td>
                                    <td><span class='status $statusClass'>" . ucfirst($order['status']) . "</span></td>
                                    <td>" . ucfirst($order['order_type']) . "</td>
                                    <td>" . number_format($order['paid'], 2) . " BDT</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>No recent orders found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Quick Actions -->
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>Menu Management</h3>
                        <span class="icon"><i class="fas fa-utensils"></i></span>
                    </div>
                    <p>Update your menu items, prices, and categories</p>
                    <!-- <a href="Admin/manage_menu.php"><button class="btn" style="margin-top: 15px;"><span class="icon"><i class="fas fa-pen"></i></span> Manage Menu</button></a> -->
                      <a href="../Admin/manage_menu.php" class="btn">
                        <i class="fas fa-pen"></i>Manage Menu
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Customer Management</h3>
                        <span class="icon"><i class="fas fa-users"></i></span>
                    </div>
                    <p>View and manage your customer information</p>
                    <a href="../Admin/view_customer.php" class="btn">
                        <i class="fas fa-eye"></i>View Customers
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Inventory Management</h3>
                        <span class="icon"><i class="fas fa-clipboard-list"></i></span>
                    </div>
                    <p>View and manage your inventory information</p>
                    <a href="../Admin/view_inventory.php" class="btn">
                        <i class="fas fa-eye"></i>View Inventory
                    </a>
                </div>
        </div>
    </div>

</body>
</html>