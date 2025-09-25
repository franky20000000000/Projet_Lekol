<?php
session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_email']);
session_write_close();
header('Location: AdminLogin.php?expired=1');
exit;
?>

