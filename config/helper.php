<?php
require 'vendor/autoload.php';


class helper
{
    static function yonlendir($url)
    {
        $client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
        $db = $client->UrlShortener;
        $collection = $client->UrlShortener->kisalt;
        $item=$collection->count(['link'=>$url]);
        if ($item!=0)
        {
            $bul=$collection->find(
                [
                    'link'=>$url
                ]
            );
            foreach ($bul as $a){
                $yeniHit=$a['hit'];
            }
            $yeniHit=$yeniHit+1;

            $guncelle=$collection->updateOne(
                ['link'=>$url],
                ['$set'=>['hit'=>$yeniHit]]
            );
            header("Location: $url");
        }
        else
        {
            header("Location: $url");
        }


    }



    static function yonlendirme($url)
    {
        $client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
        $db = $client->UrlShortener;
        $collection = $client->UrlShortener->uyekisalt;
        $item=$collection->count(['link'=>$url]);
        if ($item!=0)
        {
            $bul=$collection->find(
                [
                    'link'=>$url
                ]
            );
            foreach ($bul as $a){
                $yeniHit=$a['hit'];
            }
            $yeniHit=$yeniHit+1;

            $guncelle=$collection->updateOne(
                ['link'=>$url],
                ['$set'=>['hit'=>$yeniHit]]
            );
            header("Location: $url");
        }
        else
        {
            header("Location: $url");
        }


    }

    static function GirisYap($url, $sure=0)
    {
        if ($sure==0)
        {
            header("Location: $url");
        }
        else
        {
            $sure=0;
            header("Location: $url");
        }
    }
}