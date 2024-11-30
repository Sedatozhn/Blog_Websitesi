<?php
$sayfa = "Referans Güncelle";
include('inc/ahead.php');

if ($_SESSION["yetki"] != "1") {
    echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
    echo "<script> Swal.fire({
        title: 'Hata!',
        text: 'Yetkisiz kullanıcı',
        icon: 'error',
        confirmButtonText: 'Kapat'
    }).then((value) => {
        if (value.isConfirmed) {
            window.location.href = 'index.php';
        }
    }); </script>";
    exit;
}

// Referans ID'sini alma
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $referansId = $_GET["id"];

    // Veritabanından referansı seçme
    $sorgu = $baglanti->prepare("SELECT * FROM referans WHERE id = :id");
    $sorgu->execute(['id' => $referansId]);
    $referans = $sorgu->fetch();

    if (!$referans) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Belirtilen referans bulunamadı.',
            icon: 'error',
            confirmButtonText: 'Kapat'
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = 'referanslar.php';
            }
        }); </script>";
        exit;
    }
} else {
    echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
    echo "<script> Swal.fire({
        title: 'Hata!',
        text: 'Referans ID'si belirtilmedi.',
        icon: 'error',
        confirmButtonText: 'Kapat'
    }).then((value) => {
        if (value.isConfirmed) {
            window.location.href = 'referanslar.php';
        }
    }); </script>";
    exit;
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tüm alanların dolu olup olmadığını kontrol et
    if (!isset($_POST["link"]) || empty(trim($_POST["link"])) ||
        !isset($_POST["sira"]) || empty(trim($_POST["sira"])) ||
        !isset($_POST["aktif"]) || !isset($_FILES["foto"]) || empty($_FILES["foto"]["name"])) {

        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Lütfen tüm alanları doldurun ve bir fotoğraf seçin.',
            icon: 'error',
            confirmButtonText: 'Kapat'
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = 'referanslar.php';
            }
        }); </script>";
        exit;
    }

    // Yeni bilgileri al
    $link = $_POST["link"];
    $sira = $_POST["sira"];
    $aktif = isset($_POST["aktif"]) ? 1 : 0;

    // Yeni fotoğraf yüklendi mi?
    $hedef_klasor = "../assets/img/logos/";
    $hedef_dosya = $hedef_klasor . basename($_FILES["foto"]["name"]);
    $dosya_tipi = strtolower(pathinfo($hedef_dosya, PATHINFO_EXTENSION));

    // Dosya boyutunu kontrol et (2MB'tan küçük olmalı)
    if ($_FILES["foto"]["size"] > 2000000) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Dosya boyutu 2MB'tan büyük olamaz.',
            icon: 'error',
            confirmButtonText: 'Kapat'
        }); </script>";
        exit;
    }

    // Geçerli dosya türleri (JPEG ve PNG)
    if ($dosya_tipi != "jpg" && $dosya_tipi != "png") {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Sadece JPEG ve PNG dosyaları kabul edilir.',
            icon: 'error',
            confirmButtonText: 'Kapat'
        }); </script>";
        exit;
    }

    // Dosyayı hedefe taşı
    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $hedef_dosya)) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Dosya yükleme sırasında bir hata oluştu.',
            icon: 'error',
            confirmButtonText: 'Kapat'
        }); </script>";
        exit;
    }

    // Eski dosyayı sil
    if (!empty($referans["foto"])) {
        $eski_dosya = $hedef_klasor . $referans["foto"];
        if (file_exists($eski_dosya)) {
            unlink($eski_dosya);
        }
    }

    // Yeni fotoğraf adını veritabanına kaydet
    $foto = $_FILES["foto"]["name"];

    // Veritabanında güncelleme işlemi
    $sorgu = $baglanti->prepare("UPDATE referans SET link = :link, sira = :sira, aktif = :aktif, foto = :foto WHERE id = :id");
    $sonuc = $sorgu->execute([
        'link' => $link,
        'sira' => $sira,
        'aktif' => $aktif,
        'foto' => $foto,
        'id' => $referansId
    ]);

    if ($sonuc) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Başarılı!',
            text: 'Referans güncelleme işlemi başarıyla tamamlandı.',
            icon: 'success',
            confirmButtonText: 'Tamam'
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = 'referanslar.php';
            }
        }); </script>";
    } else {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Referans güncelleme sırasında bir hata oluştu.',
            icon: 'error',
            confirmButtonText: 'Tamam'
        }); </script>";
    }
}
?>


<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="mt-4">Referans Güncelle</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                    <li class="breadcrumb-item active">Referans Güncelle</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="referansForm" method="POST" enctype="multipart/form-data">
                            <div style="width: 80%;">
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    <input type="text" class="form-control" id="link" name="link" value="<?= htmlspecialchars($referans["link"]) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" class="form-control" id="sira" name="sira" value="<?= htmlspecialchars($referans["sira"]) ?>" required>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="aktif" name="aktif" value="1" <?= $referans["aktif"] == 1 ? "checked" : "" ?>>
                                    <label class="form-check-label" for="aktif">Aktif</label>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="foto">Fotoğraf</label>
                                    <input type="file" class="form-control-file" id="foto" name="foto" accept=".jpg, .jpeg, .png">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Kaydet</button>
                                <a href="referanslar.php" class="btn btn-secondary mt-3">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Form submit edildiğinde dosya uzantısını kontrol et
    document.getElementById('referansForm').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('foto');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

        if (!allowedExtensions.exec(filePath)) {
            event.preventDefault();
            Swal.fire({
                title: 'Hata!',
                text: 'Sadece JPEG ve PNG dosyaları kabul edilir.',
                icon: 'error',
                confirmButtonText: 'Kapat'
            });
        }
    });
</script>

<?php
include('inc/afooter.php');
?>

