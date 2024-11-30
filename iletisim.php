<?php 
$sayfa = "İletişim";
include("inc/db.php");
$tanimlama = "iletişim sayfası";
$key = "iletişim";
include("inc/head.php");
?>

<!-- Contact -->
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase mt-3">İLETİŞİM</h2>
            <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
        <form id="contactForm" name="sentMessage" method="post" action="">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="name" name="txtAd" type="text" placeholder="Adınız Soyadınız *" required="required" data-validation-required-message="Please enter your name." />
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="email" type="email" name="txtEmail" placeholder="Email adresiniz *" required="required" data-validation-required-message="Please enter your email address." />
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea class="form-control" id="message" name="txtMesaj" placeholder="Mesajınız *" required="required" data-validation-required-message="Please enter a message."></textarea>
                        <p class="help-block text-danger"></p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div id="success"></div>
                <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">GÖNDER</button>
            </div>
        </form>
        <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ad = htmlspecialchars($_POST['txtAd']);
            $email = htmlspecialchars($_POST['txtEmail']);
            $mesaj = htmlspecialchars($_POST['txtMesaj']);

            $sorgu = $baglanti->prepare("INSERT INTO iletisimformu SET ad=:ad, email=:email, mesaj=:mesaj");
            $ekle = $sorgu->execute([
                'ad' => $ad,
                'email' => $email,
                'mesaj' => $mesaj,
            ]);

            if ($ekle) {
                echo "<script>Swal.fire({tittle:'BAŞARILI', text:'Mesajınız Bize Ulaştı', icon:'success', confirmButtonText:'KAPAT'})</script>";
            } else {
                echo "<script>Swal.fire('HATALI!!','Tüm alanaları doğru doldurun','error')</script>";
            }
        }
        ?>
    </div>
</section>
<?php 
include("inc/footer.php");
?>
