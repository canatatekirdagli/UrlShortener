<?php
require 'vendor/autoload.php';
class connection
{
    public $db;
    function __construct()
    {
        $this->db=new MongoDB\Client("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
    }
}