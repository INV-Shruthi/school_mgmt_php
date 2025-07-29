<?php
session_start();

// Hardcoded credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin');

// Failed login tracking
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

if (!isset($_SESSION['blocked_until'])) {
    $_SESSION['blocked_until'] = 0;
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: ../dashboard.php");
    exit;
}

// Handle login form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (time() < $_SESSION['blocked_until']) {
        $error = "Too many failed attempts. Try again after 5 minutes.";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['blocked_until'] = 0;
            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['failed_attempts'] += 1;
            $error = "Invalid username or password.";

            if ($_SESSION['failed_attempts'] >= 3) {
                $_SESSION['blocked_until'] = time() + (5 * 60); // 5 minutes
                $error = "Too many failed attempts. Try again after 5 minutes.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - School Management System</title>
</head>
<body>
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
