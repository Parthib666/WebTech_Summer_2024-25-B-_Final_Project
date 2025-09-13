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

    $sql = "UPDATE users SET username=?, email=?, phone=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $phone, $address, $user_id);
    $stmt->execute();
    $stmt->close();
}
$sql = 'SELECT * FROM users WHERE user_id = "' . $_SESSION['user_id'] . '"';
$result = $conn->query($sql);
$user_details = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/footer_user.css">
    <script src="https://kit.fontawesome.com/31caec7e2c.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --border-color: #e0e0e0;
            --hover-color: #f0f5ff;
            --success-color: #4caf50;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fafafa;
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        main {
            min-height: calc(100vh - 160px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .profile-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
            transition: var(--transition);
        }

        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .profile-icon {
            font-size: 4.5rem;
            color: var(--primary-color);
            background: var(--secondary-color);
            border-radius: 50%;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        #user-info {
            text-align: center;
        }

        #user-info h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
        }

        #user-info p {
            margin: 0;
            color: #666;
            font-size: 1rem;
        }

        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .form-actions {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #3651d3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .edit-toggle {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .readonly-field {
            background-color: #f5f5f5;
            color: #666;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #eee;
        }
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

            <?php if (isset($success) && $success): ?>
                <div style="color: var(--success-color); margin-bottom: 1rem; text-align: center; font-weight: 500;">Profile
                    updated successfully!</div>
            <?php elseif (isset($error)): ?>
                <div style="color: #e74c3c; margin-bottom: 1rem; text-align: center; font-weight: 500;">Error:
                    <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form class="profile-form" id="profileForm" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="readonly-field" id="usernameDisplay">
                        <?php echo htmlspecialchars($user_details['username']); ?></div>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($user_details['username']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="readonly-field" id="emailDisplay">
                        <?php echo htmlspecialchars($user_details['email']); ?></div>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($user_details['email']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <!-- <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="readonly-field" id="firstNameDisplay"><?php echo htmlspecialchars($user_details['username']); ?></div>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_details['username']); ?>" class="edit-field" style="display: none;">
                </div> -->

                <!-- <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="readonly-field" id="lastNameDisplay"><?php echo htmlspecialchars($user_details['last_name']); ?></div>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_details['last_name']); ?>" class="edit-field" style="display: none;">
                </div> -->

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <div class="readonly-field" id="phoneDisplay">
                        <?php echo htmlspecialchars($user_details['phone']); ?></div>
                    <input type="tel" id="phone" name="phone"
                        value="<?php echo htmlspecialchars($user_details['phone']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <div class="readonly-field" id="addressDisplay">
                        <?php echo htmlspecialchars($user_details['address']); ?></div>
                    <input type="text" id="address" name="address"
                        value="<?php echo htmlspecialchars($user_details['address']); ?>" class="edit-field"
                        style="display: none;">
                </div>

                <div class="form-group" style:>
                    <div class="readonly-field"><button onclick="window.location.href='change_password.php'"
                            style="color: #e74c3c; width: 100%; height: 100%;">
                            Change Password
                        </button>
                    </div>
                </div>

                <!-- <div class="form-group">
                    <label for="state">State</label>
                    <div class="readonly-field" id="stateDisplay"><?php echo htmlspecialchars($user_details['state']); ?></div>
                    <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user_details['state']); ?>" class="edit-field" style="display: none;">
                </div>
                
                <div class="form-group">
                    <label for="country">Country</label>
                    <div class="readonly-field" id="countryDisplay"><?php echo htmlspecialchars($user_details['country']); ?></div>
                    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user_details['country']); ?>" class="edit-field" style="display: none;">
                </div> -->

                <div class="form-actions" id="formActions" style="display: none;">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="saveChanges()">Save Changes</button>
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
            const displayFields = document.querySelectorAll('.readonly-field');

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

            profileForm.addEventListener('submit', function (e) {
                // Let the form submit normally so PHP can handle DB update
                // Only prevent default if you want AJAX
                // e.preventDefault();
                // cancelEditing();
            });
        });
    </script>
</body>

</html>