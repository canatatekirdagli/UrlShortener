<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
$db = $client->UrlShortener;

$collection = $client->UrlShortener->uyekisalt;
if(isset($_GET["pid"]))
{
    $bul=$collection->find(
        [
            'kod'=>$_GET["pid"]
        ]
    );
    foreach ($bul as $a){
        $kod=$a['kod'];
    }
    $deleteResult = $collection->deleteOne(['kod' => $kod]);
    if($deleteResult){
        header("Location:uyeanasayfa.php"); //Silme tamamlandıktan sonra personelliste sayfasına yönlendiriyoruz.
    }
    else
        echo("Kayıt silinemedi.");
}
?>