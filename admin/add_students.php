<?php
session_start();
include("../includes/server.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $year_level = $_POST['year_level']; 
    $class_id   = $_POST['class_id'];  

   
    $stmt = $con->prepare("SELECT year_level FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $stmt->bind_result($class_year);
    $stmt->fetch();
    $stmt->close();

   
    if ($year_level !== $class_year) {
        echo "<script>
                alert('Error: The selected class ($class_year) does not match the student\'s year level ($year_level).');
                window.location.href='add_students.php';
              </script>";
        exit();
    }

   
    $query = "INSERT INTO students (first_name, last_name, email, password, year_level, class_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $password, $year_level, $class_id);

    if ($stmt->execute()) {
        echo "<script>alert('Student added successfully!'); window.location.href='manage_students.php';</script>";
    } else {
        echo "<script>alert('Error adding student!');</script>";
    }
    $stmt->close();
}


$classes = $con->query("SELECT class_id, class_name, year_level FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow p-4 w-50 mx-auto">
      <h2 class="mb-4 text-center">Add New Student</h2>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">First Name</label>
          <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Last Name</label>
          <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Enter password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Year Level</label>
          <select class="form-select" name="year_level" required>
            <option value="">Select Year Level</option>
            <option value="First Year">First Year</option>
            <option value="Second Year">Second Year</option>
            <option value="Third Year">Third Year</option>
            <option value="Fourth Year">Fourth Year</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Class (Section)</label>
          <select class="form-select" name="class_id" required>
            <option value="">Select Class</option>
            <?php while ($row = $classes->fetch_assoc()) : ?>
              <option value="<?= $row['class_id']; ?>">
                <?= $row['class_name']; ?> (<?= $row['year_level']; ?>)
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="text-center">
          <button type="submit" name="add_student" class="btn btn-success">Add Student</button>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
