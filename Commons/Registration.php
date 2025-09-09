<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../CSS/Registration.css">
  <title>Restaurant Management</title>
</head>
<body>
  <!-- <h2>Restaurant Management</h2> -->
  <div class="wrapper">
    <h2>Registration</h2>
    <form action="Registration.php" method="post">
      <label for="username" class=>Username</label>
      <input type="text" id="username" name="username" placeholder="Enter your username" >
      <!-- value="<?php echo htmlspecialchars($username); ?>" -->
      <!-- <span class="error"><?php if($showErrors) echo $usernameErr; ?></span> -->

      <label for="email" class=>Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" >
      <!-- value="<?php echo htmlspecialchars($email); ?>" -->
      <!-- <span class="error"><?php if($showErrors) echo $emailErr; ?></span> -->

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password">
      <!-- <span class="error"><?php if($showErrors) echo $passwordErr; ?></span> -->

      <label for="confirmPassword">Confirm Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password">
      <!-- <span class="error"><?php if($showErrors) echo $confirmPasswordErr; ?></span> -->

      <div class="checkbox-container">
        <div class="checkbox">
          <input type="checkbox" id="termsCondition" name="termsCondition">
          <label for="termsCondition">Agreed to the terms and conditions.</label>
        </div>
      </div>

      <center><button type="submit">Register</button></center>
      <a class="login" href="Login_Page.php"><span class="login-link">Already have an account? Log in.</span></a>
    </form>
    <!-- <center><?php if($showErrors) echo $loginMsg; ?></center> -->
  </div>
</body>
</html>