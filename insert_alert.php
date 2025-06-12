<?php
// الاتصال بقاعدة البيانات (عدّل معلومات الاتصال حسب إعداداتك)
$servername = "localhost";
$username = "root";
$password = ""; // عادة فارغ في XAMPP
$dbname = "safe_pool"; // <-- عدّل هنا

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// فحص الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استلام البيانات من ESP
$alert_type = $_POST['alert_type'];
$pool_id = $_POST['pool_id'];

// التحقق من صحة البيانات
$valid_alerts = ['drowning_detected', 'fall_detected'];
if (!in_array($alert_type, $valid_alerts)) {
    die("Invalid alert type");
}

// إدخال البيانات في جدول alerts
$stmt = $conn->prepare("INSERT INTO alerts (pool_id, alert_time, alert_type) VALUES (?, NOW(), ?)");
$stmt->bind_param("is", $pool_id, $alert_type);

if ($stmt->execute()) {
    echo "Alert inserted successfully";
} else {
    echo "Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
