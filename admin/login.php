<?php
session_start();
include("../inc/db.php");

// Oturum kontrolü
if (isset($_SESSION["oturum"]) && $_SESSION["oturum"] == "6789") {
    header("Location: index.php");
    exit(); // Yönlendirmeden sonra scriptin çalışmasını durdurur
}

// Çerez kontrolü
if (isset($_COOKIE["cerez"])) {
    $sorgu = $baglanti->prepare("SELECT kadi, yetki FROM kullanici WHERE aktif=1");
    $sorgu->execute();
    while ($sonuc = $sorgu->fetch()) {
        if ($_COOKIE["cerez"] == md5("aa" . $sonuc["kadi"] . "bb")) {
            $_SESSION["oturum"] = "6789";
            $_SESSION["kadi"] = $sonuc["kadi"];
            $_SESSION["yetki"] = $sonuc["yetki"];
            header("Location: index.php");
            exit();
        }
    }
}

// Form gönderildiyse
if ($_POST) {
    $kadi = $_POST["txtKadi"];
    $parola = $_POST["txtParola"];

    // Kullanıcı adı ve parola kontrolü
    $sorgu = $baglanti->prepare("SELECT parola, yetki FROM kullanici WHERE kadi=:kadi AND aktif=1");
    $sorgu->execute(['kadi' => htmlspecialchars($kadi)]);
    $sonuc = $sorgu->fetch();

    if (md5("56" . $parola . "23") == $sonuc['parola']) {
        $_SESSION["oturum"] = "6789";
        $_SESSION["kadi"] = $kadi;
        $_SESSION["yetki"] = $sonuc["yetki"];

        if (isset($_POST["cbHatirla"])) {
            setcookie("cerez", md5("aa" . $kadi . "bb"), time() + (60 * 60 * 24 * 7));
        }

        header("Location: index.php");
        exit();
    } else {
        echo "<script>Swal.fire('HATALI!!', 'Kullanıcı adı veya parola hatalı', 'error')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Giriş Sayfası - SB Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Kullanıcı Girişi</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Form -->
                                    <form method="post" action="login.php">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Kullanıcı Adı</label>
                                            <input class="form-control py-4" id="inputEmailAddress" name="txtKadi" value="<?= isset($kadi) ? htmlspecialchars($kadi) : '' ?>" type="text" placeholder="Kullanıcı adı giriniz" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Parola</label>
                                            <input class="form-control py-4" id="inputPassword" name="txtParola" type="password" placeholder="Parola Giriniz" />
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" name="cbHatirla" />
                                                <label class="custom-control-label" for="rememberPasswordCheck">Beni Hatırla</label>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="password.html">Parolanızı mı unuttunuz?</a>
                                            <input type="submit" class="btn btn-primary" value="Giriş">
                                        </div>
                                    </form>
                                    <script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="register.html">Hesabınız yok mu? Kayıt olun!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Telif Hakkı &copy; Siteniz 2020</div>
                        <div>
                            <a href="#">Gizlilik Politikası</a>
                            &middot;
                            <a href="#">Şartlar &amp; Koşullar</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
