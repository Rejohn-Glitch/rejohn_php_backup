<?php
session_start();
include("../includes/server.php");

if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Access denied! Student ID missing.'); window.location.href='../index.php';</script>";
    exit();
}

$student_id = $_SESSION['student_id'];


$query = "SELECT sub.subject_name, c.class_name, t.first_name AS teacher_first, t.last_name AS teacher_last, g.prelim
          FROM enrollments e
          JOIN teacher_subjects ts ON e.teacher_subject_id = ts.teacher_subject_id
          JOIN subjects sub ON ts.subject_id = sub.subject_id
          JOIN classes c ON ts.class_id = c.class_id
          JOIN teachers t ON ts.teacher_id = t.teacher_id
          LEFT JOIN grades g ON e.enrollment_id = g.enrollment_id
          WHERE e.student_id = ?";

          
$stmt = $con->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
      body {
          background-color: #690B22;
          font-family: 'Roboto', sans-serif;
          color: white;
      }
      
      .logout-btn {
          position: fixed;
          top: 10px;
          left: 10px;
      }
     
      .dashboard-container {
          margin-top: 80px;
          margin-left: auto;
          margin-right: auto;
          width: 90%;
      }
      .table-container {
          overflow-x: auto;
          background: white;
          color: black;
          border-radius: 5px;
          padding: 10px;
      }
      .table {
          table-layout: fixed;
          width: 100%;
          font-size: 0.9rem;
      }
      .table th, .table td {
          white-space: nowrap;
          text-align: center;
          padding: 10px;
      }
      .table th {
          background-color: #343a40;
          color: white;
      }
      @media (max-width: 768px) {
          .table { font-size: 0.8rem; }
      }
      @media (max-width: 480px) {
          .dashboard-container { width: 100%; padding: 5px; }\n          .table th, .table td { padding: 5px; font-size: 10px; }\n      }
  </style>
</head>
<body>
  <a href="logout.php" class="btn btn-danger logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
  
  <div class="dashboard-container">
      <h2 class="text-center mb-4">Student Dashboard</h2>
      <div class="card p-3">
          <div class="table-container table-responsive">
              <table class="table table-bordered table-striped">
                  <thead class="table-dark">
                      <tr>
                          <th style="width: 25%;">Subject</th>
                          <th style="width: 25%;">Class (Section)</th>
                          <th style="width: 30%;">Teacher</th>
                          <th style="width: 20%;">Prelim Score</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if ($result && $result->num_rows > 0): ?>
                          <?php while ($row = $result->fetch_assoc()): ?>
                              <tr>
                                  <td><?= $row['subject_name']; ?></td>
                                  <td><?= $row['class_name']; ?></td>
                                  <td><?= $row['teacher_first'] . " " . $row['teacher_last']; ?></td>
                                  <td><?= $row['prelim'] ?? 'N/A'; ?></td>
                              </tr>
                          <?php endwhile; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="4" class="text-center">No subjects found.</td>
                          </tr>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
