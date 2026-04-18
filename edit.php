<?php
include("db.php");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM duties WHERE duty_id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Invigilation Duty</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
  <div class="card p-4 shadow">
    <h4>Edit Invigilation Duty</h4>

    <form method="POST">
      <input type="text" name="room_number" class="form-control mb-2"
      value="<?php echo $row['room_number']; ?>" placeholder="Room Number" required>

      <input type="date" name="date" class="form-control mb-2"
      value="<?php echo $row['date']; ?>" required>

      <input type="text" name="slot" class="form-control mb-2"
      value="<?php echo $row['slot']; ?>" placeholder="Shift A / Shift B / Shift C" required>

      <button name="update" class="btn btn-success w-100">Update</button>
    </form>
  </div>
</div>

</body>
</html>

<?php
if(isset($_POST['update'])){
  $room = $_POST['room_number'];
  $date = $_POST['date'];
  $slot = $_POST['slot'];

  mysqli_query($conn, "UPDATE duties SET
  room_number='$room',
  date='$date',
  slot='$slot'
  WHERE duty_id=$id");

  header("Location: dashboard.php");
  exit();
}
?>
