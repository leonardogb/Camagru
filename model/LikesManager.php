<?php

require_once ("Manager.php");

class LikesManager extends Manager
{
    public function get_likes()
    {
        $conn = $this->dbConnect();

        $getLikes = "SELECT id_user, id_galeria FROM likes";
        $respuesta = $conn->query($getLikes);
        $tab = NULL;
        $i = 0;
        while ($linea = $respuesta->fetch(PDO::FETCH_ASSOC))
        {
            $tab[$i] = $linea;
            $i++;
        }
        return ($tab);
    }

    public function likeExist($id_user, $id_img)
    {
        $conn = $this->dbConnect();

        $buscaLike = $conn->query("SELECT COUNT(id) FROM likes WHERE id_user = '$id_user' AND id_galeria = '$id_img'");
        $res = $buscaLike->fetchColumn(0);
        if ($res)
            return (TRUE);
        else
            return (FALSE);
    }

    public function addLike($id_user, $id_img)
    {
        $conn = $this->dbConnect();

        $addLike = "INSERT INTO likes(id_user, id_galeria) VALUES ('$id_user', '$id_img')";
        $respuesta = $conn->exec($addLike);
        return ($respuesta);
    }

    public function deleteLike($id_user, $id_img)
    {
        $conn = $this->dbConnect();

        $deleteLike = "DELETE FROM likes WHERE id_user = '$id_user' AND id_galeria = '$id_img'";
        $respuesta = $conn->exec($deleteLike);
        return ($respuesta);
    }

    public function deleteLikes($id_img)
    {
        $conn = $this->dbConnect();

        $deleteLikes = "DELETE FROM likes WHERE id_galeria = '$id_img'";
        $affected = $conn->exec($deleteLikes);

        if ($affected == 1)
            return (TRUE);
        else
            return (FALSE);
    }
    
}

?>