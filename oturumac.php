<?php
require 'vendor/autoload.php';
require_once 'config/helper.php';
if ($_POST) {
    $email = strip_tags($_POST['email']);
    $sifre = strip_tags($_POST['sifre']);

    $client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
    $db = $client->UrlShortener;
    $collection = $client->UrlShortener->kullanici;

    $mailsayisi=$collection->count(['email'=>$email]);

    if ($mailsayisi!=0)
    {
        $bul=$collection->find(
            [
                'email'=>$email
            ]
        );
        foreach ($bul as $a){
            $girismail=$a['email'];
            $girissifre=$a['sifre'];
        }

        if ($girismail==$email && $girissifre==md5($sifre))
        {
            session_start();
            $_SESSION['email']=$_POST['email'];
            $_SESSION['kullaniciAdi']=$_POST['kullaniciAdi'];
            helper::GirisYap("http://localhost/UrlShortener/uyeanasayfa.php");
        }
        else
        {
            ?> <script>
            alert("Şifre Yanlış")
        </script>

            <?php
        }
    }
    else
    {
        ?> <script>
        alert("Kayıtlı Kullanıcı Bulunamadı")
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
    <title>Oturum Aç</title>
    <link rel="stylesheet" href="public/css/girisyap.css">

    <script src="https://kit.fontawesome.com/33ed2ec878.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="header">
    <h1> <a href="index.php"> <i class="fa-solid fa-circle-arrow-left"></i> </a>Ant Link Kısaltma </h1>
</div>

<div class="giriskutusu">
    <img src="https://w7.pngwing.com/pngs/505/761/png-transparent-login-computer-icons-avatar-icon-monochrome-black-silhouette.png" class="avatar">
    <h1>Oturum Aç</h1>
    <form action="" method="POST">
        <p>E-posta</p>
        <input type="email" name="email" placeholder="E-posta" >
        <p>Şifre</p>
        <input type="password" name="sifre" placeholder="Şifre">
        <input type="submit" name="" value="Giriş">
        <a href="sifremiunuttum.php">Şifremi unuttum</a>
    </form>
</div>

</body>
</html>