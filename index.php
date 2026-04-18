<?php
session_start();

$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
  <title>EduNexus - Login</title>

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

    .login-wrapper{
      width:100%;
      max-width:440px;
      padding:20px;
    }

    .login-box{
      background:#ffffff;
      padding:38px;
      border-radius:26px;
      box-shadow:0 12px 35px rgba(0,0,0,0.08);
      border:1px solid rgba(255,255,255,0.7);
    }

    .brand{
      text-align:center;
      font-size:34px;
      font-weight:800;
      color:#5b5f97;
      margin-bottom:8px;
    }

    .tagline{
      text-align:center;
      color:#777;
      font-size:14px;
      margin-bottom:28px;
    }

    .soft-badge{
      width:70px;
      height:70px;
      margin:0 auto 15px;
      border-radius:50%;
      background:linear-gradient(135deg, #c7ceea, #fed6e3);
      display:flex;
      justify-content:center;
      align-items:center;
      font-size:30px;
      box-shadow:0 8px 20px rgba(0,0,0,0.08);
    }

    .form-label{
      font-weight:600;
      color:#555;
      font-size:14px;
    }

    .form-control{
      border-radius:15px;
      padding:12px 14px;
      border:1px solid #dddddd;
      background:#fbfbff;
    }

    .form-control:focus{
      box-shadow:none;
      border-color:#c7ceea;
      background:#ffffff;
    }

    .btn-admin{
      width:100%;
      background:#c7ceea;
      border:none;
      border-radius:15px;
      padding:12px;
      font-weight:700;
      color:#222;
      margin-top:8px;
      transition:0.3s;
    }

    .btn-admin:hover{
      background:#b8c0e3;
      color:#222;
      transform:translateY(-1px);
    }

    .btn-faculty{
      width:100%;
      display:block;
      text-align:center;
      background:#ffd6a5;
      border:none;
      border-radius:15px;
      padding:12px;
      font-weight:700;
      color:#333;
      text-decoration:none;
      margin-top:12px;
      transition:0.3s;
    }

    .btn-faculty:hover{
      background:#ffb703;
      color:#5a2d0c;
      transform:translateY(-1px);
    }

    .error-box{
      background:#ffe5e5;
      color:#b02a37;
      padding:11px 14px;
      border-radius:14px;
      margin-bottom:18px;
      font-size:14px;
      text-align:center;
    }

    .mini-text{
      text-align:center;
      margin-top:18px;
      color:#888;
      font-size:13px;
    }
  </style>
</head>

<body>

<div class="login-wrapper">
  <div class="login-box">

    <div class="soft-badge">EN</div>

    <div class="brand">EduNexus</div>
    <p class="tagline">Examination Duty Allocation System</p>

    <?php if(!empty($error)){ ?>
      <div class="error-box">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php } ?>

    <form action="login.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Admin Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>

      <button type="submit" class="btn btn-admin">Admin Login</button>
    </form>

    <a href="faculty_login.php" class="btn-faculty">Faculty Login</a>

    <div class="mini-text">
      Manage duties, faculty allocation, and reports in one place.
    </div>

  </div>
</div>

</body>
</html>
