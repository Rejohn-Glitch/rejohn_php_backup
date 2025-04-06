<?php
include("../includes/server.php");



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_teacher'])) {
    $teacher_id = $_POST[''];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];


    $query = "UPDATE teachers SET first_name = ?, last_name = ?, email = ?, password = ?, subject_id = ? WHERE teacher_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssii", $first_name, $last_name, $email, $password, $subject_id, $teacher_id);

    if ($stmt->execute()) {
        echo "<script>alert('Teacher edited successfully!'); window.location.href='manage_teachers.php';</script>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Update Teacher</title>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4 w-50 mx-auto">
            <h2 class="mb-4 text-center">Update Teacher</h2>
            <form method="POST">
                <input type="hidden" name="teacher_id" value="<?php echo $_GET['id']; ?>">
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
                    <label class="form-label">Subject</label>
                    <select class="form-select" name="subject_id" required>
                        <option value="" disabled selected>Select Subject</option>
                        <?php
                        $subjects = $con->query("SELECT * FROM subjects");
                        while ($row = $subjects->fetch_assoc()) {
                            echo "<option value='{$row['subject_id']}'> {$row['subject_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" name="update_teacher" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>