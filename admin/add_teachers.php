<?php
session_start();
include("../includes/server.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_teacher'])) {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $year_level = $_POST['year_level']; 

    $query = "INSERT INTO teachers (first_name, last_name, email, password, year_level) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $year_level);

    if ($stmt->execute()) {
        echo "<script>alert('Teacher added successfully!'); window.location.href='manage_teachers.php';</script>";
    } else {
        echo "<script>alert('Error adding teacher!');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Teacher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4 w-50 mx-auto">
            <h2 class="mb-4 text-center">Add New Teacher</h2>
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
                <div class="text-center">
                    <button type="submit" name="add_teacher" class="btn btn-success">Add Teacher</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
