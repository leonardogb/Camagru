<?php

require_once ("Manager.php");

class ImageManager extends Manager
{
    public function get_imgdb($id_user)
    {

        $conn = $this->dbConnect();

        if ($id_user == "all")
            $getFotos = "SELECT id, id_user, img FROM galeria ORDER BY img_date";
        else
            $getFotos = "SELECT id, id_user, img FROM galeria WHERE id_user = '$id_user' ORDER BY img_date";
        $respuesta = $conn->query($getFotos);
        $tab = NULL;
        $i = 0;
        while ($linea = $respuesta->fetch(PDO::FETCH_ASSOC))
        {
            $tab[$i] = $linea;
            $i++;
        }
        return ($tab);
    }

    public function get_rangeImg($limit, $start)
    {

        $conn = $this->dbConnect();

        if ($start < 0)
            return (FALSE);
        $getFotos = "SELECT id, id_user, img FROM galeria ORDER BY img_date LIMIT $limit OFFSET $start";

        $respuesta = $conn->query($getFotos);
        $tab = NULL;
        $i = 0;
        while ($linea = $respuesta->fetch(PDO::FETCH_ASSOC))
        {
            $tab[$i] = $linea;
            $i++;
        }
        return ($tab);
    }

    public function get_nbImg()
    {
        $conn = $this->dbConnect();

        $getNbImg = "SELECT COUNT(id) as 'Nb_Img' FROM galeria";
        $respuesta = $conn->query($getNbImg);
        $nbImg = $respuesta->fetch(PDO::FETCH_ASSOC);
        return ($nbImg['Nb_Img']);
    }

    public function get_imgById($id_img)
    {
        $conn = $this->dbConnect();

        $getFotos = "SELECT id, id_user, img FROM galeria WHERE id = '$id_img'";
        $respuesta = $conn->query($getFotos);
        $res = $respuesta->fetch(PDO::FETCH_ASSOC);
        
        return ($res);
    }

    public function deleteImage($id_user, $id_img)
    {
        $conn = $this->dbConnect();

        $img = $this->get_imgById($id_img);
        $deleteImg = "DELETE FROM galeria WHERE id = '$id_img' AND id_user = '$id_user'";
        $affected = $conn->exec($deleteImg);

        unlink("ffinales/" . $img['img']);
        if ($affected == 1)
            return (TRUE);
        else
            return (FALSE);
    }
}

?>