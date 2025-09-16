<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../Commons/Login_Page.php');
    exit;
}

include '../Config/db_connection.php';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $sql = 'UPDATE users SET username="' . $username . '", email="' . $email . '", phone="' . $phone . '", address="' . $address . '" WHERE user_id="' . $user_id . '"';
    $push=$conn->query($sql);
}
$sql = 'SELECT * FROM users WHERE user_id = "' . $_SESSION['user_id'] . '"';
$result = $conn->query($sql);
$user_details = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <link rel="stylesheet" href="../CSS/manage_profile.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
    <style>
        
    </style>
</head>

<body>
    <header>
        <?php include '../Includes/navbars/Navbar_user.php'; ?>
    </header>
    <main>
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-icon">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div id="user-info">
                    <h4><?php echo htmlspecialchars($user_details['username']); ?></h4>
                    <p><?php echo htmlspecialchars($user_details['email']); ?></p>
                </div>
            </div>

            <div class="edit-toggle">
                <button class="toggle-btn" id="editToggle">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
            </div>
            <form class="profile-form" id="profileForm" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="display-field" id="usernameDisplay">
                        <?php echo htmlspecialchars($user_details['username']); ?></div>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($user_details['username']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="display-field" id="emailDisplay">
                        <?php echo htmlspecialchars($user_details['email']); ?></div>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($user_details['email']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <div class="display-field" id="phoneDisplay">
                        <?php echo htmlspecialchars($user_details['phone']); ?></div>
                    <input type="tel" id="phone" name="phone"
                        value="<?php echo htmlspecialchars($user_details['phone']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <div class="display-field" id="addressDisplay">
                        <?php echo htmlspecialchars($user_details['address']); ?></div>
                    <input type="text" id="address" name="address"
                        value="<?php echo htmlspecialchars($user_details['address']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group" style:>
                    <div class="display-field"><button onclick="window.location.href='change_password.php'"
                            style="color: #e74c3c; width: 100%; height: 100%;">
                            Change Password
                        </button>
                    </div>
                </div>

                <div class="form-actions" id="formActions" style="display: none;">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <?php include '../Includes/footers/Footer_user.php'; ?>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editToggle = document.getElementById('editToggle');
            const formActions = document.getElementById('formActions');
            const cancelBtn = document.getElementById('cancelBtn');
            const profileForm = document.getElementById('profileForm');
            const editFields = document.querySelectorAll('.edit-field');
            const displayFields = document.querySelectorAll('.display-field');

            let isEditing = false;

            editToggle.addEventListener('click', function () {
                isEditing = !isEditing;

                if (isEditing) {
                    // Switch to edit mode
                    editToggle.innerHTML = '<i class="fas fa-times"></i> Cancel Editing';
                    formActions.style.display = 'flex';

                    // Hide display fields and show input fields
                    displayFields.forEach(field => field.style.display = 'none');
                    editFields.forEach(field => field.style.display = 'block');
                } else {
                    // Switch back to view mode
                    cancelEditing();
                }
            });

            cancelBtn.addEventListener('click', cancelEditing);

            function cancelEditing() {
                isEditing = false;
                editToggle.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
                formActions.style.display = 'none';

                // Hide input fields and show display fields
                editFields.forEach(field => field.style.display = 'none');
                displayFields.forEach(field => field.style.display = 'block');

                // Reset form values to original
                profileForm.reset();
            }
        });
    </script>
</body>

</html>