<?php
session_start();
include("db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Duty Manager</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  .btn-logout{
    background:#ffd6a5;
    border:round;
    color:#333;
  }

  .btn-logout:hover{
    background:#ffb703;
    color:brown;
  }

  body{
    background: linear-gradient(135deg,#fdfbfb,#ebedee);
    font-family:'Segoe UI';
    color:#333;
  }

  .container{
    max-width:900px;
  }

  .header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
  }

  .card{
    background:#ffffff;
    border-radius:15px;
    border:none;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
    margin-bottom:15px;
  }

  .section{
    background:#f8f9ff;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
  }

  .btn-custom{
    background:#c7ceea;
    border:round;
    color:#111;
    font-weight:500;
  }

  .btn-custom:hover{
    background:#fed6e3;
  }

  .btn-add-duty{
    background:#ffd6a5;
    border:none;
    color:#333;
    font-weight:600;
  }

  .btn-add-duty:hover{
    background:#ffb703;
    color:#5a2d0c;
  }

  .small-text{
    font-size:14px;
    color:#666;
  }

  h5{
    margin-bottom:15px;
  }

  .form-box{
    display:none;
    margin-top:15px;
    background:#ffffff;
    padding:18px;
    border-radius:14px;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
  }

  .message-success{
    background:#e6ffed;
    color:#146c43;
    padding:10px 14px;
    border-radius:12px;
    margin-bottom:15px;
  }

  .message-error{
    background:#ffe5e5;
    color:#b02a37;
    padding:10px 14px;
    border-radius:12px;
    margin-bottom:15px;
  }
</style>
</head>

<body>

<div class="container py-4">

  <div class="header">
    <h2>EduNexus</h2>
    <h4>Very warm welcome, <?php echo $_SESSION['user']; ?> ~</h4>
    <a href="logout.php" class="btn btn-logout btn-sm">Logout</a>
  </div>

  <div class="section">
    <h5>Manage Invigilation Duties:</h5>

    <?php if(!empty($success)){ ?>
      <div class="message-success"><?php echo htmlspecialchars($success); ?></div>
    <?php } ?>

    <?php if(!empty($error)){ ?>
      <div class="message-error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <input type="date" id="allocationDate" class="form-control mb-3">

    <button onclick="runAllocationByDate()" class="btn btn-custom w-100 mb-3">
      Allocate faculty for selected date
    </button>

    <a href="export_report.php" class="btn btn-success w-100 mb-3">
      Export Report to Excel/CSV
    </a>

    <button type="button" onclick="toggleDutyForm()" class="btn btn-add-duty w-100">
      Add New Duty
    </button>

    <div id="dutyFormBox" class="form-box">
      <form action="add_duty.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Date</label>
          <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Slot</label>
          <select name="slot" class="form-control" required>
            <option value="">Select Shift</option>
            <option value="Shift A">Shift A</option>
            <option value="Shift B">Shift B</option>
            <option value="Shift C">Shift C</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Room Number</label>
          <input type="text" name="room_number" class="form-control" placeholder="Enter room number" required>
        </div>

        <button type="submit" class="btn btn-custom w-100">
          Save Duty
        </button>
      </form>
    </div>
  </div>

  <div class="section">
    <h5>Exam Hall Invigilation Duties:</h5>

    <?php
    $duties = mysqli_query($conn, "
    SELECT * FROM duties
    ORDER BY date DESC, slot ASC, room_number ASC
    ");

    while($d = mysqli_fetch_assoc($duties)){
    ?>
      <div class="card p-3">
        <b>Room number: <?php echo $d['room_number']; ?></b>
        <div class="small-text">
          Date: <?php echo $d['date']; ?><br>
          Shift: <?php echo $d['slot']; ?>
        </div>
      </div>
    <?php } ?>
  </div>

  <div class="section">
    <h5>Allocated invigilation duties:</h5>

    <?php
    $alloc = mysqli_query($conn,"
    SELECT a.*, f.name, d.room_number, d.date, d.slot
    FROM allocations a
    JOIN faculty_real f ON a.faculty_id = f.id
    JOIN duties d ON a.duty_id = d.duty_id
    ORDER BY d.date DESC, d.slot ASC, d.room_number ASC
    ");

    while($row = mysqli_fetch_assoc($alloc)){
    ?>
      <div class="card p-3">
        <b><?php echo $row['name']; ?></b>
        <div class="small-text">
          Room number: <?php echo $row['room_number']; ?><br>
          Date: <?php echo $row['date']; ?><br>
          Shift: <?php echo $row['slot']; ?>
        </div>
      </div>
    <?php } ?>
  </div>

</div>

<script>
function toggleDutyForm(){
  const formBox = document.getElementById("dutyFormBox");

  if(formBox.style.display === "block"){
    formBox.style.display = "none";
  } else {
    formBox.style.display = "block";
  }
}

function runAllocationByDate(){
  const selectedDate = document.getElementById("allocationDate").value;

  if(selectedDate === ""){
    alert("Please select a date first.");
    return;
  }

  fetch("allocate_by_date.php?date=" + selectedDate)
  .then(async res => {
    const message = await res.text();
    if (!res.ok) {
      throw new Error(message || "Allocation failed.");
    }
    return message;
  })
  .then((message)=>{
    alert(message);
    location.reload();
  })
  .catch((error) => {
    alert(error.message);
  });
}
</script>

</body>
</html>
