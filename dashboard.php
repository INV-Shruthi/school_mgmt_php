<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<h2>Welcome Admin!</h2>
<ul>
    <li><a href="students/register.php">Register Student</a></li>
    <li><a href="students/list.php">View Students</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
