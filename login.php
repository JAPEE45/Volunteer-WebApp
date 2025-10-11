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
    
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
        position: relative;
        overflow: hidden;
      }

      /* Animated Background */
      body::before {
        content: "";
        position: absolute;
        width: 200%;
        height: 200%;
        background: 
          radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
          radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
          radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        animation: rotate 30s linear infinite;
      }

      @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      /* Floating Hearts Animation */
      .heart-bg {
        position: absolute;
        font-size: 3rem;
        opacity: 0.1;
        animation: float-up 15s linear infinite;
        pointer-events: none;
      }

      @keyframes float-up {
        0% {
          transform: translateY(100vh) rotate(0deg);
          opacity: 0.1;
        }
        50% {
          opacity: 0.15;
        }
        100% {
          transform: translateY(-100vh) rotate(360deg);
          opacity: 0;
        }
      }

      .heart-bg:nth-child(1) { left: 10%; animation-delay: 0s; }
      .heart-bg:nth-child(2) { left: 30%; animation-delay: 3s; font-size: 2rem; }
      .heart-bg:nth-child(3) { left: 50%; animation-delay: 6s; font-size: 4rem; }
      .heart-bg:nth-child(4) { left: 70%; animation-delay: 9s; font-size: 2.5rem; }
      .heart-bg:nth-child(5) { left: 90%; animation-delay: 12s; font-size: 3.5rem; }

      /* Login Container */
      .login-container {
        background: white;
        padding: 3rem 2.5rem;
        border-radius: 30px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        width: 100%;
        max-width: 450px;
        position: relative;
        z-index: 10;
        animation: slideUp 0.6s ease;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(50px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* Logo Section */
      .logo {
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
      }

      .logo img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #dc143c;
        box-shadow: 0 8px 24px rgba(220, 20, 60, 0.3);
        transition: all 0.3s ease;
        animation: logoAppear 0.6s ease 0.2s backwards;
      }

      @keyframes logoAppear {
        from {
          opacity: 0;
          transform: scale(0.5) rotate(-10deg);
        }
        to {
          opacity: 1;
          transform: scale(1) rotate(0deg);
        }
      }

      .logo img:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 12px 32px rgba(220, 20, 60, 0.4);
      }

      .logo::after {
        content: "Philippine Red Cross";
        display: block;
        margin-top: 1rem;
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .logo::before {
        content: "Volunteer Management System";
        display: block;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
      }

      /* Form Styling */
      form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        margin-top: 2rem;
      }

      .input-group {
        position: relative;
      }

      input[type="text"],
      input[type="password"] {
        width: 100%;
        padding: 1rem 1.25rem 1rem 3.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fafafa;
        font-family: inherit;
      }

      input[type="text"]:focus,
      input[type="password"]:focus {
        outline: none;
        border-color: #dc143c;
        background: white;
        box-shadow: 0 0 0 4px rgba(220, 20, 60, 0.1);
        transform: translateY(-2px);
      }

      /* Input Icons */
      input[type="text"]::before,
      input[type="password"]::before {
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
      }

      #username {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23dc143c'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 1.25rem center;
        background-size: 1.5rem;
      }

      #password {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23dc143c'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 1.25rem center;
        background-size: 1.5rem;
      }

      /* Submit Button */
      button[type="submit"] {
        background: linear-gradient(135deg, #dc143c 0%, #a00000 100%);
        color: white;
        padding: 1.125rem 2rem;
        border: none;
        border-radius: 15px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
        position: relative;
        overflow: hidden;
      }

      button[type="submit"]::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
      }

      button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(220, 20, 60, 0.4);
      }

      button[type="submit"]:hover::before {
        width: 300px;
        height: 300px;
      }

      button[type="submit"]:active {
        transform: translateY(0);
      }

      /* Social Login */
      .social-login {
        margin-top: 2rem;
        text-align: center;
      }

      .social-login p {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 0.95rem;
      }

      .social-login i {
        font-size: 2rem;
        margin: 0 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 50%;
        width: 3.5rem;
        height: 3.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .social-login i:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
      }

      .social-login .fa-facebook {
        color: #1877f2 !important;
      }

      .social-login .fa-facebook:hover {
        background: #1877f2;
        color: white !important;
      }

      .social-login .fa-envelope {
        color: #EA4335 !important;
      }

      .social-login .fa-envelope:hover {
        background: #EA4335;
        color: white !important;
      }

      /* Additional Links */
      .extra-links {
        margin-top: 1.5rem;
        text-align: center;
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
      }

      .extra-links a {
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
      }

      .extra-links a:hover {
        color: #dc143c;
      }

      /* Responsive Design */
      @media (max-width: 768px) {
        .login-container {
          margin: 1rem;
          padding: 2.5rem 2rem;
          border-radius: 25px;
        }

        .logo img {
          width: 100px;
          height: 100px;
        }

        .logo::after {
          font-size: 1.25rem;
        }

        .logo::before {
          font-size: 0.85rem;
        }

        input[type="text"],
        input[type="password"] {
          padding: 0.875rem 1rem 0.875rem 3.25rem;
          font-size: 0.95rem;
        }

        button[type="submit"] {
          padding: 1rem 1.5rem;
          font-size: 1rem;
        }

        .social-login i {
          font-size: 1.75rem;
          width: 3rem;
          height: 3rem;
        }
      }

      @media (max-width: 480px) {
        .login-container {
          padding: 2rem 1.5rem;
        }

        .logo img {
          width: 90px;
          height: 90px;
        }

        .logo::after {
          font-size: 1.1rem;
        }

        .extra-links {
          flex-direction: column;
          gap: 0.75rem;
        }
      }

      /* Loading Animation */
      .loading {
        pointer-events: none;
        opacity: 0.6;
      }

      .loading button[type="submit"]::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
      }

      @keyframes spin {
        to { transform: rotate(360deg); }
      }
    </style>
</head>
<body>
     <!-- Floating Hearts Background -->
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>
     <div class="heart-bg">❤️</div>

     <div class="login-container">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <form action="" method="post">
        <input type="text" id="username" placeholder="Username" name="username" required>
        <input type="password" id="password" placeholder="Password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <!-- <div class="social-login">
      <p>- - Login with - -</p>
      <i class="fa-brands fa-facebook" style="color: #1877f2;"></i>
      <i class="fa-solid fa-envelope" style="color: #EA4335;"></i>
    </div> -->
    <div class="extra-links">
      <!-- <a href="#">Forgot Password?</a> -->
      <a href="register.php" style='text-align:center'>Create Account</a>
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