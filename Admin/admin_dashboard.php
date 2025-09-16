<?php
session_start();
require_once '../Config/db_connection.php';

// checking if admin is logged in
//if admin is not logged then session will not be set and user will be redirected to the login page.
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../Commons/Login_Page.php");
    exit();
}

// Counting Total Orders
$totalOrderSql = "SELECT COUNT(*) as order_count FROM `orders`";    //query created
$totalOrdersResult = $conn->query($totalOrderSql);                 // query executed
$totalOrders = $totalOrdersResult->fetch_assoc()['order_count'];  // fetching the total count. 

// Counting Total Completed orders
$completedOrderSql = "SELECT COUNT(*) as completed_count FROM `orders` WHERE status = 'completed'";
$completedOrdersResult = $conn->query($completedOrderSql);
$completedOrders = $completedOrdersResult->fetch_assoc()['completed_count'];

// Counting Total Pending orders
$pendingOrdersSql = "SELECT COUNT(*) as pending_count FROM `orders` WHERE status = 'pending'";
$pendingOrdersResult = $conn->query($pendingOrdersSql);
$pendingOrders = $pendingOrdersResult->fetch_assoc()['pending_count'];

// Counting total revenue from the total completed orders. 
$revenueSql = "SELECT SUM(total) as total_revenue FROM `orders` WHERE status = 'completed'";
$revenueResult = $conn->query($revenueSql);
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0;

/// Fetching recent 5 orders with customer names
// aliased the 'orders' table as 'o' and 'users' table as 'u'
$recentOrdersQuery = "SELECT o.order_id, u.username as customer, 
                     o.total as amount, o.status, o.order_type,
                     o.subTotal, o.discount, o.service_charge, o.delivery_charge,
                     o.paid, o.user_id
                     FROM `orders` o
                     JOIN users u ON o.user_id = u.user_id
                     ORDER BY o.order_id DESC 
                     LIMIT 5"; 
$recentOrdersResult = $conn->query($recentOrdersQuery);

// Counting total customers
$customersSql = "SELECT COUNT(*) as customer_count FROM users WHERE role = 'customer'";
$customersResult = $conn->query($customersSql);
$totalCustomers = $customersResult->fetch_assoc()['customer_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome link added for the icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../CSS/admin_dashboard.css">
</head>
<body>
    <!-- whole dashboard container -->
    <div class="dashboard-container">
        <!-- left Sidebar -->
        <div class="left-sidebar">
            <div class="admin-title">
                <h1>Admin Dashboard</h1>
            </div>
            <ul class="sidebar-items">
                <li class="active"><a href=""><span class="icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard</a></li>
                <li><a href="../Customer/menu_customer.php"><span class="icon"><i class="fas fa-utensils"></i></span> Menu</a></li>
                <li><a href="../Admin/manage_menu.php"><span class="icon"><i class="fas fa-pen"></i></span> Manage Menu</a></li>
                <li><a href="../Admin/view_customer.php"><span class="icon"><i class="fas fa-user"></i></span> View Customers</a></li>
                <li><a href="../Admin/view_inventory.php"><span class="icon"><i class="fas fa-cart-plus"></i></span> View Inventory</a></li>
                <li><a href="../Customer/logout.php"><span class="icon"><i class="fas fa-sign-out"></i></span> Log Out</a></li>
            </ul>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="dashboard-header">
                <h2>Dashboard Overview</h2>
                <div class="admin-info">
                    <span class="icon"><i class="fas fa-user-shield"></i></span>
                    <span><?php echo $_SESSION['username']; ?> (Admin)</span>
                </div>
            </div>

            <!-- Dashboard layouts, individual box and it's content (value,label) -->

            <div class="dashboard-layout">
                <div class="box">
                    <div class="box-header">
                        <h3>Total Orders</h3>
                    </div>
                    <div class="data">
                        <div class="data-value"><?php echo $totalOrders; ?></div>
                    </div>
       
                </div>
                
                <div class="box">
                    <div class="box-header">
                        <h3>Completed Orders</h3>
                    </div>
                    <div class="data">
                        <div class="data-value"><?php echo $completedOrders; ?></div>
                    </div>
                </div>
                
                <div class="box">
                    <div class="box-header">
                        <h3>Pending Orders</h3>
  
                    </div>
                    <div class="data">
                        <div class="data-value"><?php echo $pendingOrders; ?></div>
                    </div>
    
                </div>
                
                <div class="box">
                    <div class="box-header">
                        <h3>Total Revenue</h3>
              
                    </div>
                    <div class="data">
                        <div class="data-value"><?php echo $totalRevenue; ?> BDT</div>
                    </div>
     
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3>Total Customers</h3>
           
                    </div>
                    <div class="data">
                        <div class="data-value"><?php echo $totalCustomers; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders Layout. -->
            <div class="orders-table-container">
                <div class="table-header">
                    <h3>Recent Orders</h3>
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

            <!-- Admin can manage menu, customer and Inventory here. -->

            <div class="dashboard-layout">
                <div class="box">
                    <div class="box-header">
                        <h3>Menu Management</h3>
                    </div>
                      <a href="../Admin/manage_menu.php" class="btn">
                        Manage Menu
                    </a>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3>Customer Management</h3>

                    </div>
                    <a href="../Admin/view_customer.php" class="btn">
                        View Customers
                    </a>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3>Inventory Management</h3>
                    </div>
                    <a href="../Admin/view_inventory.php" class="btn">
                      View Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>