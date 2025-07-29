<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - School Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .button {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            background-color: #e7f1ff;
            color: #0056b3;
            border: 1px solid #cce0ff;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .button:hover {
            background-color: #d6eaff;
        }

        .logout {
            background-color: #ffeaea;
            color: #b30000;
            border: 1px solid #ffc2c2;
        }

        .logout:hover {
            background-color: #ffd6d6;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

        <a class="button" href="students/register.php">Register Student</a>
        <a class="button" href="students/list.php">View Students</a>
        <a class="button logout" href="auth/logout.php">Logout</a>
    </div>
</body>
</html>
