<?php
      include_once "./utility/db.php";
  if($_SERVER['REQUEST_METHOD'] == "POST"){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT * from account WHERE username = ?");
  $stmt->bind_param("s", $username);  
  $stmt->execute();
  $res = $stmt->get_result();
  if($res->num_rows > 0){
    $r = $res->fetch_assoc();
    if($r['user_type'] == 'admin'){
      header("Location: /Volunteer-WebApp/homepage.php");
      exit();
      echo "jo";
    }elseif($r['user_type'] == 'volunteer'){
      session_start();
      $_SESSION['user_id'] = $r['user_id'];
      header("Location: /Volunteer-WebApp/profile/profile.php");
      exit();
    }
  }else{
    echo "not good";
  }
  $stmt->close();
  $conn->close();

  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Philippine Red Cross</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
     <!-- Floating Hearts Background -->
     <!-- <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div> -->

     <div class="login-container">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <form action="" method="post">
        <input type="text" id="username" placeholder="Username" name="username" required>
        <input type="password" id="password" placeholder="Password" name="password" required>
        <button type="submit" style="padding-bottom:10px;">Login</button>
    </form>
    <!-- <div class="social-login">
      <p>- - Login with - -</p>
      <i class="fa-brands fa-facebook" style="color: #1877f2;"></i>
      <i class="fa-solid fa-envelope" style="color: #EA4335;"></i>
    </div> -->
    <div class="extra-links">
      <!-- <a href="#">Forgot Password?</a> -->
      <a href="register.php" style='text-align:center; text-decorator:none; color:white;padding:20px!important;'>Create Account</a>
    </div>
  </div>

  <script src="script/login.js"></script>
  <script>
    // Add loading state on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
      const button = this.querySelector('button[type="submit"]');
      button.textContent = 'Logging in...';
      this.classList.add('loading');
    });

    // Add animation to inputs
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>
</html>