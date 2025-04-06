<?php
session_start();
include("../includes/server.php");
include("../includes/teacher_sidebar.php");

if (!isset($_SESSION['teacher_id'])) {
    echo "<script>alert('Access denied! Teacher ID missing.'); window.location.href='../index.php';</script>";
    exit();
}

$teacher_id = $_SESSION['teacher_id'];


$query = "SELECT s.first_name, s.last_name, s.email, s.year_level, c.class_name
          FROM students s
          INNER JOIN enrollments e ON s.student_id = e.student_id
          INNER JOIN teacher_subjects ts ON e.teacher_subject_id = ts.teacher_subject_id
          INNER JOIN classes c ON ts.class_id = c.class_id
          WHERE ts.teacher_id = ?";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #690B22;
            font-family: 'Roboto', sans-serif;
            color: white;
        }
        .manage_body {
            margin-top: 2%;
            margin-left: 30%;
            width: 65%;
            padding: 10px;
            transition: margin-left 0.3s ease;
        }
        .table-container {
            max-height: 570px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            background: white;
            color: black;
            border-radius: 10px;
            padding: 10px;
        }
        .table th, .table td {
            white-space: nowrap;
            text-align: center;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        @media (max-width: 991px) {
            .manage_body {
                margin-left: 10%;
                width: 80%;
            }
        }
        @media (max-width: 768px) {
            .manage_body {
                margin-left: 5%;
                width: 90%;
            }
            .table {
                font-size: 14px;
            }
            .table th, .table td {
                padding: 5px;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="container manage_body mt-4">
        <h2 class="mb-3 text-center">View Students</h2>
        <div class="card p-3">
            <div class="table-container table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Class</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['class_name']; ?></td>
                                <td><?= $row['first_name']; ?></td>
                                <td><?= $row['last_name']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td><?= $row['year_level']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
