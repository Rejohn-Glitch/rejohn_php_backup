<?php
session_start();
include("includes/server.php");

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "admin") {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] == "teacher") {
        $_SESSION['teacher_id'] = $user_id; 
        $_SESSION['role'] = "teacher"; 
        header("Location: teacher/dashboard.php");
        exit();
    } elseif ($_SESSION['role'] == "student") {
        $_SESSION['student_id'] = $user_id; 
        $_SESSION['role'] = "student"; 
        header("Location: students/dashboard.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $role = $_POST['role']; 

    if ($role == "admin") {
        $query = "SELECT admin_id, password FROM admins WHERE email = ?";
    } elseif ($role == "teacher") {
        $query = "SELECT teacher_id, password FROM teachers WHERE email = ?";
    } elseif ($role == "student") {
        $query = "SELECT student_id, password FROM students WHERE email = ?";
    } else {
        echo "<script>alert('Invalid role selected!'); window.location.href='index.php';</script>";
        exit();
    }

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hash);
    $stmt->fetch();
    $stmt->close();

    if ($user_id && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

        if ($role == "admin") {
            header("Location: admin/dashboard.php");
        } elseif ($role == "teacher") {
            $_SESSION['teacher_id'] = $user_id;
            header("Location: teacher/dashboard.php");
            exit();
        } else {
            $_SESSION['student_id'] = $user_id;
            header("Location: students/dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Incorrect email or password!'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: url('images/Nexuslogo.jpeg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            filter: blur(5px);
            z-index: -1;
            opacity: 0.9;
        }
        .container-box {
            border: 1px solid #E1EACD;
            padding: 20px;
            max-width: 43rem;
            margin: auto;
            background-color: #690B22;
            color: #690B22;
            width: 70rem;
            border-radius: 10px;
        }
        .nexuslogo {
            width: 300px;
            height: auto;
        }
        .input-box {
            position: relative;
            left: 40px;
            height: 45px;
            max-width: 20rem;
        }
        .button {
            position: relative;
            top: -10px;
            width: 20rem;
        }
        button {
            position: relative;
            top: 10px;
            left: 100px;
        }
        .teacher {
            position: relative;
            top: 2px;
            left: 40px;
            width: 20rem;
            height: 45px;
            color: white !important;
            text-align: center;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: 0;
        }
        
        @media (max-width: 991px) {
            .container-box {
                width: 90% !important;
                max-width: 90% !important;
                 
            }
            .input-box {
                max-width: 90% !important;
                
            }
        }
        @media (max-width: 768px) {
            .container-box {
                width: 100% !important;
                max-width: 100% !important;
                padding: 1.5rem !important;
            }
            .container-box.row {
                flex-direction: column;
            }
            .input-box {
                left: 0 !important;
                bottom: 0 !important;
                max-width: 100% !important;
            }
            .button,
            button {
                width: 100% !important;
                left: 0 !important;
                
            }
        }
    </style>
</head>
<body class="container d-flex align-items-center justify-content-center vh-100">
    <div class="last-part">
        <div class="container-box row bg-light p-4 shadow">
            <div class="col-md-5 text-center">
                <div class="nexuslogo mx-auto">
                    <img src="./images/Nexus.png" class="img-fluid" alt="Nexus Ignite College Logo">
                </div>
            </div>
            <div class="col-md-7 d-flex align-items-center">
                <form class="w-100" method="POST">
                    <div class="input-box">
                        <h4 class="mb-3">Already have an account?</h4>
                    </div>
                    <div class="mb-3 input-group input-box">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" required pattern="[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+">
                    </div>
                    <div class="mb-3 input-group input-box">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-2 input-group input-box">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <select class="form-control" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <input type="checkbox" id="rememberMe" name="remember">
                        <label for="rememberMe" class="ms-1">Remember me</label>
                    </div>
                    <input type="submit" class="btn btn-primary input-box button" value="Login">
                    <div class="mb-3 input-group input-box forgot ">
                        <a href="#" onclick="forgot_password()"><b>Forgot email or password?</b></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function forgot_password() {
        alert("Maintenance please try again later...");
        window.location.href = "register.php";
    }

    function teacher_register() {
        window.location.href = "register.php";
    }
</script>