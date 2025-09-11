<?php
// Initialize variables

$username = $email = $phone = $address = $password = $confirmPassword = "";
$usernameErr = $emailErr = $phoneErr = $addressErr = $passwordErr = $confirmPasswordErr = $termsErr = "";
$showErrors = false;
$registrationSuccess = false;
$loginMsg = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $showErrors = true;
    
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = cleanInput($_POST["username"]);
        // Check if username only contains letters and numbers
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $usernameErr = "Only letters and numbers allowed";
        } elseif (strlen($username) < 3) {
            $usernameErr = "Username must be at least 3 characters";
        }
    }
    
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Validate phone
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = cleanInput($_POST["phone"]);
        // Check if phone is valid (10 digits)
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phoneErr = "Phone must be 10 digits";
        }
    }
    
    // Validate address
    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
    } else {
        $address = cleanInput($_POST["address"]);
        if (strlen($address) < 5) {
            $addressErr = "Address must be at least 5 characters";
        }
    }
    
    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = cleanInput($_POST["password"]);
        if (strlen($password) < 6) {
            $passwordErr = "Password must be at least 6 characters";
        }
    }
    
    // Validate confirm password
    if (empty($_POST["confirmPassword"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirmPassword = cleanInput($_POST["confirmPassword"]);
        if ($password !== $confirmPassword) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }
    
    // Validate terms and conditions
    if (!isset($_POST["termsCondition"])) {
        $termsErr = "You must agree to the terms and conditions";
    }
    
  // If no errors, registration is successful
  if (empty($usernameErr) && empty($emailErr) && empty($phoneErr) && 
    empty($addressErr) && empty($passwordErr) && empty($confirmPasswordErr) && empty($termsErr)) {
    require_once '../Config/db_connection.php';
    // Check if email already exists
    $checkEmailSql = "SELECT user_id FROM users WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailSql);
    if ($checkEmailResult && $checkEmailResult->num_rows > 0) {
      $emailErr = "This email is already registered";
    } else {
      // Hash the password
      // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      // Insert into database
      $insertSql = "INSERT INTO users (username, email, password, phone, address, role) VALUES ('$username', '$email', '$password', '$phone', '$address', 'customer')";
      if ($conn->query($insertSql) === TRUE) {
        $registrationSuccess = true;
        $loginMsg = "<div class='success-msg'>Registration successful! You can now <a href='../Commons/Login_Page.php'>log in</a>.</div>";
      } else {
        $loginMsg = "<div class='error'>Registration failed. Please try again later.</div>";
      }
    }
    $conn->close();
  }
}

// Helper function to clean input data
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../CSS/Registration.css">
  <title>Restaurant Management</title>
</head>
<body>
  <!-- <h2>Restaurant Management</h2> -->
  <div class="wrapper">
    <div class="form-header">
      <h2>Registration</h2>
      <center><p>Create your account to get started</p></center>
    </div>

    <form action="Registration.php" method="post">

      <div class="input-field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder=" " value="<?php echo htmlspecialchars($username); ?>">
        <span class="error"><?php if($showErrors) echo $usernameErr; ?></span>
      </div>

      <div class="input-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder=" " value="<?php echo htmlspecialchars($email); ?>">
        <span class="error"><?php if($showErrors) echo $emailErr; ?></span>
      </div>

      <div class="input-field">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" placeholder=" " pattern="[0-9]{10}" maxlength="10">
        <span class="error"><?php if($showErrors) echo $phoneErr; ?></span>
      </div>

      <div class="input-field">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" placeholder=" ">
        <span class="error"><?php if($showErrors) echo $addressErr; ?></span>
      </div>

      <div class="input-field">
         <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder=" ">
        <span class="error"><?php if($showErrors) echo $passwordErr; ?></span>
      </div>

      <div class="input-field">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder=" ">
        <span class="error"><?php if($showErrors) echo $confirmPasswordErr; ?></span>
      </div>

      <div class="checkbox-container">
        <div class="checkbox">
          <input type="checkbox" id="termsCondition" name="termsCondition">
          <label for="termsCondition">Agreed to the terms and conditions.</label>
        </div>
      </div>

      <center><button type="submit">Register</button></center>
      <a class="login" href="Login_Page.php"><span class="login-link">Already have an account? Log in.</span></a>
    </form>
  <center><?php if($showErrors && !empty($loginMsg) && $registrationSuccess) echo $loginMsg; ?></center>
  </div>
</body>
</html>