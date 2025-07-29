<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - School Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .button {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #007bff;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
        }
        .logout {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

        <a class="button" href="students/register.php">➕ Student Registration</a>
        <a class="button" href="students/list.php">📋 Student Listing</a>
        <a class="button logout" href="auth/logout.php">🚪 Logout</a>
    </div>
</body>
</html>
