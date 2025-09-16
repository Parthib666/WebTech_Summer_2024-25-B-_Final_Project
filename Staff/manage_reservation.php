<?php
include '../Config/db_connection.php';
$reservationID = $_GET['id'];

$sqlReservations = 'SELECT * FROM reservation WHERE reservation_id = "' . $reservationID . '"';
$resultReservations = $conn->query($sqlReservations);
$reservation = $resultReservations->fetch_assoc();

$sqlUser = 'SELECT * FROM users WHERE user_id = "' . $reservation['user_id'] . '"';
$resultUser = $conn->query($sqlUser);
$userDetails = $resultUser->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newStatus = $_POST['status'];
    $updateSql = 'UPDATE reservation SET status = "' . $newStatus . '" WHERE reservation_id = "' . $reservationID . '"';
    $resultUpdate = $conn->query($updateSql);
    if ($resultUpdate) {
        echo "<script>alert('Reservation status updated successfully.'); window.location.href='staff_dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating reservation status.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        .details-container {
            display: flex;
            margin-top: 0px;
        }

        .sidebar {
            width: 10%;
            background-color: #040111ff;
            padding: 20px;
            height: 100vh;
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

        .order-details {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100vh;
            width: 90%;
            margin-top: 0px;
            padding: 20px;
            background-color: #77a2dab9;
        }

        .order-form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .order-form {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            gap: 15px;
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .detail-fields {
            display: grid;
            grid-template-columns: 1fr 2fr;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0, 0, 0, 1);
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        .button {
            margin-top: 10px;
            padding: 10px;
            background-color: #040111ff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 80%;
        }
    </style>
</head>

<body>
    <div class="details-container">
        <div class="sidebar">
            <h2>Staff Panel</h2>
            <ul class="sidebar-list">
                <a href="staff_dashboard.php" style="text-decoration: none; color: white;">
                    <li>Dashboard</li>
                </a>
                <a href="manage_orders.php" style="text-decoration: none; color: white;">
                    <li>Manage Orders</li>
                </a>
                <a href="update_menu.php" style="text-decoration: none; color: white;">
                    <li>Update Menu</li>
                </a>
                <a href="view_profile.php" style="text-decoration: none; color: white;">
                    <li>View Profile</li>
                </a>
                <a href="logout.php" style="text-decoration: none; color: white;">
                    <li>Logout</li>
                </a>
            </ul>
        </div>
        <div class="order-details">
            <div class="order-form-container">
                <form class="order-form" method="POST">
                    <div class="detail-fields">
                        <label for="Username">User Name:</label>
                        <span><?php echo htmlspecialchars($userDetails['username']); ?></span>
                        <br>
                    </div>
                    <div class="detail-fields">
                        <label for="Email">Email:</label>
                        <span><?php echo htmlspecialchars($userDetails['email']); ?></span>
                    </div>
                    <div class="detail-fields">
                        <label for="Phone">Phone:</label>
                        <span><?php echo htmlspecialchars($userDetails['phone']); ?></span>
                    </div>
                    <div style="background-color: #f0f0f0; flex-direction: column;" class="detail-fields">
                        <label for="Address">Address:</label>
                        <span><?php echo htmlspecialchars($userDetails['address']); ?></span>
                    </div>
                    <div class="detail-fields">
                        <label for="ReservationID">Reservation ID:</label>
                        <br>
                        <span><?php echo htmlspecialchars($reservation['reservation_id']); ?></span>
                    </div>
                    <div class="detail-fields">
                        <label for="ReservationDate">Reservation Date:</label>
                        <span><?php echo htmlspecialchars($reservation['reservation_date']); ?></span>
                    </div>
                    <div class="detail-fields">
                        <label for="Status">Status:</label>
                        <select name="status" id="status">
                            <option value="pending" <?php echo ($reservation['status'] == 'pending') ? 'selected' : ''; ?>>
                                Pending</option>
                            <option value="approved" <?php echo ($reservation['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="declined" <?php echo ($reservation['status'] == 'declined') ? 'selected' : ''; ?>>Declined</option>
                        </select>

                    </div>
                    <center><input class="button" type="submit" value="Update Status"></center>

                </form>
            </div>
        </div>
    </div>
</body>

</html>