<?php
$sayfa = isset($_GET['id']) ? "Kullanıcı Düzenle" : "Kullanıcı Ekle";
include('inc/ahead.php');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$kadi = '';
$parola = '';
$yetki = '';
$email = '';
$aktif = '';

if ($id) {
    // Eğer ID belirtilmişse ve geçerliyse
    $sorgu = $baglanti->prepare("SELECT * FROM kullanici WHERE id = :id");
    $sorgu->execute(['id' => $id]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$kullanici) {
        echo '<script>alert("Belirtilen kullanıcı kaydı bulunamadı."); window.location.href = "kullanicilar.php";</script>';
        exit;
    }

    $kadi = $kullanici['kadi'];
    $parola = $kullanici['parola'];
    $yetki = $kullanici['yetki'];
    $email = $kullanici['email'];
    $aktif = $kullanici['aktif'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kadi = $_POST["kadi"];
    $parola = $_POST["parola"];
    $yetki = $_POST["yetki"];
    $email = $_POST["email"];
    $aktif = isset($_POST["aktif"]) ? 1 : 0;

    if ($id) {
        // Güncelleme sorgusu
        $sorgu = $baglanti->prepare("UPDATE kullanici SET kadi = :kadi, parola = :parola, yetki = :yetki, email = :email, aktif = :aktif WHERE id = :id");
        $sorgu->execute(['kadi' => $kadi, 'parola' => $parola, 'yetki' => $yetki, 'email' => $email, 'aktif' => $aktif, 'id' => $id]);
    } else {
        // Ekleme sorgusu
        $sorgu = $baglanti->prepare("INSERT INTO kullanici (kadi, parola, yetki, email, aktif) VALUES (:kadi, :parola, :yetki, :email, :aktif)");
        $sorgu->execute(['kadi' => $kadi, 'parola' => $parola, 'yetki' => $yetki, 'email' => $email, 'aktif' => $aktif]);
    }

    echo '<script>alert("Kayıt başarıyla kaydedildi."); window.location.href = "kullanicilar.php";</script>';
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
                <form method="post">
                    <div class="form-group">
                        <label for="kadi">Kullanıcı Adı</label>
                        <input type="text" id="kadi" name="kadi" class="form-control" value="<?= $kadi ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="parola">Parola</label>
                        <input type="password" id="parola" name="parola" class="form-control" value="<?= $parola ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="yetki">Yetki</label>
                        <select id="yetki" name="yetki" class="form-control" required>
                            <option value="1" <?= $yetki == 1 ? 'selected' : '' ?>>Admin</option>
                            <option value="0" <?= $yetki == 0 ? 'selected' : '' ?>>Kullanıcı</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="aktif" name="aktif" class="form-check-input" value="1" <?= $aktif == 1 ? 'checked' : '' ?>>
                        <label for="aktif" class="form-check-label">Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3"><?= $id ? 'Güncelle' : 'Ekle' ?></button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include('inc/afooter.php');
?>
