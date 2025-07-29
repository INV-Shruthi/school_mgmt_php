<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $reg_no = trim($_POST['reg_no']);
    $age = $_POST['age'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = $_POST['course'];

    // Validation
    if (!preg_match("/^[a-zA-Z ]{2,}$/", $name)) {
        $errors[] = "Name must be at least 2 alphabetic characters.";
    }

    if (!preg_match("/^REG-\d{4}-\d{4}$/", $reg_no)) {
        $errors[] = "Registration number must be in format REG-YYYY-NNNN.";
    }

    if ($age < 18 || $age > 25) {
        $errors[] = "Age must be between 18 and 25.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    $allowed_courses = ['Physics', 'Chemistry', 'Maths', 'Biology', 'English'];
    if (!in_array($course, $allowed_courses)) {
        $errors[] = "Invalid course selected.";
    }

    // If no errors, save to database
    if (empty($errors)) {
        $conn = new mysqli("localhost", "root", "", "school_mgmt");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO students (name, reg_no, age, email, phone, course) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $name, $reg_no, $age, $email, $phone, $course);

        if ($stmt->execute()) {
            $success = "Student registered successfully! Redirecting to dashboard...";
            header("refresh:3;url=../dashboard.php");
        } else {
            $errors[] = "Error saving student: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Student</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 40px; }
        .box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
        }
        .error { color: red; }
        .success { color: green; }
        a { text-decoration: none; display: inline-block; margin-top: 15px; color: #007bff; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Register Student</h2>

        <?php
        foreach ($errors as $error) {
            echo "<p class='error'>* $error</p>";
        }
        if ($success) echo "<p class='success'>$success</p>";
        ?>

        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Registration Number:</label>
            <input type="text" name="reg_no" required placeholder="REG-2024-0001">

            <label>Age:</label>
            <input type="number" name="age" required min="18" max="25">

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Phone Number:</label>
            <input type="text" name="phone" required placeholder="10-digit mobile number">

            <label>Course:</label>
            <select name="course" required>
                <option value="">-- Select Course --</option>
                <option value="Physics">Physics</option>
                <option value="Chemistry">Chemistry</option>
                <option value="Maths">Maths</option>
                <option value="Biology">Biology</option>
                <option value="English">English</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <a href="../dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>
