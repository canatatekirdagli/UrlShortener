<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';


if ($_POST) {
    $email = strip_tags($_POST['email']);


    $client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
    $db = $client->UrlShortener;
    $collection = $client->UrlShortener->kullanici;

    $mailsayisi = $collection->count(['email' => $email]);

    if ($mailsayisi != 0)
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $sifre= substr(str_shuffle($data), 0, 8);
        $DBsifre=md5($sifre);

        $mail=new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPKeepAlive=true;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure='tls';

        $mail->Port=587;
        $mail->Host='smtp.gmail.com';

        $mail->Username="kocaeli.uni92@gmail.com";
        $mail->Password="ruyqoazxecaxhkjx";

        $mail->setFrom("kocaeli.uni92@gmail.com","ANT URL SHORTENER");
        $mail->addAddress($email);

        $mail->Subject="PASSWORD CHANGED!";
        $mail->Body="Sayın kullanıcımız yeni şifreniz: $sifre ";
        if ($mail->send())
        {
            $guncelle=$collection->updateOne(
                ['email'=>$email],
                ['$set'=>['sifre'=>$DBsifre]]
            );
            if ($guncelle)
            {
                ?> <script>
                alert("Şifreniz Başarıyla Mail Adresinize Gönderildi Yeni Şifrenizle Giriş Yapabilirsiniz!")
            </script>

                <?php
            }
            else
            {
                ?> <script>
                alert("Şifre Resetlenemedi!")
            </script>

                <?php
            }
        }
        else
        {
            ?> <script>
            alert("Şifre Resetlenemedi!")
        </script>

            <?php
        }
    }
    else
    {
        ?> <script>
        alert("Kayıtlı mail adresi bulunamadı!")
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
    <title>Şifremi Unuttum</title>
    <link rel="stylesheet" href="public/css/sifremiunuttum.css">

    <script src="https://kit.fontawesome.com/33ed2ec878.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="header">
    <h1> <a href="oturumac.php"> <i class="fa-solid fa-circle-arrow-left"></i> </a>Ant Link Kısaltma </h1>
</div>

<div class="giriskutusu">
    <img src="https://w7.pngwing.com/pngs/505/761/png-transparent-login-computer-icons-avatar-icon-monochrome-black-silhouette.png" class="avatar">
    <h1>Şifre Yenileme</h1> <br>
    <form action="" method="POST">
        <p>E-mail Adresinizi Giriniz:</p>
        <input type="email"placeholder="e-mail" name="email">
        <input id="sifreunuttum" type="submit" name="" value="Gönder">
    </form>
</div>
</body>
</html>