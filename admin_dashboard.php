<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "safe_pool");
$pools = $conn->query("SELECT * FROM pools");
$users = $conn->query("SELECT users.*, pools.name AS pool_name FROM users LEFT JOIN pools ON users.pool_id = pools.id WHERE users.user_type = 'pool_owner'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .tool-button {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }
        .tool-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<header><h1>Safe Pool System</h1></header>
<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="add_pool.php">Add Pool</a>
    <a href="add_user.php">Add Pool Owner</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>All Pools</h2>
    <ul>
        <?php while ($pool = $pools->fetch_assoc()): ?>
            <li><strong><?= htmlspecialchars($pool['name']) ?></strong> - <?= htmlspecialchars($pool['location']) ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Pool Owners</h2>
    <ul>
        <?php while ($user = $users->fetch_assoc()): ?>
            <li><?= htmlspecialchars($user['username']) ?> (Pool: <?= htmlspecialchars($user['pool_name'] ?? 'None') ?>)</li>
        <?php endwhile; ?>
    </ul>

    <h2>Tools</h2>
    <div class="tab-buttons">
        <a href="folcal.html" class="tool-button" target="_blank">أداة العزم</a>
        <a href="tafeo.html" class="tool-button" target="_blank">حساب الطفو</a>
        <a href="cal.html" class="tool-button" target="_blank">حاسبة البكرة والمحرك</a>
    </div>
</div>

</body>
</html>
