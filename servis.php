<?php 
$sayfa="Servis";
include("inc/db.php");
$sorgu=$baglanti-> prepare("select * from servis");
$sorgu->execute();
$sonuc=$sorgu->fetch();
include("inc/head.php");


    ?>

        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase mt-3"><?php echo $sonuc["baslik"] ?></h2>
                    <h3 class="section-subheading text-muted"><?php echo $sonuc["altBaslik"] ?></h3>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">
                        Kurs İmkanları</h4>
                        <p class="text-muted">Yazılımcılık, coğrafi sınırları aşan bir meslektir. İnternetin yaygın kullanımı ve küreselleşme ile birlikte, yazılımcılar dünya genelinde iş fırsatlarına erişebilirler. Farklı ülkelerdeki şirketlerle uzaktan çalışabilir veya yurtdışında çalışma imkanı bulabilirler.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">
                        Esneklik ve Özgürlük</h4>
                        <p class="text-muted">Yazılımcılar, kod yazarak ve teknolojiyi kullanarak çeşitli problemleri çözebilme yeteneğine sahiptirler. Bu sayede işlerini kendi zamanlarında ve istedikleri yerde yapabilirler.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Web güvenliği</h4>
                        <p class="text-muted">Günümüz teknolojileri incelendiğinde web tabanlı uygulamaların çokluğu ve kritiklik seviyesi göze çarpmaktadır. Bu kritik verilerin iletişiminin sağlandığı web uygulamalarının güvenliği de bu neticede önemlidir. Güncel zafiyetlerin ve bypass yöntemlerinin eklendiği eğitim içeriği, günlük olarak takip edilen gündeme ve zafiyet tespit plarformlarından esinlenen verilere göre hazırlanmıştır. Eğitimde bug bounty platformlarındaki dünyadaki uzmanların zafiyetleri de incelenecek ve uygulamaları yapılacaktır.Web uygulama güvenliği eğitimi içeriği;
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <?php 
    include("inc/footer.php");
    ?>