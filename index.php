<?php
      include_once "./utility/db.php";
  if($_SERVER['REQUEST_METHOD'] == "POST"){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT * from users WHERE username = ?");
  $stmt->bind_param("s", $username);
  if($stmt->execute()){
    echo "goods";
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
    <link rel="stylesheet" href="style/login.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
     <div class="login-container">
    <div class="logo">
      <img src="img/Philippine_Red_Cross_logo.jpg" alt="Red Cross Logo">
    </div>
    <form action="" method="post">
        <input type="text" id="username" placeholder="Username" name="username">
        <input type="password" id="password" placeholder="Password" name="password">
        <button type="submit">Login</button>
    </form>
    <div class="social-login">
      <p>- - Login with - -</p>
      <i class="fa-brands fa-facebook" style="color: #1877f2;"></i>
      <i class="fa-solid fa-envelope" style="color: #1877f2;"></i>
    </div>
  </div>
</body>
<script src="script/login.js"></script>
</html>