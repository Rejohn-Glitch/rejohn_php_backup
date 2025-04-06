<?php
session_start();
include("../includes/server.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$student_selected = false;
$student_id = 0;
$student_class_id = 0;
$student_year = "";
$student_first = "";
$student_last = "";
$teacher_subjects = null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['student']) && !empty($_POST['student'])) {
        $student_selected = true;
        $student_id = intval($_POST['student']);
        
        
        $stmt = $con->prepare("SELECT class_id, year_level, first_name, last_name FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($student_class_id, $student_year, $student_first, $student_last);
        $stmt->fetch();
        $stmt->close();
    }
    
   
    if (isset($_POST['teacher_subject']) && !empty($_POST['teacher_subject'])) {
        $teacher_subject_id = intval($_POST['teacher_subject']);
        
      
        $stmt = $con->prepare("SELECT class_id FROM teacher_subjects WHERE teacher_subject_id = ?");
        $stmt->bind_param("i", $teacher_subject_id);
        $stmt->execute();
        $stmt->bind_result($ts_class_id);
        $stmt->fetch();
        $stmt->close();
        
      
        $stmt = $con->prepare("SELECT class_id FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($student_class);
        $stmt->fetch();
        $stmt->close();
        
       
        if ($ts_class_id != $student_class) {
            echo "<script>
                    alert('Error: The selected subject is not available for the student\\'s assigned class.');
                    window.location.href = 'add_student_enrollment.php';
                  </script>";
            exit();
        }
        
       
        $stmt = $con->prepare("SELECT enrollment_id FROM enrollments WHERE student_id = ? AND teacher_subject_id = ?");
        $stmt->bind_param("ii", $student_id, $teacher_subject_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "<script>
                    alert('Student is already enrolled in this subject.');
                    window.location.href = 'manage_enrollments.php';
                  </script>";
            exit();
        }
        $stmt->close();
        
      
        $stmt = $con->prepare("INSERT INTO enrollments (student_id, teacher_subject_id, class_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $student_id, $teacher_subject_id, $student_class);
        if ($stmt->execute()) {
            header("Location: manage_enrollments.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        
        if ($student_selected) {
            $teacher_subjects = $con->query("SELECT ts.teacher_subject_id, c.class_name, s.subject_name, t.first_name, t.last_name 
                                              FROM teacher_subjects ts
                                              JOIN classes c ON ts.class_id = c.class_id
                                              JOIN subjects s ON ts.subject_id = s.subject_id
                                              JOIN teachers t ON ts.teacher_id = t.teacher_id
                                              WHERE ts.class_id = $student_class_id");
        }
    }
}


$students = $con->query("SELECT student_id, first_name, last_name, year_level FROM students");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enroll Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
      <div class="card shadow p-4 w-50 mx-auto">
          <h2 class="mb-4 text-center">Enroll Student</h2>
          <form method="POST" action="">
             
              <div class="mb-3">
                  <label class="form-label">Student</label>
                  <select name="student" class="form-select" required>
                      <option value="">Select Student</option>
                      <?php while ($row = $students->fetch_assoc()) : ?>
                          <option value="<?= $row['student_id']; ?>"
                          <?php if($student_selected && $row['student_id'] == $student_id) echo "selected"; ?>>
                              <?= $row['first_name'] . " " . $row['last_name']; ?> (<?= $row['year_level']; ?>)
                          </option>
                      <?php endwhile; ?>
                  </select>
              </div>
          
              <div class="mb-3">
                  <label class="form-label">Class & Subject</label>
                  <?php if ($student_selected && $teacher_subjects): ?>
                      <select name="teacher_subject" class="form-select" required>
                          <option value="">Select Subject</option>
                          <?php while ($row = $teacher_subjects->fetch_assoc()) : ?>
                              <option value="<?= $row['teacher_subject_id']; ?>">
                                  <?= $row['class_name'] . " - " . $row['subject_name'] . " (Teacher: " . $row['first_name'] . " " . $row['last_name'] . ")"; ?>
                              </option>
                          <?php endwhile; ?>
                      </select>
                  <?php else: ?>
                      <select name="teacher_subject" class="form-select" disabled>
                          <option value="">Please select a student first</option>
                      </select>
                  <?php endif; ?>
              </div>
              
              <div class="text-center">
                  <button type="submit" class="btn btn-primary">
                      <?php echo ($student_selected && $teacher_subjects) ? "Enroll Student" : "Submit"; ?>
                  </button>
              </div>
          </form>
      </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
