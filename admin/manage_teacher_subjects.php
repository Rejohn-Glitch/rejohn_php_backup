<?php
session_start();
include("../includes/server.php");
include("../includes/admin_sidebar.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$teacherSubjects = $con->query("SELECT ts.teacher_subject_id, c.class_name, s.subject_name, t.first_name, t.last_name, t.year_level 
                              FROM teacher_subjects ts
                              JOIN classes c ON ts.class_id = c.class_id
                              JOIN subjects s ON ts.subject_id = s.subject_id
                              JOIN teachers t ON ts.teacher_id = t.teacher_id");

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM teacher_subjects WHERE teacher_subject_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: ./manage_teacher_subjects.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Teacher Subjects</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
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
        }
        .table th,
        .table td {
            white-space: normal;
            word-wrap: break-word;
        }
        .table td:last-child {
            text-align: center;
            vertical-align: middle;
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
            .table th,
            .table td {
                padding: 5px;
                white-space: normal;
            }
        }
    </style>
</head>

<body>
    <div class="container manage_body mt-4">
        <h2 class="mb-3">Manage Teacher Subjects</h2>
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0">Teacher Subjects List</h5>
                <button class="btn btn-success" onclick="add_teacher_subject()">Add Teacher Subject</button>
            </div>
            <div class="table-container table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Year Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $teacherSubjects->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['class_name']; ?></td>
                                <td><?= $row['subject_name']; ?></td>
                                <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                <td><?= $row['year_level']; ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="delete_teacher_subject(<?= $row['teacher_subject_id'] ?>)">
                                        <i class="bi bi-trash"></i> Delete
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
        function add_teacher_subject() {
            window.location.href = "add_manage_teacher_subjects.php";
        }

        function delete_teacher_subject(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",    
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "manage_teacher_subjects.php?delete=" + id;
                }
            });
        }
    </script>
</body>
</html>
