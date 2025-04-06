<?php
session_start();
include("../includes/server.php");
include("../includes/admin_sidebar.php");


$query_classes = "SELECT COUNT(*) AS total_classes FROM classes";
$result_classes = $con->query($query_classes);
$total_classes = $result_classes->fetch_assoc()['total_classes'] ?? 0;

$query_teachers = "SELECT COUNT(*) AS total_teachers FROM teachers";
$result_teachers = $con->query($query_teachers);
$total_teachers = $result_teachers->fetch_assoc()['total_teachers'] ?? 0;

$query_students = "SELECT COUNT(*) AS total_students FROM students";
$result_students = $con->query($query_students);
$total_students = $result_students->fetch_assoc()['total_students'] ?? 0;

$query_subjects = "SELECT COUNT(*) AS total_subjects FROM subjects";
$result_subjects = $con->query($query_subjects);
$total_subjects = $result_subjects->fetch_assoc()['total_subjects'] ?? 0;

$query_enrolled = "SELECT COUNT(DISTINCT student_id) AS total_enrolled FROM enrollments";
$result_enrolled = $con->query($query_enrolled);
$total_enrolled = $result_enrolled->fetch_assoc()['total_enrolled'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    <div class="container dashboard_body mt-4">
        <h2 class="mb-3">Admin Dashboard</h2>
        <div class="d-flex justify-content-center flex-wrap gap-3 box">
            <div class="dashboard-card">
                <i class="bi bi-building-fill" style="font-size: 3rem;"></i>
                <h3><?= $total_classes; ?></h3>
                <p>Total Classes</p>
            </div>
            <div class="dashboard-card">
                <i class="bi bi-person-badge-fill" style="font-size: 3rem;"></i>
                <h3><?= $total_teachers; ?></h3>
                <p>Total Teachers</p>
            </div>
            <div class="dashboard-card">
                <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                <h3><?= $total_students; ?></h3>
                <p>Total Students</p>
            </div>
            <div class="dashboard-card">
                <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                <h3><?= $total_subjects; ?></h3>
                <p>Total Subjects</p>
            </div>
            <div class="dashboard-card">
                <i class="bi bi-journal-check" style="font-size: 3rem;"></i>
                <h3><?= $total_enrolled; ?></h3>
                <p>Enrolled Students</p>
            </div>
        </div>
    </div>
</body>

</html>