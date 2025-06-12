<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['password'])) {
        $conn = new mysqli("localhost", "root", "", "safe_pool");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username']; // تأكد من هذا السطر
                $_SESSION['user_type'] = $user['user_type'];

                if ($user['user_type'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit;
            } else {
                echo "Wrong password.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "Please fill in all fields.";
    }
} else {
    echo "Access denied.";
}
?>
