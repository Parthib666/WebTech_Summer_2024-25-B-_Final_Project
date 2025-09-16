<?php
 include "../Config/db_connection.php";
        session_start();
$OTP = rand(100000, 999999);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_input = trim($_POST['OTP']);
    if ($otp_input == $OTP) {
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        $update_sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
        $result = $conn->query($update_sql);
        if ($result) {
            echo "Password updated successfully.";
            session_unset();
            session_destroy();
            echo "<script>
                    window.location.href = 'Login_Page.php';
                  </script>";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Invalid OTP. Please try again.";

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="forget-container">
        <h2>Forget Password</h2>
        <form method="POST">
            <label for="OTP">OTP: <?php echo $OTP; ?></label>
            <input type="number" id="OTP" name="OTP" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>