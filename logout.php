<?php
session_start();
session_destroy();
echo "<script>
    alert('تم تسجيل الخروج بنجاح.');
    window.location.href = 'index.php';
</script>";
exit;
?>
