<?php
require 'vendor/autoload.php';
require_once 'config/helper.php';
if ($_POST)
{
    $email=strip_tags($_POST['email']);
    $kullaniciAdi=strip_tags($_POST['kullaniciAdi']);
    $sifre=strip_tags($_POST['sifre']);
    $sifretekrar=strip_tags($_POST['sifretekrar']);

    $client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
    $db = $client->UrlShortener;
    $collection = $client->UrlShortener->kullanici;

    $mailsayisi=$collection->count(['email'=>$email]);
    $kullanicisayisi=$collection->count(['kullaniciAdi'=>$kullaniciAdi]);



    if ($email!="" && $kullaniciAdi!="" && $sifre!="" && $sifretekrar!="") //kutular boş mu
    {
        if (filter_var($email,FILTER_VALIDATE_EMAIL)) //mail geçerli mi?
        {
            if (strlen($sifre)>=8)
            {
                if ($sifre==$sifretekrar) //şifreler uyuşuyor mu?
                {
                    if ($mailsayisi==0)
                    {
                        if ($kullanicisayisi==0)
                        {
                            session_start();
                            $_SESSION['email']=$_POST['email'];
                            $_SESSION['kullaniciAdi']=$_POST['kullaniciAdi'];
                            $insertOneResult = $collection->insertOne([
                                'email' => $email,
                                'kullaniciAdi' => $kullaniciAdi,
                                'sifre' => md5($sifre),
                                'kisaltilanLink'=>0
                            ]);
                            helper::GirisYap("http://localhost/UrlShortener/uyeanasayfa.php");
                        }
                        else
                        {
                            ?> <script>
                            alert("Bu kullanıcı adı mevcuttur lütfen başka bir kullanıcı adıyla tekrar deneyiniz!")
                        </script>

                            <?php
                        }
                    }
                    else
                    {
                        ?> <script>
                        alert("Bu mail adresiyle kayıtlı kullanıcı vardır! Lütfen giriş yapınız!")
                    </script>

                        <?php
                    }
                }
                else
                {
                    ?> <script>
                    alert("Şifreler birbiriyle uyuşmamaktadır!")
                </script>

                    <?php
                }
            }
            else
            {
                ?> <script>
                alert("Şifreniz en az 8 karakterden oluşmalıdır!")
            </script>

                <?php
            }
        }
        else
        {
            ?> <script>
            alert("Geçersiz email adresi")
        </script>

            <?php
        }
    }
    else
    {
        ?> <script>
        alert("Lütfen tüm alanları doldurunuz!")
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
    <h1> <a href="index.php"> <i class="fa-solid fa-circle-arrow-left"></i> </a>Ant Link Kısaltma </h1>
</div>

<div class="giriskutusu">
    <img src="https://w7.pngwing.com/pngs/505/761/png-transparent-login-computer-icons-avatar-icon-monochrome-black-silhouette.png" class="avatar">
    <h1>Kayıt Ol</h1>
    <form action="" method="POST">
        <p>E-posta</p>
        <input type="email" name="email" placeholder="E-posta" >
        <p>Kullanıcı Adı</p>
        <input type="text" name="kullaniciAdi" placeholder="Kullanıcı Adı" >
        <p>Şifre</p>
        <input type="password" name="sifre" placeholder="Şifre">
        <p>Şifre Tekrar</p>
        <input type="password" name="sifretekrar" placeholder="Şifre Tekrar">
        <input type="submit" name="" value="Kayıt Ol">
    </form>
</div>

</body>
</html>