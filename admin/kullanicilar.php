<?php
$sayfa = "Kullanıcı Yönetimi";
include('inc/ahead.php');

// Silme işlemi
if (isset($_GET['sil']) && !empty($_GET['sil'])) {
    $sorgu = $baglanti->prepare("DELETE FROM kullanici WHERE id = :id");
    $silmeSonuc = $sorgu->execute(['id' => $_GET['sil']]);
    if ($silmeSonuc) {
        echo '<script>alert("Kullanıcı başarıyla silindi."); window.location.href = "kullanicilar.php";</script>';
    } else {
        echo '<script>alert("Silme işlemi sırasında bir hata oluştu."); window.location.href = "kullanicilar.php";</script>';
    }
}

// Kullanıcı verilerini çekme
$sorgu = $baglanti->prepare("SELECT id, kadi, yetki, email, aktif FROM kullanici");
$sorgu->execute();
$kullaniciListesi = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Kullanıcı Yönetimi</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active">Kullanıcı Yönetimi</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <a href="kullanici_ekle.php" class="btn btn-primary mb-3">Yeni Kullanıcı Ekle</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kullanıcı Adı</th>
                                <th>Yetki</th>
                                <th>Email</th>
                                <th>Aktif</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kullaniciListesi as $kullanici): ?>
                                <tr>
                                    <td><?= $kullanici['id'] ?></td>
                                    <td><?= $kullanici['kadi'] ?></td>
                                    <td><?= $kullanici['yetki'] == 1 ? 'Admin' : 'Kullanıcı' ?></td>
                                    <td><?= $kullanici['email'] ?></td>
                                    <td><?= $kullanici['aktif'] == 1 ? 'Aktif' : 'Pasif' ?></td>
                                    <td>
                                        <a href="kullanici_ekle.php?id=<?= $kullanici['id'] ?>" class="btn btn-warning">Düzenle</a>
                                        <a href="kullanicilar.php?sil=<?= $kullanici['id'] ?>" class="btn btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">Sil</a>
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
