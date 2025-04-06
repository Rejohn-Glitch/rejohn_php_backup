<?php
session_start();
include("includes/server.php"); // Make sure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkQuery = "SELECT * FROM admins WHERE email = ?";
    $stmt = $con->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='admin_register.php';</script>";
        exit();
    }
    $stmt->close();

    // Insert new admin
    $query = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Admin registered successfully!'); window.location.href='admin_login.php';</script>";
    } else {
        echo "<script>alert('Error registering admin. Try again!'); window.location.href='admin_register.php';</script>";
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Registration</title>
    <style>
        body {
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Admin Registration</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</body>
</html>
