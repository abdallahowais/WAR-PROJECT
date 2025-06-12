<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "safe_pool");
$pools = $conn->query("SELECT * FROM pools");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pool_id = $_POST['pool_id'];
    $stmt = $conn->prepare("INSERT INTO users (username, password, user_type, pool_id) VALUES (?, ?, 'pool_owner', ?)");
    $stmt->bind_param("ssi", $username, $password, $pool_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Pool Owner</title>
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
    <h2>Add Pool Owner</h2>
    <label>Username:</label><input type="text" name="username" required>
    <label>Password:</label><input type="password" name="password" required>
    <label>Assign to Pool:</label>
    <select name="pool_id" required>
        <?php while ($pool = $pools->fetch_assoc()): ?>
            <option value="<?= $pool['id'] ?>"><?= htmlspecialchars($pool['name']) ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" value="Add Owner">
</form>
</div>
</body>
</html>