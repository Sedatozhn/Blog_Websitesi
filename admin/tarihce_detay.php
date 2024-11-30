<?php
$sayfa = "Tarihçe Detay";
include('inc/ahead.php');

// Eğer ID belirtilmişse ve geçerliyse
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $tarihceId = $_GET['id'];

    // Tarihçe kaydını seçme sorgusu
    $sorgu = $baglanti->prepare("SELECT * FROM tarihce WHERE id = :id");
    $sorgu->execute(['id' => $tarihceId]);
    $tarihce = $sorgu->fetch(PDO::FETCH_ASSOC);

    // Eğer tarihçe kaydı bulunamazsa
    if (!$tarihce) {
        echo '<script>alert("Belirtilen tarihçe kaydı bulunamadı."); window.location.href = "tarihce.php";</script>';
        exit;
    }
} else {
    // Eğer ID belirtilmemişse veya geçersizse
    echo '<script>alert("Geçersiz tarihçe kaydı ID."); window.location.href = "tarihce.php";</script>';
    exit;
}
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Tarihçe Detay</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active">Tarihçe Detay</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <h3>Başlık: <?= $tarihce['baslik'] ?></h3>
                <p><strong>Tarih:</strong> <?= $tarihce['tarih'] ?></p>
                <p><strong>İçerik:</strong></p>
                <p><?= nl2br($tarihce['icerik']) ?></p>
                <p><strong>Fotoğraf:</strong></p>
                <img src="../assets/img/about/<?= $tarihce['foto'] ?>" alt="<?= $tarihce['baslik'] ?>" width="200">
                <a href="tarihce.php" class="btn btn-secondary mt-3">Geri Dön</a>
            </div>
        </div>
    </div>
</main>

<?php
include('inc/afooter.php');
?>
