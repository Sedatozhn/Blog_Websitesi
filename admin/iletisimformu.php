<?php
// Veritabanı bağlantısı
include('inc/ahead.php'); // db.php dosyanızın adı ve yolunu doğru olarak belirtmelisiniz.

// Yetkilendirme kontrolü
session_start();
if (!isset($_SESSION["yetki"]) || $_SESSION["yetki"] != "1") {
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

// İletişim mesajlarını veritabanından al
$sorgu = $baglanti->prepare("SELECT id, email, mesaj, tarih FROM iletisimformu ORDER BY tarih DESC");
$sorgu->execute();
$iletisim_mesajlari = $sorgu->fetchAll();

// Eğer silme işlemi yapılacaksa
if (isset($_GET['sil_id'])) {
    $sil_id = $_GET['sil_id'];

    $sil_sorgu = $baglanti->prepare("DELETE FROM iletisimformu WHERE id = :id");
    $sonuc = $sil_sorgu->execute(['id' => $sil_id]);

    if ($sonuc) {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Başarılı!',
            text: 'İletişim mesajı başarıyla silindi.',
            icon: 'success',
            confirmButtonText: 'Tamam'
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = 'iiletisimformu.php';
            }
        }); </script>";
    } else {
        echo '<script type="text/javascript" src="../js/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({
            title: 'Hata!',
            text: 'İletişim mesajı silinirken bir hata oluştu.',
            icon: 'error',
            confirmButtonText: 'Tamam'
        }); </script>";
    }
}
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">İletişim Mesajları</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active">İletişim Mesajları</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Mesaj</th>
                                <th>Tarih</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($iletisim_mesajlari as $mesaj): ?>
                                <tr>
                                    <td><?= $mesaj['id'] ?></td>
                                    <td><?= $mesaj['email'] ?></td>
                                    <td><?= htmlspecialchars($mesaj['mesaj']) ?></td>
                                    <td><?= $mesaj['tarih'] ?></td>
                                    <td>
                                        <a href="?sil_id=<?= $mesaj['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">Sil</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include('inc/afooter.php');
?>
