<?php
include 'db.php';

// Check ID
if(!isset($_GET['id'])){
    die("Invalid Request");
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM enquiries WHERE enquiry_id='$id'");
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("No data found");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Lead</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container" style="max-width:600px;">

<h3 class="text-center mb-4">Edit Lead</h3>

<form action="update_lead.php" method="POST" class="card p-4 shadow-sm">

  <input type="hidden" name="id" value="<?php echo $data['enquiry_id']; ?>">

  <label class="form-label">Student Name</label>
  <input type="text" name="student_name"
    value="<?php echo isset($data['student_name']) ? $data['student_name'] : ''; ?>"
    class="form-control mb-3" required>

  <label class="form-label">Phone Number</label>
  <input type="text" name="phone"
    value="<?php echo isset($data['phone']) ? $data['phone'] : ''; ?>"
    class="form-control mb-3" required>

  <label class="form-label">Email Address</label>
  <input type="email" name="email"
    value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
    class="form-control mb-3">

  <label class="form-label">City</label>
  <input type="text" name="city"
    value="<?php echo isset($data['city']) ? $data['city'] : ''; ?>"
    class="form-control mb-3">

  <label class="form-label">Course Interested</label>
  <input type="text" name="course"
    value="<?php echo isset($data['course_interested']) ? $data['course_interested'] : ''; ?>"
    class="form-control mb-3">

  <label class="form-label">Lead Source</label>
  <select name="source" class="form-control mb-3">
    <?php
    $sources = mysqli_query($conn, "SELECT * FROM sources WHERE status='Active'");
    while($s = mysqli_fetch_assoc($sources)) {
      $selected = ($s['source_name'] == $data['source']) ? "selected" : "";
    ?>
      <option value="<?php echo $s['source_name']; ?>" <?php echo $selected; ?>>
        <?php echo $s['source_name']; ?>
      </option>
    <?php } ?>
  </select>

  <label class="form-label">Lead Status</label>
  <select name="status" class="form-control mb-3">
    <option value="New" <?php if($data['status']=='New') echo 'selected'; ?>>New</option>
    <option value="Called" <?php if($data['status']=='Called') echo 'selected'; ?>>Called</option>
    <option value="Follow-up" <?php if($data['status']=='Follow-up') echo 'selected'; ?>>Follow-up</option>
    <option value="Converted" <?php if($data['status']=='Converted') echo 'selected'; ?>>Converted</option>
  </select>

  <button type="submit" class="btn btn-success w-100">Update Lead</button>

</form>

</div>

</body>
</html>