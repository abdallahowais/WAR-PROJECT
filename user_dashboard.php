<?php
session_start();

// تحقق من صلاحية الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "safe_pool");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// جلب معلومات المستخدم
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT pool_id FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$pool_id = $user_data['pool_id'];

// جلب معلومات المسبح
$pool_query = $conn->prepare("SELECT * FROM pools WHERE id = ?");
$pool_query->bind_param("i", $pool_id);
$pool_query->execute();
$pool_result = $pool_query->get_result();
$pool = $pool_result->fetch_assoc();

// جلب التنبيهات
$alerts_query = $conn->prepare("SELECT * FROM alerts WHERE pool_id = ? ORDER BY alert_time DESC LIMIT 5");
$alerts_query->bind_param("i", $pool_id);
$alerts_query->execute();
$alerts_result = $alerts_query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Pool</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome, Pool Owner!</h1>
        <p>Here is your pool status and recent alerts.</p>
    </header>

    <nav>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Pool Info</h2>
        <?php if ($pool): ?>
            <p><strong>Name:</strong> <?= htmlspecialchars($pool['name']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($pool['location']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($pool['status']) ?></p>
            <p><strong>Emergency Contact:</strong> <?= htmlspecialchars($pool['emergency_contact']) ?></p>
        <?php else: ?>
            <p>No pool assigned to your account.</p>
        <?php endif; ?>

        <h2>Recent Alerts</h2>
        <?php if ($alerts_result->num_rows > 0): ?>
            <ul>
                <?php while($alert = $alerts_result->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($alert['alert_time']) ?> - <?= htmlspecialchars($alert['alert_type']) ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No recent alerts.</p>
        <?php endif; ?>
    </div>
</body>
</html>
