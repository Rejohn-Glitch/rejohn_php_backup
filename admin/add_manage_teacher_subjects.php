<?php
include("../includes/server.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_subject'])) {
    $class_id   = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];

   
    $stmt = $con->prepare("SELECT year_level FROM teachers WHERE teacher_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $stmt->bind_result($teacher_year);
    $stmt->fetch();
    $stmt->close();


    $stmt = $con->prepare("SELECT year_level FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $stmt->bind_result($class_year);
    $stmt->fetch();
    $stmt->close();

    
    if ($teacher_year !== $class_year) {
        echo "<script>
                alert('Error: Teacher\'s year level is " . $teacher_year . " but the class requires " . $class_year . ".');
                window.location.href = 'manage_teacher_subjects.php';
              </script>";
        exit();
    }

    
    $checkQuery = "SELECT * FROM teacher_subjects WHERE teacher_id = $teacher_id AND subject_id = $subject_id AND class_id = $class_id";
    $result = $con->query($checkQuery);

    if ($result && $result->num_rows > 0) {
        echo "<script>
                alert('This teacher is already assigned this subject in the selected class.');
                window.location.href = 'manage_teacher_subjects.php';
              </script>";
        exit();
    }

    $con->query("INSERT INTO teacher_subjects (class_id, subject_id, teacher_id) VALUES ($class_id, $subject_id, $teacher_id)");
    header("Location: manage_teacher_subjects.php");
    exit();
}

$classes = $con->query("SELECT * FROM classes");
$subjects = $con->query("SELECT * FROM subjects");
$teachers = $con->query("SELECT * FROM teachers");

$teacherSubjects = $con->query("SELECT ts.teacher_subject_id, c.class_name, s.subject_name, t.first_name, t.last_name 
                              FROM teacher_subjects ts
                              JOIN classes c ON ts.class_id = c.class_id
                              JOIN subjects s ON ts.subject_id = s.subject_id
                              JOIN teachers t ON ts.teacher_id = t.teacher_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teacher Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center">Manage Teacher Subjects</h2>
            <form method="POST" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <select class="form-select" name="class_id" required>
                            <?php while ($class = $classes->fetch_assoc()): ?>
                                <option value="<?= $class['class_id']; ?>">
                                    <?= $class['class_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Subject</label>
                        <select class="form-select" name="subject_id" required>
                            <?php while ($subject = $subjects->fetch_assoc()): ?>
                                <option value="<?= $subject['subject_id']; ?>">
                                    <?= $subject['subject_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teacher</label>
                        <select class="form-select" name="teacher_id" required>
                            <?php while ($teacher = $teachers->fetch_assoc()): ?>
                                <option value="<?= $teacher['teacher_id']; ?>">
                                    <?= $teacher['first_name'] . " " . $teacher['last_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" name="assign_subject" class="btn btn-success">Assign Subject</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
