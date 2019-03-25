<?php

session_start();

if (isset($_POST['filtro'], $_POST['foto']))
{
    $filtro = $_POST['filtro'];
    $foto = $_POST['foto'];
    $foto = str_replace('data:image/png;base64,', '', $foto);
    $foto = str_replace(' ', '+', $foto);
    $photo = base64_decode($foto);
    if (!file_exists("../ffinales/"))
        mkdir("../ffinales/");
    
    $nom_photo = uniqid() . ".png";
    file_put_contents("../ffinales/" . $nom_photo, $photo);

    $source = imagecreatefrompng($filtro);
    $destination = imagecreatefrompng("../ffinales/" . $nom_photo);

    $largeur_source = imagesx($source);
    $hauteur_source = imagesy($source);
    if ($_POST['filtro'] == "../filtros/bigote.png")
    {
        $sourceX = 250;
        $sourceY = 200;
    }
    else if ($_POST['filtro'] == "../filtros/caca.png")
    {
        $sourceX = 0;
        $sourceY = 300;
    }
    else
    {
        $sourceX = 0;
        $sourceY = 0;
    }

    imagecopy($destination, $source, $sourceX, $sourceY, 0, 0, $largeur_source, $hauteur_source);

    $success = imagepng($destination, "../ffinales/" . $nom_photo);
    if ($success)
    {
        include ("database.php");

        
        try
        {
            $tab = "ffinales/" . $nom_photo;

            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $id_user = $_SESSION['id_user'];
            $addImg = "INSERT INTO galeria (id_user, img, img_date) VALUE ('$id_user', '$nom_photo', NOW())";
            $conn->query($addImg);

            $respuesta = $conn->query("SELECT id FROM galeria WHERE img = '$nom_photo'");
            $res = $respuesta->fetch(PDO::FETCH_ASSOC);
            $tab .= " " . $res['id'];

            print_r($tab);
            //echo "ffinales/". $nom_photo;
        }
        catch (PDOException $e)
        {
            return ($e->getMessage());
        }
        


    }
    

    
}
else
{
    print('Vous devez vous conecter <a href="../index.php">Accueil</a>');
    return ('Vous devez vous conecter <a href="../index.php">Accueil</a>');
}

?>