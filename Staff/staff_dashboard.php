<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../Customer/Login_Page.php");
    exit();
}else{
include "../Config/db_connection.php";
$sqlAllOrders = "SELECT COUNT(*) FROM orders";
$result = $conn->query($sqlAllOrders);
$allOrders = $result->fetch_assoc();

$sqlActiveOrders = "SELECT COUNT(*) FROM orders WHERE status IN ('pending', 'preparing', 'ready', 'delivering')";
$result = $conn->query($sqlActiveOrders);
$activeOrders = $result->fetch_assoc();

$sqlCompletedOrders = "SELECT COUNT(*) FROM orders WHERE status IN ('completed')";
$result = $conn->query($sqlCompletedOrders);
$completedOrders = $result->fetch_assoc();

$sqlTotalRevenue = "SELECT SUM(total) AS total_revenue FROM orders WHERE status = 'completed'";
$result = $conn->query($sqlTotalRevenue);
$totalRevenue = $result->fetch_assoc();

$sqlRecentOrders = "SELECT * FROM orders ORDER BY status ";
$resultOrders = $conn->query($sqlRecentOrders);

$sqlReservations = "SELECT * FROM reservation ORDER BY status ";
$resultReservations = $conn->query($sqlReservations);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../CSS/staff_dashboard.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
    <style>
        .dashboard-container {
            display: flex;
            margin-top: 0px;
        }
        .sidebar {
            width: 10%;
            background-color: #040111ff;
            padding: 20px;
            height: 200vh;
        }
        .sidebar h2 {
            color: white;
            text-align: center;
        }
        .sidebar-list {
            list-style-type: none;
            padding: 0;
        }
        .sidebar-list li {
            padding: 10px;
            background-color: #0d111d;
            color: white;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .dashboard-content {
            height: 100vh;
            width: 90%;
            margin-top: 0px;
        }
        .dash-flex {
            width: 90%;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .dash-card {
            background-color: #f0f0f0;
            border-radius: 8px;
            padding: 20px;
            flex: 1 1 200px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0px;
            padding: 10px;
        }
        .account-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 10px;
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
        .orders-table {
            margin: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);  
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        tr{
            border-bottom: 1px solid #41414173;
        }
    </style>

</head>
<body>
        <div class="dashboard-container" >
            <div class="sidebar">
                <h2>Staff Panel</h2>
                <ul class="sidebar-list">
                    <a href="staff_dashboard.php" style="text-decoration: none; color: white;"><li>Dashboard</li></a>
                    <a href="staff_dashboard.php#orders-table" style="text-decoration: none; color: white;"><li>Manage Orders</li></a>
                    <a href="staff_dashboard.php#reservations-table" style="text-decoration: none; color: white;"><li>Manage Reservations</li></a>
                    <a href="../Admin/view_inventory.php" style="text-decoration: none; color: white;"><li>Manage Inventory</li></a>
                    <a href="../Customer/user_profile.php" style="text-decoration: none; color: white;"><li>View Profile</li></a>
                    <a href="../Customer/logout.php" style="text-decoration: none; color: white;"><li>Logout</li></a>
                </ul>
            </div>
            <div class="dashboard-content">
                <div class="dash-header">
                    <h1>Dashboard Overview</h1>
                    <div class="account-info">
                        <div class="user-avatar"><i class="fas fa-user-circle"></i></div>
                        <div>
                            <p><?php echo $_SESSION['username']; ?></p>
                        </div>
                    </div>
                </div>
                <hr>
                <center><div class="dash-flex">
                    <div class="dash-card">
                        <h3>Total Orders</h3>
                        <p><?php echo $allOrders['COUNT(*)']; ?></p>
                    </div>
                    <div class="dash-card">
                        <h3>Active Orders</h3>
                        <p><?php echo $activeOrders['COUNT(*)']; ?></p>
                    </div>
                    <div class="dash-card">
                        <h3>Completed Orders</h3>
                        <p><?php echo $completedOrders['COUNT(*)']; ?></p>
                    </div>
                    <div class="dash-card">
                        <h3>Total Revenue</h3>
                        <p><?php echo $totalRevenue['total_revenue']; ?></p>
                    </div>
                </div>
                </center>
                <center><div class="orders-table" id="orders-table">
                    <h2 style="text-align: center; margin-top: 20px;">Recent Orders</h2>
                    <table >
                            <tr>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Order Date</th>
                            </tr>
                       
                            <?php
                            if ($resultOrders->num_rows > 0) {
                                while($row = $resultOrders->fetch_assoc()) {
                                    echo "<tr style='text-align: center;border=1px solid black;' >
                                            <td>" . $row['order_id'] . "</td>
                                            <td>" . $row['user_id'] . "</td>
                                            <td>" . $row['status'] . "</td>
                                            <td>$" . $row['total'] . "</td>
                                            <td>" . $row['date'] . "</td>
                                            <td><button class='btn btn-primary' onclick=\"location.href='manage_order.php?id=" . $row['order_id'] . "'\">View</button></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No recent orders found.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        
                    </table>
                </div></center>

                <center><div class="orders-table" id="reservations-table">
                    <h2 style="text-align: center; margin-top: 20px;">Reservations</h2>
                    <table >
                            <tr>
                                <th>Reservation ID</th>
                                <th>Customer ID</th>
                                <th>Status</th>
                                <th>Tables Required</th>
                                <th>Reservation Date</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                        <
                            <?php
                            if ($resultReservations->num_rows > 0) {
                                while($row = $resultReservations->fetch_assoc()) {
                                    echo "<tr style='text-align: center;border=1px solid black;' >
                                            <td>" . $row['reservation_id'] . "</td>
                                            <td>" . $row['user_id'] . "</td>
                                            <td>" . $row['status'] . "</td>
                                            <td>" . $row['table_no'] . "</td>
                                            <td>" . $row['reservation_date'] . "</td>
                                            <td>" . $row['time_start'] . "</td>
                                            <td>" . $row['time_end'] . "</td>
                                            <td><button class='btn btn-primary' onclick=\"location.href='manage_.php?id=" . $row['reservation_id'] . "'\">View</button></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No recent reservations found.</td></tr>";
                            }
                            ?>
                        
                    </table>
                </div></center>

            </div>
        </div>
</body>
</html>