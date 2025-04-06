<?php
include("../includes/server.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
    $stmt = $con->prepare("INSERT INTO subjects(subject_name) VALUES(?)");
    $stmt->bind_param("s", $subject);
    if ($stmt->execute()) {
        header("Location: ./manage_subjects.php");
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4 w-50 mx-auto">
            <h2 class="mb-4 text-center">Add Subject</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="subject" class="form-control" placeholder="Enter subject name" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>