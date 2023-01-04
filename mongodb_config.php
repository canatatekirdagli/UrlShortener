<?php

class DbManager {

    //Database configuration
    private $conn;

    function __construct(){
        //Connecting to MongoDB
        try {
            //Establish database connection
            $this->conn = new MongoDB\Driver\Manager("mongodb+srv://root:1234@mydb.hnuneft.mongodb.net/?retryWrites=true&w=majority");
        }catch (MongoDBDriverExceptionException $e) {
            echo $e->getMessage();
            echo nl2br("n");
        }
    }

    function getConnection() {
        return $this->conn;
    }

}

?>