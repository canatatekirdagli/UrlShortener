<?php
require_once 'config/helper.php';
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;
$collection = $client->UrlShortener->kisalt;
$collection1 = $client->UrlShortener->kisalt;

$kod=strip_tags($_GET['kod']);

if ($kod!="")
{
    $item=$collection->count(['kod'=>$kod]);
    if ($item!=0)
    {
        $bul=$collection->find(
            [
                'kod'=>$kod
            ]
        );
        foreach ($bul as $a){
            $link=$a['link'];
            $bitisTar=$a['bitisTarih'];
            $yeniHit=$a['hit']+1;
        }
        $bugun=date("Y-m-d");
        if ($bugun>$bitisTar)
        {
            helper::yonlendir("http://localhost/UrlShortener/404.php"); //HATA SAYFASINA GİDİCEK GEÇERLİLİĞİ BİTMİŞ
        }
        else
        {
            helper::yonlendir($link);
        }


    }
    else
    {
        $item1=$collection1->count(['kod'=>$kod]);
        if ($item1!=0)
        {
            $bul=$collection1->find(
                [
                    'kod'=>$kod
                ]
            );
            foreach ($bul as $a){
                $link=$a['link'];
                $bitisTar=$a['bitisTarih'];
                $yeniHit=$a['hit']+1;
            }
            $bugun=date("Y-m-d");
            if ($bugun>$bitisTar)
            {
                helper::yonlendirme("http://localhost/UrlShortener/404.php"); //HATA SAYFASINA GİDİCEK GEÇERLİLİĞİ BİTMİŞ
            }
            else
            {
                helper::yonlendirme($link);
            }


        }
        else
        {
            helper::Yonlendir("http://localhost/UrlShortener/404.php");
        }

    }
}
else
{

    helper::Yonlendir("http://localhost/UrlShortener/404.php");
    //404 sayfasına gelicek!!!

}