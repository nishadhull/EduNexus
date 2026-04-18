<?php
session_start();

$error = $_SESSION['faculty_error'] ?? "";
unset($_SESSION['faculty_error']);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Faculty Login - EduNexus</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body{
      margin:0;
      min-height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
      background:linear-gradient(135deg, #fdfbfb, #ebedee, #dfe9f3);
      font-family:'Segoe UI', sans-serif;
      color:#333;
    }

    .login-box{
      width:100%;
      max-width:420px;
      background:#ffffff;
      padding:35px;
      border-radius:22px;
      box-shadow:0 10px 30px rgba(0,0,0,0.08);
    }

    .brand{
      text-align:center;
      font-size:32px;
      font-weight:800;
      color:#5b5f97;
      margin-bottom:10px;
    }

    .subtitle{
      text-align:center;
      color:#777;
      font-size:14px;
      margin-bottom:25px;
    }

    .form-control{
      border-radius:14px;
      padding:12px;
      border:1px solid #ddd;
    }

    .form-control:focus{
      box-shadow:none;
      border-color:#c7ceea;
    }

    .btn-login{
      width:100%;
      background:#c7ceea;
      border:none;
      border-radius:14px;
      padding:12px;
      font-weight:600;
      color:#222;
    }

    .btn-login:hover{
      background:#fed6e3;
    }

    .error-box{
      background:#ffe5e5;
      color:#b02a37;
      padding:10px 14px;
      border-radius:12px;
      margin-bottom:15px;
      font-size:14px;
    }
  </style>
</head>

<body>

<div class="login-box">
  <div class="brand">EduNexus</div>
  <p class="subtitle">Faculty Login</p>

  <?php if(!empty($error)){ ?>
    <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
  <?php } ?>

  <form action="faculty_login_check.php" method="POST">
    <input type="text" name="employee_code" class="form-control mb-3" placeholder="Enter Employee Code" required>
    <button type="submit" class="btn btn-login">Login</button>
  </form>
</div>

</body>
</html>
