<?php

if (isset($_GET['log'], $_GET['cle']))
{
    include ("database.php");

    $login = htmlspecialchars($_GET['log']);
    $cle = htmlspecialchars($_GET['cle']);
    try
    {
        $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $consulta = "SELECT cle, active FROM users WHERE login = '$login'";
        $result = $conn->query($consulta);

        $listo = $result->fetch(PDO::FETCH_ASSOC);
        if ($listo['active'] == 1)
            print("Votre compte est déjà activé.");
        else if ($listo['active'] == 0)
        {
            if ($listo['cle'] == $cle)
            {
                $actualiza = "UPDATE users SET active = 1, cle = null";
                $conn->query($actualiza);
                print("Votre compte vient d'être validé\n");
            }
            else
                print("Le code n'est pas valide\n");
        }
    }
    catch (PDOException $e)
    {
        echo  $e->getMessage();
        $conn = null;
    }
}

?>
<html>
    <head>
        <title>Activation de compte</title>
        <meta charset="utf-8">
    </head>
    <body>
        <a href="../index.php">Retour</a>
    </body>
</html>