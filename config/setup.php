<?php
session_start();
include ("database.php");

try
{
    $conn = new PDO("mysql:host=172.19.0.2", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $base = "CREATE DATABASE camagrudb";
    $conn->exec($base);

    $users = "CREATE TABLE IF NOT EXISTS camagrudb.users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `login` VARCHAR(30) NOT NULL,
        passwd CHAR(128) NOT NULL,
        mail CHAR(255) NOT NULL,
        cle CHAR(128) NOT NULL,
        active TINYINT(1) UNSIGNED DEFAULT 0,
        mailComment TINYINT(1) UNSIGNED DEFAULT 1)";
    $conn->exec($users);

    $passwdAdmin = hash("Whirlpool", "lgarcia-");
    $admin = "INSERT INTO camagrudb.users (`login`, passwd, mail, active) 
    VALUES ('admin', '$passwdAdmin', 'lgarcia-@student.le-101.fr', 1)";
    $conn->exec($admin);

    $galeria = "CREATE TABLE IF NOT EXISTS camagrudb.galeria (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_user INT(6) UNSIGNED NOT NULL,
        img VARCHAR(30) NOT NULL,
        img_date DATETIME NOT NULL)";
    $conn->exec($galeria);

    $comentarios = "CREATE TABLE IF NOT EXISTS camagrudb.comment (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_user INT(6) UNSIGNED NOT NULL,
        id_galeria INT(6) UNSIGNED NOT NULL,
        author VARCHAR(30) NOT NULL,
        comment VARCHAR(255) NOT NULL,
        comment_date DATETIME NOT NULL)";
    $conn->exec($comentarios);

    $likes = "CREATE TABLE IF NOT EXISTS camagrudb.likes (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_user INT(6) UNSIGNED NOT NULL,
        id_galeria INT(6) UNSIGNED NOT NULL)";

    $conn->exec($likes);

    if (isset($_SESSION['user']) && $_SESSION['user'] == "admin")
        header('location: ../index.php?action=profile');
    else
        header('location: ../index.php');
}
catch (PDOException $e)
{
    print('<a href="../index.php">Accueil</a>');
}