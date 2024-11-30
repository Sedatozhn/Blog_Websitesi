<?php
session_start();

// Oturumu sonlandır
session_destroy();

// Çerezi sil
setcookie("cerez", "", time() - 3600, "/");

// Yönlendirme yap
header("Location: login.php");
exit(); // Güvenlik için scriptin devam etmemesi için exit() kullanıyoruz
?>
