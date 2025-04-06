<?php
session_start();
include("../includes/server.php");
include("../includes/teacher_sidebar.php");

if (!isset($_SESSION['teacher_id'])) {
    echo "<script>alert('Access denied! Teacher ID missing.'); window.location.href='../index.php';</script>";
    exit();
}

$teacher_id = $_SESSION['teacher_id'];


$query = "SELECT c.class_name, s.subject_name, ts.teacher_subject_id 
          FROM teacher_subjects ts
          JOIN classes c ON ts.class_id = c.class_id
          JOIN subjects s ON ts.subject_id = s.subject_id
          WHERE ts.teacher_id = ?";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if (isset($_GET['drop'])) {
    $ts_id = intval($_GET['drop']);
    $stmt = $con->prepare("DELETE FROM teacher_subjects WHERE teacher_subject_id = ? AND teacher_id = ?");
    $stmt->bind_param("ii", $ts_id, $teacher_id);
    if ($stmt->execute()) {
        echo "<script>alert('Subject dropped successfully.'); window.location.href = 'view_subject.php';</script>";
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subjects</title>
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
        .btn-drop {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
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
        <h2 class="mb-3 text-center">View Subjects</h2>
        <div class="card p-3">
            <div class="table-container table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['class_name']; ?></td>
                                <td><?= $row['subject_name']; ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm btn-drop" onclick="dropSubject(<?= $row['teacher_subject_id'] ?>)">
                                        <i class="bi bi-x-circle"></i> Drop
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function dropSubject(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Dropping will remove this subject from your assignments.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, drop it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "view_subject.php?drop=" + id;
                }
            });
        }
    </script>
</body>
</html>
