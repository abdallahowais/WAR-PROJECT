<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "safe_pool");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $emergency = $_POST['emergency'];
    $stmt = $conn->prepare("INSERT INTO pools (name, location, status, emergency_contact) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $location, $status, $emergency);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Pool</title>
    <link rel="stylesheet" href="style.css">
</head>
  <style>
      
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 320px;
        }
        h2 {
            text-align: center;
            color: #0077b6;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            width: 100%;
            background: #0077b6;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #005f8e;
        }
    </style>
<body>
<header><h1>Safe Pool System</h1></header>
<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="add_pool.php">Add Pool</a>
    <a href="add_user.php">Add Pool Owner</a>
    <a href="logout.php">Logout</a>
</nav>
<div class="container">
<form method="post">
    <h2>Add New Pool</h2>
    <label>Pool Name:</label><input type="text" name="name" required>
    <label>Location:</label><input type="text" name="location" required>
    <label>Status:</label><input type="text" name="status" value="Active">
    <label>Emergency Contact:</label><input type="text" name="emergency">
    <input type="submit" value="Add Pool">
</form>
</div>
</body>
</html>