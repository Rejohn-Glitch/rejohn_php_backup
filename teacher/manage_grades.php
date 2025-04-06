<?php
session_start();
include("../includes/server.php");
include("../includes/teacher_sidebar.php");

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$result = null;

try {
    
    $query = "SELECT s.student_id, s.first_name, s.last_name, c.class_name, sub.subject_name, e.enrollment_id, 
                     g.prelim, g.midterm, g.pre, g.finals, g.overall_grade, g.status
              FROM students s
              JOIN enrollments e ON s.student_id = e.student_id
              JOIN teacher_subjects ts ON e.teacher_subject_id = ts.teacher_subject_id
              JOIN classes c ON e.class_id = c.class_id
              JOIN subjects sub ON ts.subject_id = sub.subject_id
              LEFT JOIN grades g ON e.enrollment_id = g.enrollment_id
              WHERE ts.teacher_id = ?";
    
    $stmt = $con->prepare($query) or die($con->error);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute() or die($stmt->error);
    $result = $stmt->get_result();
    $stmt->close();

} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    die("<script>alert('Error loading student data.'); window.location.href='manage_grades.php';</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_grade'])) {
    $enrollment_id = $_POST['enrollment_id'];
    $prelim = $_POST['prelim'];
    $midterm = $_POST['midterm'];
    $pre = $_POST['pre'];
    $finals = $_POST['finals'];
    
    if (!empty($prelim) && !empty($midterm) && !empty($pre) && !empty($finals)) {
        $overall_grade = ($prelim + $midterm + $pre + $finals) / 4;
        $overall_grade = number_format($overall_grade, 2);
        
        if ($overall_grade >= 90) {
            $status = "Outstanding";
        } elseif ($overall_grade >= 80) {
            $status = "Very Good";
        } elseif ($overall_grade >= 70) {
            $status = "Good";
        } elseif ($overall_grade >= 60) {
            $status = "Bad";
        } else {
            $status = "Failing";
        }
    } else {
        $overall_grade = null;
        $status = "Not Graded";
    }
    
    try {
        $checkQuery = "SELECT * FROM grades WHERE enrollment_id = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param("i", $enrollment_id);
        $stmt->execute();
        $gradeExists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        
        if ($gradeExists) {
            $query = "UPDATE grades SET 
                      prelim = ?, 
                      midterm = ?, 
                      pre = ?, 
                      finals = ?, 
                      overall_grade = ?, 
                      status = ? 
                      WHERE enrollment_id = ?";
        } else {
            $query = "INSERT INTO grades 
                     (enrollment_id, prelim, midterm, pre, finals, overall_grade, status) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        }
        
        $stmt = $con->prepare($query);
        if ($gradeExists) {
            $stmt->bind_param("ddddssi", $prelim, $midterm, $pre, $finals, $overall_grade, $status, $enrollment_id);
        } else {
            $stmt->bind_param("iddddss", $enrollment_id, $prelim, $midterm, $pre, $finals, $overall_grade, $status);
        }
        
        if ($stmt->execute()) {
            echo "<script>alert('Grade successfully saved!'); window.location.href='manage_grades.php';</script>";
        } else {
            throw new Exception("Error saving grade: " . $stmt->error);
        }
        $stmt->close();
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "<script>alert('Error saving grade. Please try again.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #690B22; font-family: 'Roboto', sans-serif; color: white; }
        .manage_body { margin-top: 1%; margin-left: 30%; width: 65%; padding: 5px; }
        .table-container { max-height: 570px; overflow-y: auto; background: white; border-radius: 5px; }
        .table { font-size: 0.75rem; table-layout: fixed; }
        .table th { font-size: 0.7rem; padding: 6px !important; }
        .table td { padding: 4px !important; vertical-align: middle; }
        .table td:nth-child(2), .table td:nth-child(4) { white-space: normal; word-break: break-word; }
        .btn-save { padding: 2px 5px; font-size: 0.65rem; }
        .form-control { height: 25px; font-size: 0.7rem; }
        
        @media (max-width: 768px) {
            .manage_body { margin-left: 5% !important; width: 90% !important; }
            .table { font-size: 0.65rem; }
            .btn-save { font-size: 0.6rem; }
        }
    </style>
</head>
<body>
    <div class="container manage_body mt-2">
        <h4 class="mb-2 text-center" style="font-size: 1.1rem;">Manage Grades</h4>
        <div class="card p-2">
            <div class="table-container table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 16%">Name</th>
                            <th style="width: 10%">Class</th>
                            <th style="width: 16%">Subject</th>
                            <th style="width: 7%">Prelim</th>
                            <th style="width: 7%">Midterm</th>
                            <th style="width: 7%">Pre</th>
                            <th style="width: 7%">Finals</th>
                            <th style="width: 8%">Overall</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 7%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <form method="POST">
                                        <input type="hidden" name="enrollment_id" value="<?= htmlspecialchars($row['enrollment_id']) ?>">
                                        <td><?= $row['student_id'] ?></td>
                                        <td><?= $row['last_name'] . ", " . $row['first_name'] ?></td>
                                        <td><?= $row['class_name'] ?></td>
                                        <td><?= $row['subject_name'] ?></td>
                                        <td><input type="number" name="prelim" class="form-control form-control-sm" 
                                               value="<?= $row['prelim'] ?? '' ?>" min="0" max="100" step="0.1"></td>
                                        <td><input type="number" name="midterm" class="form-control form-control-sm" 
                                               value="<?= $row['midterm'] ?? '' ?>" min="0" max="100" step="0.1"></td>
                                        <td><input type="number" name="pre" class="form-control form-control-sm" 
                                               value="<?= $row['pre'] ?? '' ?>" min="0" max="100" step="0.1"></td>
                                        <td><input type="number" name="finals" class="form-control form-control-sm" 
                                               value="<?= $row['finals'] ?? '' ?>" min="0" max="100" step="0.1"></td>
                                        <td><?= $row['overall_grade'] ?? '' ?></td>
                                        <td><?= $row['status'] ?? 'Not Graded' ?></td>
                                        <td><button type="submit" name="submit_grade" class="btn-save btn btn-success"><i class="bi bi-save"></i> Save</button></td>
                                    </form>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center py-2">No student records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>