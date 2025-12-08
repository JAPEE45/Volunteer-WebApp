<?php
session_start();
include_once "./utility/db.php";

$errorMessage = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  $stmt = $conn->prepare("SELECT * from account WHERE username = ?");
  $stmt->bind_param("s", $username);  
  $stmt->execute();
  $res = $stmt->get_result();
  
  if($res->num_rows > 0){
    $r = $res->fetch_assoc();
    
    // Verify password
    if($r['password'] === $password){
      // Password is correct, proceed with login
      if($r['user_type'] == 'admin'){
        $_SESSION['user_id'] = $r['user_id'];
        $_SESSION['username'] = $r['username'];
        header("Location: /Volunteer-WebApp/homepage.php");
        exit();
      }elseif($r['user_type'] == 'volunteer'){
        $_SESSION['user_id'] = $r['user_id'];
        $_SESSION['username'] = $r['username'];
        header("Location: /Volunteer-WebApp/profile/profile.php");
        exit();
      }
    } else {
      // Password is incorrect
      $errorMessage = "Incorrect password. Please try again.";
    }
  }else{
    // Username not found
    $errorMessage = "Username not found. Please check your credentials or create an account.";
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
    
    <?php if(!empty($errorMessage)): ?>
    <div class="error-message" style="background: linear-gradient(135deg, #fee 0%, #fcc 100%); border: 2px solid #dc143c; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; animation: shake 0.5s ease;">
      <i class="fas fa-exclamation-circle" style="color: #dc143c; font-size: 1.5rem;"></i>
      <span style="color: #a00000; font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($errorMessage); ?></span>
    </div>
    <?php endif; ?>
    
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
  
  <style>
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .error-message {
      animation: shake 0.5s ease, fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</body>
</html>