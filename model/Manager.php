<?php

class Manager
{
    protected function dbConnect()
    {
        require ("config/database.php");

        $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return ($conn);
    }

    protected function dbConnect2()
    {
        require ("config/database.php");

        $conn = new PDO("mysql:host=172.18.0.2", $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return ($conn);
    }
}

?>