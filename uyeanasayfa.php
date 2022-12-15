<?php
define("SITE_URL","http://localhost/UrlShortener");
require 'vendor/autoload.php';
session_start();


$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;

$collection1 = $client->UrlShortener->kullanici;

$bul=$collection1->find(
    [
        'email'=>$_SESSION['email']
    ]
);
foreach ($bul as $a){
    $kullaniciAdi=$a['kullaniciAdi'];
    $olusturmaSay=$a['kisaltilanLink'];
}
?>




<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Dönüştürücü</title>
    <link rel="stylesheet" href="public/css/uyeanasayfa.css">

    <script src="https://kit.fontawesome.com/33ed2ec878.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

<div class="header">
    <h1>Ant Link Kısaltma    <i class="fa-solid fa-user"></i><?=$kullaniciAdi ?></h1>
</div>

<div class="x">

    <a id="istatistik" class="button" href="#"><span> İSTATİSTİKLER</span></a>
    <a id="button" class="button1" href="sifredegis.php"><i class="fa-solid fa-user-plus"></i><span> ŞİFRE
        DEĞİŞTİR</span></a>
    <a id="button" class="button2" href="oturumac.php"><i
            class="fa-sharp fa-solid fa-arrow-right-to-bracket"></i><span> ÇIKIŞ YAP</span></a>

    <form action="" method="POST">
    <div class="Icon-inside">
        <i class="fa fa-link fa-lg fa-fw" aria-hidden="true"></i>
        <input type="url" placeholder="Kısaltmak için URL giriniz..." name="link">
    </div>


        <?php
        if($_POST) {
            $secret = '6LePXmgjAAAAAL5eJrXF9YIQtXE0M-6ALsxwhRH-';
            $response = $_POST['g-recaptcha-response'];
            $remoteip = $_SERVER['REMOTE_ADDR'];

            $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
            $result=json_decode($url,TRUE);

            if ($result['success']==1)
            {
                $link=strip_tags($_POST['link']);
                if ($link!="")
                {
                    if (filter_var($link,FILTER_VALIDATE_URL))
                    {
                        $collection = $client->UrlShortener->uyekisalt;
                        $item=$collection->count(['link'=>$link]);
                        $bul=$collection->find(
                            [
                                'link'=>$link
                            ]
                        );
                        foreach ($bul as $a){
                            $link2=$a['kod'];
                        }
                        $kod=md5(uniqid());
                        $kodKisa=substr($kod,0,10);
                        $insertOneResult = $collection->insertOne([
                            'link' => $link,
                            'kod' => $kodKisa,
                            'olusTarih' => date("d-m-Y H:i:s"),
                            'bitisTarih'=>date('d.m.Y H:i:s', strtotime('+3 days')),
                            'olusturan'=>$kullaniciAdi,
                            'hit'=>0,
                        ]); ?>
                        <div class="Icon-inside">
                            <i class="fa fa-link fa-lg fa-fw" aria-hidden="true"></i>
                            <input type="url" value=<?=SITE_URL?>/i/<?=$kodKisa?> name="sonuc" id="sonuc">
                        </div>

                        <div class="kopyalatip">
                            <button id="kopyalabuton" onclick="kopyala()" >KOPYALA</button>
                        </div>
                        <script>
                            function kopyala(){
                                var metin = document.getElementById("url");
                                metin.select();
                                document.execCommand("copy");
                                alert("URL KOPYALANDI");
                            }
                        </script>

                    <?php
                        }
                    else
                    {
                        ?> <script>
                alert("Lütfen Geçerli Link Girin!")
            </script>

            <?php
                    }
                }
                else
                {
                        ?> <script>
                alert("Lütfen Geçerli Link Girin!")
            </script>

            <?php
                }
            }
            else
            {
                        ?> <script>
                alert("Lütfen Recaptcha'yı Doğrulayın!")
            </script>

                <?php
            }
        }
        ?>
        <?=SITE_URL?>/i/<?=$link2?>
    <div class="kopyalatip">
        <div class="g-recaptcha" data-sitekey="6LePXmgjAAAAAIzGEZREM9PgvqYXb5tgC-Vz1wvV"></div>
    </div>

    <input type="submit" value="Kısalt" id="kısalt">

</div>
</form>

<div id="tableduzenle" style="overflow-x:auto;">
    <table>
        <tr>
            <th>Kısa Link</th>
            <th>Kod</th>
            <th>Link</th>
            <th>Tık</th>
            <th>Oluşturulma Tarihi</th>
            <th>Son Kullanma Tarihi</th>
            <th>Sil</th>
            <th>Düzenle</th>
        </tr>
        <?php

        $coll = $client->UrlShortener->kullanici;

        $bul=$coll->find(
            [
                'email'=>$_SESSION['email']
            ]
        );
        foreach ($bul as $kullanici){
            $kullaniciAdi=$kullanici['kullaniciAdi'];
        }

        $coll1=$client->UrlShortener->uyekisalt;

        $bul2=$coll1->find(
            [
                'olusturan'=>$kullaniciAdi
            ]
        );
        ?>
        <?php
        foreach($bul2 as $item){?>

            <tr>
                <td>http://localhost/UrlShortener/i/<?= $item['kod'] ?></td>
                <td><?= $item['kod'] ?></td>
                <td><?= $item['link'] ?></td>
                <td><?= $item['hit'] ?></td>
                <td><?= $item['olusTarih'] ?></td>
                <td><?= $item['bitisTarih'] ?></td>
                <td><a href="linksil.php?pid=<?=$item['kod']?>" id="sil" class="btn btn-danger">Sil</a></td>
                <td style="text-align:center"><a id="guncelle" href="#">Güncelle</a></td>

            </tr>

        <?php } ?>

    </table>
</div>


<?php
$collection = $client->UrlShortener->uyekisalt;
$collection1 = $client->UrlShortener->kisalt;

$bul=$collection->find();
$encok=0;
$encoktik="";
$encokkod="";
foreach ( $bul as $item) {
    if ($item['hit']>$encok)
    {
        $encok=$item['hit'];
        $encoktik=$item['link'];
        $encokkod=$item['kod'];
    }
}
$bul=$collection1->find();
foreach ( $bul as $item) {
    if ($item['hit']>$encok)
    {
        $encok=$item['hit'];
        $encoktik=$item['link'];
        $encokkod=$item['kod'];
    }
}

$collection2 = $client->UrlShortener->kullanici;
$donusum=0;
$ad="";
$hit1=0;
$bul=$collection2->find();
foreach ( $bul as $item) {
    if ($item['kisaltilanLink']>$donusum)
    {
        $donusum=$item['kisaltilanLink'];
        $ad=$item['kullaniciAdi'];
        $hit1=$item['kisaltilanLink'];
    }
}

?>

<div class="modal">
    <form>
        <h3 id="modal-kapat">X</h3>
        <table>
            <tr>
                <td>Kod: </td>
                <td> <input type="text"> </td>
            </tr>
            <tr>
                <td> Son Kullanma Tarihi: </td>
                <td> <input type="date"> </td>
            </tr>
        </table>
    </form>
</div>


<div class="modal1">
    <form>
        <h3 id="modal-kapat1">X</h3>
        <h1>İstatistikler</h1> <br>
        <table>
            <tr>
                <td>En Çok Tıklanan Link</td>
                <td><?=$encoktik?> </td>
                <td>http://localhost/UrlShortener/i/<?=$encokkod?> </td>
            </tr>
            <tr>
                <td>En Çok Link Kısaltan Kullanıcı </td>
                <td>@<?=$ad?></td>
                <td><?=$hit1?></td>
            </tr>
        </table>
    </form>
</div>



<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


<script src="public/js/uyeanasayfa.js"></script>

</body>

</html>