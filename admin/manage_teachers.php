<?php
session_start();
include("../includes/server.php");
include("../includes/admin_sidebar.php");


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}


$teachers = $con->query("SELECT teacher_id, first_name, last_name, email, year_level FROM teachers");

if (isset($_GET['delete'])) {
  $teacher_id = $_GET['delete'];
  $stmt = $con->prepare("DELETE FROM teachers WHERE teacher_id = ?");
  $stmt->bind_param("i", $teacher_id);
  if ($stmt->execute()) {
    header("Location: ./manage_teachers.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Teachers</title>
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
    <h2 class="mb-3">Manage Teachers</h2>
    <div class="card p-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="m-0">Teachers List</h5>
        <button class="btn btn-success" onclick="add_teacher()">Add Teacher</button>
      </div>
      <div class="table-container table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Year Level</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $teachers->fetch_assoc()) : ?>
              <tr>
                <td><?= $row['teacher_id']; ?></td>
                <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['year_level']; ?></td>
                <td>
                  <a href="edit_teacher.php?id=<?= $row['teacher_id']; ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <button class="btn btn-danger btn-sm" onclick="delete_teacher(<?= $row['teacher_id'] ?>)">
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
    function add_teacher() {
      window.location.href = "add_teachers.php";
    }

    function delete_teacher(id) {
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
          window.location.href = "manage_teachers.php?delete=" + id;
        }
      });
    }
  </script>
</body>

</html>
