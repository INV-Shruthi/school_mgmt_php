<?php
session_start();
require_once '../config/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Step 1: Get current time and fetch attempt data (only 1 row expected)
    $current_time = new DateTime();
    $stmt = $conn->query("SELECT * FROM login_attempts LIMIT 1");
    $attemptData = $stmt->fetch_assoc();
    $blocked = false;
    $attempts = 0;

    if ($attemptData) {
        $last_attempt_time = new DateTime($attemptData['last_attempt']);
        $interval = $current_time->getTimestamp() - $last_attempt_time->getTimestamp();

        if ($attemptData['attempts'] >= 3 && $interval < 300) {
            $blocked = true;
        }
    }

    if ($blocked) {
        $message = "Too many failed attempts. Try again after 5 minutes.";
    } else {
        if ($username === 'admin' && $password === 'admin') {
            // Success
            $_SESSION['username'] = $username;
            $conn->query("DELETE FROM login_attempts");
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Failed login
            $attempts = ($attemptData) ? $attemptData['attempts'] + 1 : 1;

            if ($attemptData) {
                $stmt = $conn->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW() WHERE id = ?");
                $stmt->bind_param("ii", $attempts, $attemptData['id']);
            } else {
                $stmt = $conn->prepare("INSERT INTO login_attempts (attempts, last_attempt) VALUES (?, NOW())");
                $stmt->bind_param("i", $attempts);
            }

            $stmt->execute();

            if ($attempts >= 3) {
                $message = "Too many failed attempts. Try again after 5 minutes.";
            } else {
                $message = "Invalid credentials. Attempt $attempts of 3.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-bottom: 25px;
            text-align: center;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
            <?php if (!empty($message)): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
