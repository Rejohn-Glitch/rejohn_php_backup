<?php
session_start();
include("../includes/server.php");
include("../includes/teacher_sidebar.php");

if (!isset($_SESSION['teacher_id'])) {
    echo "<script>alert('Access denied! Teacher ID missing.'); window.location.href='../index.php';</script>";
    exit();
}

$teacher_id = $_SESSION['teacher_id'];


$query_students = "SELECT COUNT(DISTINCT e.student_id) AS total_students
                   FROM enrollments e
                   JOIN teacher_subjects cs ON e.teacher_subject_id = cs.teacher_subject_id
                   WHERE cs.teacher_id = ?";
$stmt = $con->prepare($query_students);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result_students = $stmt->get_result()->fetch_assoc();
$total_students = $result_students['total_students'] ?? 0;
$stmt->close();


$query_classes = "SELECT COUNT(DISTINCT cs.class_id) AS total_classes
                  FROM teacher_subjects cs
                  WHERE cs.teacher_id = ?";
$stmt = $con->prepare($query_classes);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result_classes = $stmt->get_result()->fetch_assoc();
$total_classes = $result_classes['total_classes'] ?? 0;
$stmt->close();


$query_subjects = "SELECT COUNT(DISTINCT cs.subject_id) AS total_subjects
                   FROM teacher_subjects cs
                   WHERE cs.teacher_id = ?";
$stmt = $con->prepare($query_subjects);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result_subjects = $stmt->get_result()->fetch_assoc();
$total_subjects = $result_subjects['total_subjects'] ?? 0;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #690B22;
            font-family: 'Roboto', sans-serif;
            color: white;
        }
        .dashboard_body {
            margin-top: 2%;
            margin-left: 30%;
            width: 65%;
            padding: 10px;
            transition: margin-left 0.3s ease;
        }
        .dashboard-card {
            border-radius: 10px;
            background: #fff;
            color: #690B22;
            padding: 20px;
            text-align: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        .box {
            margin-top: 13%;
        }
        .dashboard-card h3 {
            font-size: 2rem;
            font-weight: bold;
        }
        .dashboard-card p {
            font-size: 1.2rem;
            font-weight: 500;
            margin-top: 5px;
        }
        @media (max-width: 991px) {
            .dashboard_body {
                margin-left: 10%;
                width: 70%;
            }
        }
        @media (max-width: 768px) {
            .dashboard_body {
                margin-left: 5%;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="container dashboard_body mt-4">
            <h2 class="mb-3"></h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                        <h3><?php echo $total_students; ?></h3>
                        <p>Total Students</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <i class="bi bi-house-door-fill" style="font-size: 3rem;"></i>
                        <h3><?php echo $total_classes; ?></h3>
                        <p>Assigned Sections</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                        <h3><?php echo $total_subjects; ?></h3>
                        <p>Assigned Subjects</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
