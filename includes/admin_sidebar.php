<?php


include("server.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Responsive Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <style>
    .sidebar {
      width: 287px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      padding-top: 20px;
     
    }

    .nav-item a {
      display: block;
      padding: 10px 20px;
      color: white;
      text-decoration: none;
      transition: all 0.3s ease-in-out;
      border-radius: 5px;
      margin-top: 10px;
    }




    .nav-item a:hover {
      background-color: #9C1030 !important;
      color: #FFD700 !important;
      transform: translateX(5px);
      box-shadow: 2px 2px 8px rgba(255, 215, 0, 0.3);
    }



    .sidebar img {
      display: block;
      margin: 0 auto 10px;
      width: 80px;
      border-radius: 8px;
    }





    @media (max-width: 1011x) {
      .sidebar {
        width: 20%;
        height: auto;
        position: relative;
      }

      .offcanvas {
        width: 10px !important;
      }
    }


    @media (min-width: 992px) {
      .content {
        margin-left: 250px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-dark bg-dark d-lg-none">
    <button class="btn btn-dark menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      ☰
    </button>
  </nav>


  <div class="offcanvas-lg offcanvas-start sidebar text-bg-dark p-3" id="sidebarMenu">
    <div class="text-center mb-3">
      <img src="../images/Nexuslogo.jpeg" alt="Xavier College" style="width: 100px; height: auto;">
    </div>

    <span class="text-center d-block fw-bold fs-5 mb-3">Nexus College</span>

    <div class="offcanvas-body mt-5">
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="../admin/dashboard.php"><i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="../admin/manage_enrollments.php"><i class="fas fa-file fa-fw me-2"></i>Students Enrollment</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="../admin/manage_students.php"><i class="fas fa-user-graduate fa-fw me-2"></i> Manage Students</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="../admin/manage_teacher_subjects.php"><i class="fas fa-chalkboard-teacher fa-fw me-2"></i> Manage Teacher Subject</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link  text-white" href="../admin/manage_subjects.php"><i class="fas fa-book fa-fw me-2"></i> Manage Subjects</a>
        </li>
        <li class="nav-item mb-3">
          <a class="nav-link text-white" href="../admin/manage_teachers.php"><i class="fas fa-user-tie fa-fw me-2"></i> Manage Teachers</a>
        </li>
        <li class="nav-item mb-3">
          <a class="nav-link text-white" href="../admin/logout.php"><i class="fas fa-sign-out-alt fa-lg fa-rotate-180 fa-fw me-1"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>