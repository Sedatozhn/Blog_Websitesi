<?php
$sayfa = "Tarihçe Yönetimi";
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

// Silme işlemi
if (isset($_GET['sil']) && !empty($_GET['sil'])) {
    $sorgu = $baglanti->prepare("SELECT foto FROM tarihce WHERE id = :id");
    $sorgu->execute(['id' => $_GET['sil']]);
    $foto = $sorgu->fetch(PDO::FETCH_ASSOC)['foto'];

    if ($foto && file_exists("../assets/img/about/$foto")) {
        unlink("../assets/img/about/$foto");
    }

    $sorgu = $baglanti->prepare("DELETE FROM tarihce WHERE id = :id");
    $silmeSonuc = $sorgu->execute(['id' => $_GET['sil']]);
    if ($silmeSonuc) {
        echo '<script>alert("Kayıt başarıyla silindi."); window.location.href = "tarihce.php";</script>';
    } else {
        echo '<script>alert("Silme işlemi sırasında bir hata oluştu."); window.location.href = "tarihce.php";</script>';
    }
}

// Tarihçe verilerini çekme
$sorgu = $baglanti->prepare("SELECT id, tarih, baslik, icerik, foto FROM tarihce");
$sorgu->execute();
$tarihceListesi = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Tarihçe Yönetimi</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active">Tarihçe Yönetimi</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <a href="tarihce_ekle.php" class="btn btn-primary mb-3">Yeni Ekle</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tarih</th>
                                <th>Başlık</th>
                                <th>İçerik</th>
                                <th>Fotoğraf</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tarihceListesi as $tarihce): ?>
                                <tr>
                                    <td><?= $tarihce['id'] ?></td>
                                    <td><?= $tarihce['tarih'] ?></td>
                                    <td><?= $tarihce['baslik'] ?></td>
                                    <td><?= substr($tarihce['icerik'], 0, 100) . '...' ?></td>
                                    <td><img src="../assets/img/about/<?= $tarihce['foto'] ?>" alt="<?= $tarihce['baslik'] ?>" width="100"></td>
                                    <td>
                                        <a href="tarihce_detay.php?id=<?= $tarihce['id'] ?>" class="btn btn-info">Detay</a>
                                        <a href="tarihce_ekle.php?id=<?= $tarihce['id'] ?>" class="btn btn-warning">Düzenle</a>
                                        <a href="tarihce.php?sil=<?= $tarihce['id'] ?>" class="btn btn-danger" onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')">Sil</a>
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
