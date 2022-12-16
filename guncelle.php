<?php
require 'vendor/autoload.php';
require_once 'config/helper.php';

$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;

$collection = $client->UrlShortener->uyekisalt;
if(isset($_GET["kod"]))
{
    $bul=$collection->find(
        [
            'kod'=>$_GET["kod"]
        ]
    );
    foreach ($bul as $a){
        $id=$a['_id'];
        $kod=$a['kod'];
        $tarih=$a['bitisTarih'];
    }
    session_start();
    $_SESSION['kod']=$kod;
    $_SESSION['tarih']=$tarih;


}
?>