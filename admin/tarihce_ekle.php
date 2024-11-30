<?php
$sayfa = isset($_GET['id']) ? "Tarihçe Düzenle" : "Tarihçe Ekle";
include('inc/ahead.php');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$tarih = '';
$baslik = '';
$icerik = '';
$mevcutFoto = '';

if ($id) {
    // Eğer ID belirtilmişse ve geçerliyse
    $sorgu = $baglanti->prepare("SELECT * FROM tarihce WHERE id = :id");
    $sorgu->execute(['id' => $id]);
    $tarihce = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$tarihce) {
        echo '<script>alert("Belirtilen tarihçe kaydı bulunamadı."); window.location.href = "tarihce.php";</script>';
        exit;
    }

    $tarih = $tarihce['tarih'];
    $baslik = $tarihce['baslik'];
    $icerik = $tarihce['icerik'];
    $mevcutFoto = $tarihce['foto'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST["tarih"];
    $baslik = $_POST["baslik"];
    $icerik = $_POST["icerik"];
    $foto = $mevcutFoto;

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $hedef_klasor = "../assets/img/about/";
        $hedef_dosya = $hedef_klasor . basename($_FILES["foto"]["name"]);
        $dosya_tipi = strtolower(pathinfo($hedef_dosya, PATHINFO_EXTENSION));

        // Dosya boyutunu kontrol et (2MB'tan küçük olmalı)
        if ($_FILES["foto"]["size"] > 2000000) {
            echo '<script>alert("Dosya boyutu 2MB\'tan büyük olamaz.");</script>';
        } elseif ($dosya_tipi != "jpg" && $dosya_tipi != "jpeg" && $dosya_tipi != "png") {
            echo '<script>alert("Sadece JPEG ve PNG dosyaları kabul edilir.");</script>';
        } elseif (!move_uploaded_file($_FILES["foto"]["tmp_name"], $hedef_dosya)) {
            echo '<script>alert("Dosya yükleme sırasında bir hata oluştu.");</script>';
        } else {
            // Eski fotoğrafı sil
            if ($mevcutFoto && file_exists($hedef_klasor . $mevcutFoto)) {
                unlink($hedef_klasor . $mevcutFoto);
            }
            $foto = $_FILES["foto"]["name"];
        }
    }

    if ($id) {
        // Güncelleme sorgusu
        $sorgu = $baglanti->prepare("UPDATE tarihce SET tarih = :tarih, baslik = :baslik, icerik = :icerik, foto = :foto WHERE id = :id");
        $sorgu->execute(['tarih' => $tarih, 'baslik' => $baslik, 'icerik' => $icerik, 'foto' => $foto, 'id' => $id]);
    } else {
        // Ekleme sorgusu
        $sorgu = $baglanti->prepare("INSERT INTO tarihce (tarih, baslik, icerik, foto) VALUES (:tarih, :baslik, :icerik, :foto)");
        $sorgu->execute(['tarih' => $tarih, 'baslik' => $baslik, 'icerik' => $icerik, 'foto' => $foto]);
    }

    echo '<script>alert("Kayıt başarıyla kaydedildi."); window.location.href = "tarihce.php";</script>';
    exit;
}
?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4"><?= $sayfa ?></h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item active"><?= $sayfa ?></li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tarih">Tarih</label>
                        <input type="date" id="tarih" name="tarih" class="form-control" value="<?= $tarih ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="baslik">Başlık</label>
                        <input type="text" id="baslik" name="baslik" class="form-control" value="<?= $baslik ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="icerik">İçerik</label>
                        <textarea id="icerik" name="icerik" class="form-control" rows="5" required><?= $icerik ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Fotoğraf</label>
                        <?php if ($mevcutFoto): ?>
                            <img src="../assets/img/about/<?= $mevcutFoto ?>" alt="<?= $baslik ?>" width="100">
                            <input type="hidden" name="mevcutFoto" value="<?= $mevcutFoto ?>">
                        <?php endif; ?>
                        <input type="file" id="foto" name="foto" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><?= $id ? 'Güncelle' : 'Ekle' ?></button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include('inc/afooter.php');
?>
