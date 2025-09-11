<?php
include '../Config/db_connection.php';

$email = $password = "";
$emailErr = $passwordErr = "";
$loginMsg = "";
$showErrors = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $showErrors = true;

  if (empty($_POST["email"])) {
    $emailErr = "Email is required.";
  } else {
    $email = $_POST["email"];
  }

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required.";
  } else {
    $password = $_POST["password"];
  }

  if (!$emailErr && !$passwordErr) {
    $sql = "SELECT * FROM users WHERE email = '" . $conn->real_escape_string($email) . "' AND password = '" . $conn->real_escape_string($password) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
      } elseif ($user["role"] === "staff") {
        header("Location: staff_dashboard.php");
      } else {
        if ($password === $user['password']) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['address'] = $user['address'];
            $_SESSION['phone'] = $user['phone'];
            header('Location: ../Customer/dashboard_customer.php');
            exit;
        } else {
          $loginMsg = "<h3>Invalid email or password.</h3>";
        }
      }
      exit();
    } else {
      $loginMsg = "<h3>Invalid email or password.</h3>";
    }
    $stmt->close();

  }
  $conn->close();
  $email= $password= "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../CSS/login.css">
  <title>Restaurant Management</title>
</head>
<body>
  <!-- <h2>Restaurant Management</h2> -->
  <div class="center">
        <div class="material-logo">
            <div class="logo-layers">
                <div class="layer layer-1"></div>
                <div class="layer layer-2"></div>
                <div class="layer layer-3"></div>
              </div>
          </div>
    <h2>Sign In</h2>
    <form action="Login_Page.php" method="post">
	  <label for="email" class=>Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
      <span class="error"><?php if($showErrors) echo $emailErr; ?></span>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password">
      <span class="error"><?php if($showErrors) echo $passwordErr; ?></span>

      <div class="checkbox-container">
        <div class="checkbox">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember">Remember me</label>
        </div>
        <div class="forgot-password">
          <a href="Forgot_Password.php"><span>Forgot password?</span></a>
        </div>
      </div>

      <div class="login-button"><button type="submit">Login</button></div>
      <a class="register" href="Registration.php"><span class="register-link">Haven't account? Sign up.</span></a>
    </form>
    <center><?php if($showErrors) echo $loginMsg; ?></center>
  </div>
</body>
</html>