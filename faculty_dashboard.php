<?php
session_start();
include("db.php");

if(!isset($_SESSION['faculty_id'])){
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];
$faculty_name = $_SESSION['faculty_name'] ?? "Faculty";

mysqli_query($conn, "
UPDATE notifications
SET status = 'read'
WHERE faculty_id = $faculty_id AND status = 'unread'
");
?>


<!DOCTYPE html>
<html>
<head>
  <title>Faculty Dashboard - EduNexus</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body{
      margin:0;
      min-height:100vh;
      background:linear-gradient(135deg, #fdfbfb, #ebedee, #dfe9f3);
      font-family:'Segoe UI', sans-serif;
      color:#333;
    }

    .container{
      max-width:900px;
    }

    .top-bar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:25px 0;
    }

    .brand{
      font-size:32px;
      font-weight:800;
      color:#5b5f97;
    }

    .welcome{
      font-size:18px;
      color:#555;
      background:#fff7e6;
      padding:10px 18px;
      border-radius:30px;
      box-shadow:0 4px 12px rgba(0,0,0,0.05);
    }

    .hero{
      background:linear-gradient(135deg, #c7ceea, #fed6e3);
      border-radius:22px;
      padding:30px;
      margin-bottom:25px;
      box-shadow:0 8px 25px rgba(0,0,0,0.08);
    }

    .hero h2{
      margin:0;
      font-weight:750;
      color:#333;
    }

    .hero p{
      margin-top:8px;
      margin-bottom:0;
      color:#555;
    }

    .section{
      background:#f8f9ff;
      padding:25px;
      border-radius:22px;
      box-shadow:0 8px 25px rgba(0,0,0,0.04);
    }

    .section-title{
      font-size:22px;
      font-weight:700;
      margin-bottom:20px;
      color:#444;
    }

    .duty-card{
      background:#ffffff;
      border:none;
      border-radius:18px;
      padding:18px 20px;
      margin-bottom:15px;
      box-shadow:0 5px 18px rgba(0,0,0,0.05);
      border-left:7px solid #c7ceea;
    }

    .duty-card h5{
      margin:0;
      font-weight:700;
      color:#333;
    }

    .duty-card p{
      margin:6px 0 0;
      color:#777;
      font-size:14px;
    }

    .empty-box{
      background:#ffffff;
      border-radius:18px;
      padding:25px;
      text-align:center;
      color:#777;
      box-shadow:0 5px 18px rgba(0,0,0,0.05);
    }

    .btn-soft{
      background:#ffd6a5;
      border:none;
      border-radius:20px;
      padding:9px 18px;
      color:#333;
      font-weight:600;
      text-decoration:none;
    }

    .btn-soft:hover{
      background:#ffb703;
      color:#5a2d0c;
    }

    @media(max-width:768px){
      .top-bar{
        flex-direction:column;
        gap:12px;
        text-align:center;
      }

      .hero{
        text-align:center;
      }
    }
  </style>
</head>

<body>

<div class="container py-3">

  <div class="top-bar">
    <div class="brand">EduNexus</div>
    <div class="welcome">
      Welcome, <?php echo htmlspecialchars($faculty_name); ?> ~
    </div>
  </div>

  <div class="hero">
    <h2>Faculty Dashboard</h2>
    <p>Here are your examination duty allocations for the current schedule.</p>
  </div>

  <div class="section">
    <div class="section-title">My Allocated Duties</div>


    <div class="section mt-4">
  <div class="section-title">My Notifications</div>

  <?php
  $notify = mysqli_query($conn, "
  SELECT * FROM notifications
  WHERE faculty_id = $faculty_id
  ORDER BY created_at DESC
  ");

  if($notify && mysqli_num_rows($notify) > 0){
    while($note = mysqli_fetch_assoc($notify)){
  ?>
    <div class="duty-card">
      <h5><?php echo htmlspecialchars($note['message']); ?></h5>
      <p>Status: <?php echo htmlspecialchars($note['status']); ?></p>
      <p>Created At: <?php echo htmlspecialchars($note['created_at']); ?></p>
    </div>
  <?php
    }
  } else {
  ?>
    <div class="empty-box">
      No notifications yet.
    </div>
  <?php } ?>
</div>

    <?php
$query = mysqli_query($conn, "
SELECT d.room_number, d.date, d.slot
FROM allocations a
JOIN duties d ON a.duty_id = d.duty_id
WHERE a.faculty_id = $faculty_id
ORDER BY d.date DESC, d.slot ASC
");




    if($query && mysqli_num_rows($query) > 0){
      while($row = mysqli_fetch_assoc($query)){
    ?>

   <div class="duty-card">
  <h5>Room number: <?php echo htmlspecialchars($row['room_number']); ?></h5>
  <p>Date: <?php echo htmlspecialchars($row['date']); ?></p>
  <p>Shift: <?php echo htmlspecialchars($row['slot']); ?></p>
  <p>Status: Assigned successfully</p>
</div>




    <?php
      }
    } else {
    ?>

      <div class="empty-box">
        No duties allocated yet.
      </div>

    <?php } ?>

  </div>

  <div class="text-center mt-4">
    <a href="logout.php" class="btn-soft">Logout</a>
  </div>

</div>

</body>
</html>
