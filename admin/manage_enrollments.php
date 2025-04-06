<?php
session_start();
include("../includes/server.php");
include("../includes/admin_sidebar.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$students = $con->query("SELECT student_id, first_name, last_name, email FROM students");


$enrollments = $con->query("SELECT e.enrollment_id, st.first_name, st.last_name, st.year_level AS student_level, s.subject_name, c.class_name, 
                                   t.first_name AS teacher_first, t.last_name AS teacher_last
                            FROM enrollments e
                            JOIN students st ON e.student_id = st.student_id
                            JOIN teacher_subjects ts ON e.teacher_subject_id = ts.teacher_subject_id
                            JOIN subjects s ON ts.subject_id = s.subject_id
                            JOIN classes c ON ts.class_id = c.class_id
                            JOIN teachers t ON ts.teacher_id = t.teacher_id");

if (isset($_GET['unenroll'])) {
    $enrollment_id = $_GET['unenroll'];
    $stmt = $con->prepare("DELETE FROM enrollments WHERE enrollment_id = ?");
    $stmt->bind_param("i", $enrollment_id);
    if ($stmt->execute()) {
        header("Location: manage_enrollments.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
        }
    </style>
</head>
<body>
    <div class="container manage_body mt-4">
        <h2 class="mb-3">Manage Enrollments</h2>
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0">Enrolled Students</h5>
                <button class="btn btn-success" onclick="add_enrollment()">Add Enrollment</button>
            </div>
            <div class="table-container table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Student</th>
                            <th>Student Level</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $enrollments->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                <td><?= $row['student_level']; ?></td>
                                <td><?= $row['class_name']; ?></td>
                                <td><?= $row['subject_name']; ?></td>
                                <td><?= $row['teacher_first'] . " " . $row['teacher_last']; ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="unenroll(<?= $row['enrollment_id'] ?>)">
                                        <i class="bi bi-trash"></i> Unenroll
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
    <script>
        function add_enrollment() {
            window.location.href = "add_student_enrollment.php";
        }

        function unenroll(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will remove the student from the subject!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, unenroll!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "manage_enrollments.php?unenroll=" + id;
                }
            });
        }
    </script>
</body>
</html>
