<?php
require 'vendor/autoload.php';
require_once 'config/helper.php';

$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;
$sonuc="";
$collection = $client->UrlShortener->kisalt;
$collection1 = $client->UrlShortener->kisalt;

if(isset($_GET["kod"]))
{
    $bul=$collection->find(
        [
            'kod'=>$_GET["kod"]
        ]
    );
    foreach ($bul as $a){
        $kod=$a['kod'];
        $tarih=$a['bitisTarih'];
        $olusTarih=$a['olusTarih'];
        $link=$a['link'];
        $olusturan=$a['olusturan'];
        $hit=$a['hit'];

    }


}
else
{
    header("Location:uyeanasayfa.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Dönüştürücü</title>
    <link rel="stylesheet" href="public/css/guncelle.css">

    <script src="https://kit.fontawesome.com/33ed2ec878.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<div class="header">
    <h1> <a href="uyeanasayfa.php"> <i class="fa-solid fa-circle-arrow-left"></i> </a>Ant Link Kısaltma </h1>
</div>



<div class="iframekutusu">
    <h2>KISALTILAN LINK</h2><br><br>
    <div class="bilgiler">
        <iframe src=" <?=$link ?>" frameborder="0" width="100%" height="280"></iframe>
    </div>

</div>

<div class="guncellekutu">
    <h2>BİLGİLER</h2><br><br>
    <div class="modal">
        <form action="" method="POST">
            <table>
                <tr>
                    <td>Kullanıcı Adı : </td>
                    <td> <p><?=$olusturan ?></p> </td>
                </tr>
                <tr>
                    <td>Oluşturma Tarihi : </td>
                    <td> <?=$olusTarih ?> </td>
                </tr>
                <tr>
                    <td>Toplam Tıklanma : </td>
                    <td> <?=$hit ?> </td>
                </tr>
                <tr>
                    <td>Kod : </td>
                    <td> <input type="text" name="kod" value="<?=$kod ?>"> </td>
                </tr>
                <tr>
                    <td> Son Kullanma Tarihi : </td>
                    <td> <input type="date" name="tarih" value="<?=$tarih ?>"> </td>
                </tr>
            </table>
            <br>
            <input type="submit" value="Güncelle" id="kısalt">
        </form>
    </div>
</div>

<?php
if($_POST) {
    $yenitarih=strip_tags($_POST['tarih']);
    $yenikod=strip_tags($_POST['kod']);
    if ($yenitarih!="" && $yenikod!="" )
    {
        if (strlen($yenikod)<=10)
        {
            $item=$collection->count(['kod'=>$yenikod]);
            $item1=$collection1->count(['kod'=>$yenikod]);
            if ($kod!=$yenikod)
            {
                if ($item1==0 && $item==0)
                {
                    $guncelle=$collection->updateOne(
                        ['kod'=>$kod],
                        ['$set'=>['kod'=>$yenikod,'bitisTarih'=>$yenitarih]]
                    );
                    if ($guncelle)
                    {
                        ?> <script>
                        alert("Bilgiler Başarıyla Güncellendi!")
                        </script>
                            <?php
                     header("Location:uyeanasayfa.php");
                    }
                    else
                    {
                        $sonuc="Bilgiler Güncellenemedi. Lütfen Tekrar Deneyiniz!";

                    }
                }
                else
                {
                    $sonuc="Girdiğiniz kod kullanılmaktadır lütfen başka bir kodla deneyiniz!";
                }
            }
            else
            {
                $guncelle=$collection->updateOne(
                    ['kod'=>$kod],
                    ['$set'=>['bitisTarih'=>$yenitarih]]
                );
                if ($guncelle)
                {
                ?> <script>
                    alert("Bilgiler Başarıyla Güncellendi!")
                </script>
                <?php
                header("Location:uyeanasayfa.php");
                }
                else
                {
                    $sonuc="Bilgiler Güncellenemedi. Lütfen Tekrar Deneyiniz!";
                }
            }
        }
        else
        {
            $sonuc="Kodun uzunluğu maksimum 10 karakter Olmalıdır.";
        }
    }
    else
    {
        $sonuc="Lütfen tüm alanları doldurunuz!";

    }
}
?>

<br>
<h2 id="hata" style="text-align: center" ><?=$sonuc?></h2>




</body>

</html>


