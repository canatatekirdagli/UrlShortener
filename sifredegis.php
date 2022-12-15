<?php
require 'vendor/autoload.php';
require_once 'config/helper.php';

session_start();
if ($_SESSION['email']=="")
{
    helper::Yonlendir("http://localhost/UrlShortener");
}

$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;
$collection = $client->UrlShortener->kullanici;

if ($_POST)
{
    $eskisifre=strip_tags($_POST['eskisifre']);
    $yenisifre=strip_tags($_POST['yenisifre']);
    $yenisifre1=strip_tags($_POST['yenisifre1']);

    $bul=$collection->find(
        [
            'email'=>$_SESSION['email']
        ]
    );
    foreach ($bul as $a){
        $sifre=$a['sifre'];
    }

    if ($eskisifre!="" && $yenisifre !="" && $yenisifre1!="")
    {
        if (md5($eskisifre)==$sifre)
        {
            if (strlen($yenisifre)>=8)
            {
                if ($yenisifre==$yenisifre1)
                {
                    if ($yenisifre!=$eskisifre)
                    {
                        $guncelle=$collection->updateOne(
                            ['email'=>$_SESSION['email']],
                            ['$set'=>['sifre'=>md5($yenisifre)]]
                        );
                        ?> <script>
                        alert("Şifreniz Başarıyla Güncellendi!")
                    </script>

                        <?php
                    }
                    else
                    {
                        ?> <script>
                        alert("Yeni şifreniz eski şifrenizle aynı olamaz!")
                    </script>

                        <?php
                    }
                }
                else
                {
                    ?> <script>
                    alert("Şifreler birbiriyle aynı olmalıdır!")
                </script>

                    <?php

                }
            }
            else
            {
                ?> <script>
                alert("Yeni şifreniz minimum 8 karakterden oluşmalıdır!")
            </script>

                <?php

            }
        }
        else
        {
            ?> <script>
            alert("Yanlış şifre girişi yaptınız lütfen tekrar deneyin")
        </script>

            <?php

        }
    }
    else
    {
        ?> <script>
        alert("Lütfen tüm boşlukları doldurunuz!")
    </script>

        <?php

    }
}

?>




<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="public/css/kayitol.css">

    <script src="https://kit.fontawesome.com/33ed2ec878.js" crossorigin="anonymous"></script>

</head>

<body>

<div class="header">
    <h1> <a href="uyeanasayfa.php"> <i class="fa-solid fa-circle-arrow-left"></i> </a>Ant Link Kısaltma </h1>
</div>

<div class="giriskutusu">
    <img src="https://w7.pngwing.com/pngs/505/761/png-transparent-login-computer-icons-avatar-icon-monochrome-black-silhouette.png"
         class="avatar">
    <h1>Şifreni Değiştir</h1>
    <form action="" method="POST">
        <p>Eski şifre</p>
        <input type="password" name="eskisifre" placeholder="Eski Şifre">
        <p>Yeni Şifre</p>
        <input type="password" name="yenisifre" placeholder="Yeni Şifre">
        <p>Şifre Tekrar</p>
        <input type="password" name="yenisifre1" placeholder="Şifre Tekrar">
        <input type="submit" name="" value="Değiştir">
    </form>
</div>

</body>

</html>