<?php
define("SITE_URL","http://localhost/UrlShortener");
require 'vendor/autoload.php';
?>




<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Dönüştürücü</title>
    <link rel="stylesheet" href="public/css/index.css">

    <script src="https://kit.fontawesome.com/33ed2ec878.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

<div class="header">
    <h1>Ant Link Kısaltma </h1>
</div>
<?php $sonuc="" ?>
<br>

<div class="x">

    <a id="istatistik" class="button" href="#"><span> İSTATİSTİKLER</span></a>
    <a id="button" class="button1" href="kayitol.php"><i class="fa-solid fa-user-plus"></i><span> KAYDOL</span></a>
    <a id="button" class="button2" href="oturumac.php"><i
                class="fa-sharp fa-solid fa-arrow-right-to-bracket"></i><span> OTURUM AÇ</span></a>

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
        $link=strip_tags($_POST['link']);
        if ($result['success']==1)
        {

            if ($link!="")
            {
                if (filter_var($link,FILTER_VALIDATE_URL))
                {
                    $client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
                    $db = $client->UrlShortener;
                    $collection = $client->UrlShortener->kisalt;
                    $collection1 = $client->UrlShortener->kisalt;
                    $item=$collection->count(['link'=>$link]);
                    $bul=$collection->find(
                        [
                            'link'=>$link
                        ]
                    );
                    foreach ($bul as $a){
                        $link2=$a['kod'];
                    }
                    if ($item==0)
                    {
                        do
                        {
                            $kod=md5(uniqid());
                            $kodKisa=substr($kod,0,10);
                            $item1=$collection->count(['kod'=>$kodKisa]);
                            $item2=$collection1->count(['link'=>$kodKisa]);
                        }while($item1!=0 && $item2!=0);

                        $insertOneResult = $collection->insertOne([
                            'link' => $link,
                            'kod' => $kodKisa,
                            'olusTarih' => date("d-m-Y"),
                            'bitisTarih'=>date('Y-m-d', strtotime('+3 days')),
                            'hit'=>0,
                        ]); ?>
            <div class="Icon-inside">
                <i class="fa fa-link fa-lg fa-fw" aria-hidden=true></i>
                <input type="url" value=<?=SITE_URL?>/i/<?=$kodKisa?> class="form-control" id="url"/>
            </div>

            <div class="deneme">
                <button id="denemebuton" onclick="kopyala()">Kopyala
                    <span class="tooltiptext" id="myTooltip">Panoya Kopyala</span>
                </button>
            </div>
                        <script>
                            function kopyala(){
                                var metin = document.getElementById("url");
                                metin.select();
                                document.execCommand("copy");
                                alert("URL KOPYALANDI");
                            }
                        </script>

                 <?php   }
                    else
                    {
                        $guncelle=$collection->updateOne(
                            ['link'=>$link],
                            ['$set'=>['bitisTarih'=>date('Y-m-d', strtotime('+3 days'))]]
                        ); ?>

            <div class="Icon-inside">
                <i class="fa fa-link fa-lg fa-fw" aria-hidden=true></i>
                <input type="url" value=<?=SITE_URL?>/i/<?=$link2?> class="form-control" id="url"/>
            </div>

            <div class="deneme">
                <button id="denemebuton" onclick="kopyala()">Kopyala
                    <span class="tooltiptext" id="myTooltip">Panoya Kopyala</span>
                </button>
            </div>
            <script>
                function kopyala(){
                    var metin = document.getElementById("url");
                    metin.select();
                    document.execCommand("copy");

                }
            </script>

        <?php        }
                }
                else
                {
             ?> <script>
                    alert("Lütfen Geçerli Link Giriniz!")
                </script>

                    <?php
                }
            }
            else
            {
                    ?> <script>
            alert("Lütfen Geçerli Link Giriniz!")
        </script>

        <?php    }

        }
        else
        {


            $sonuc= "Lütfen Recaptcha'yı Doğrulayın!";


        }

    }

    ?>

        <div class="g-recaptcha" data-sitekey="6LePXmgjAAAAAIzGEZREM9PgvqYXb5tgC-Vz1wvV"></div>

    <input type="submit" value="Kısalt" id="kısalt">


    <div class="linkler">
        <a id="uyelink" href="#">Üyelik Avantajları</a>
    </div>

</div>
</form>

<h2 id="hata" ><?=$sonuc?></h2>

<div class="modal">
    <form>
        <h3 id="modal-kapat">X</h3>
        <h1>Üyelik Avantajları</h1> <br>
        <p>Üyeler;</p><br>
        <ul id="liste">
            <li>Linklerini ve kaç tıklama aldıklarını görebilir.</li> <br>
            <li>Linkleri silinmez, sistem var oldukça kayıtları saklanır.</li> <br>
            <li>Özel link adları oluşturabilir.</li> <br>
            <li>Yönlendirmeleri silebilir.</li> <br>
            <li>3 gün olan link süresini uzatabilir ve kısaltabilir.</li>
        </ul>

    </form>
</div>

<?php
$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;
$collection2 = $client->UrlShortener->kisalt;
$collection3 = $client->UrlShortener->kisalt;

$bul=$collection2->find();
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
$bul=$collection3->find();
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

<div class="modal1">
    <form>
        <h3 id="modal-kapat1">X</h3>
        <h1>İstatistikler</h1> <br>
        <table>
            <tr>
                <td>En Çok Tıklanan Link</td>
                <td>http://localhost/UrlShortener/i/<?=$encokkod?> </td>
            </tr>
            <tr>
                <td>En Çok Link Kısaltan Kullanıcı </td>
                <td>@<?=$ad?></td>
            </tr>
        </table>
    </form>
</div>



    </form>
</div>



<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


<script src="public/js/index.js"></script>

</body>

</html>