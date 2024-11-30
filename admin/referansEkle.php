<?php
$sayfa = "Referanslar Ekle";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadsDirectory = '../assets/img/logos/';
    $fileName = $_FILES["foto"]["name"];
    $fileType = $_FILES["foto"]["type"];
    $fileSize = $_FILES["foto"]["size"];
    $fileTmpName = $_FILES["foto"]["tmp_name"];

    // Dosya türü kontrolü
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($fileType, $allowedTypes)) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Dosya türü yalnızca JPEG veya PNG olabilir.',
            icon: 'error',
            confirmButtonText: 'Tamam'
        }); </script>";
        exit;
    }

    // Dosya boyutu kontrolü (2MB = 2 * 1024 * 1024 byte)
    $maxFileSize = 2 * 1024 * 1024; // 2MB
    if ($fileSize > $maxFileSize) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Dosya boyutu 2MB'dan büyük olamaz.',
            icon: 'error',
            confirmButtonText: 'Tamam'
        }); </script>";
        exit;
    }

    // Dosya adının benzersiz olmasını sağlama
    $uniqueFileName = uniqid() . '_' . $fileName;

    // Dosya yükleme işlemi
    $uploadFile = $uploadsDirectory . $uniqueFileName;

    // Dosya adının var olup olmadığını kontrol etme
    if (file_exists($uploadFile)) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Aynı dosya zaten mevcut.',
            icon: 'error',
            confirmButtonText: 'Tamam'
        }); </script>";
        exit;
    }

    if (move_uploaded_file($fileTmpName, $uploadFile)) {
        // Veritabanına kayıt işlemi
        $link = $_POST["link"];
        $sira = $_POST["sira"];
        $aktif = isset($_POST["aktif"]) ? 1 : 0;

        $sorgu = $baglanti->prepare("INSERT INTO referans (foto, link, sira, aktif) VALUES (:foto, :link, :sira, :aktif)");
        $sonuc = $sorgu->execute([
            'foto' => $uniqueFileName,
            'link' => $link,
            'sira' => $sira,
            'aktif' => $aktif
        ]);

        if ($sonuc) {
            echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
            echo "<script> Swal.fire({
                title: 'Başarılı!',
                text: 'Referans ekleme işlemi başarıyla tamamlandı.',
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
                text: 'Veritabanına kayıt sırasında bir hata oluştu.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            }); </script>";
        }
    } else {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'Dosya yükleme sırasında bir hata oluştu.',
            icon: 'error',
            confirmButtonText: 'Tamam'
        }); </script>";
    }
}
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Referanslar Ekle</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active">Referanslar Ekle</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="foto">Foto (Logo)</label>
                        <input type="file" class="form-control-file" id="foto" name="foto" accept="image/jpeg, image/png" required>
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
                        <input type="text" class="form-control" id="link" name="link" required>
                    </div>
                    <div class="form-group">
                        <label for="sira">Sıra</label>
                        <input type="number" class="form-control" id="sira" name="sira" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="aktif" name="aktif" value="1">
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include('inc/afooter.php');
?>
