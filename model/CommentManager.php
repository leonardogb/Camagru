<?php

require_once ("Manager.php");

class CommentManager extends Manager
{
    public function get_commentImg()
    {
        $conn = $this->dbConnect();

        $getComments = "SELECT id_user, id_galeria, author, comment, comment_date FROM comment ORDER BY comment_date DESC";
        $respuesta = $conn->query($getComments);
        $tab = NULL;
        $i = 0;
        while ($linea = $respuesta->fetch(PDO::FETCH_ASSOC))
        {
            $tab[$i] = $linea;
            $i++;
        }
        return ($tab);
    }

    public function addComment($id_user, $id_img, $author, $comment, $mailUserImg, $envoyer)
    {

        $conn = $this->dbConnect();

        $addComment = "INSERT INTO comment(id_user, id_galeria, author, comment, comment_date) VALUES ('$id_user', '$id_img', '$author', '$comment', NOW())";
        $respuesta = $conn->exec($addComment);
        
        if ($envoyer)
        {
            $destinataire = $mailUserImg;
            $sujet = "Nouveau commentaire de " . $author ;

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: <lgarcia-@student.le-101.fr>' . "\r\n";
            $message = '
            <html>
            <head>
                <title>' . $sujet . '</title>
            </head>
            <body>
                <p>Vous avez un nouveau commentaire de <strong>' . $author . '.</strong></p> <br>
                <p>' . $comment . '</p>
                <p>---------------<br>
                Ceci est un mail automatique, Merci de ne pas y répondre.
                </p>
            </body>
            </html>
            ';
            if(mail($destinataire, $sujet, $message, $headers))
                return ($respuesta);
        }
        else
            return ($respuesta);
    }

    public function deleteComment($id_img)
    {
        $conn = $this->dbConnect();

        $deleteComments = "DELETE FROM comment WHERE id_galeria = '$id_img'";
        $affected = $conn->exec($deleteComments);

        if ($affected == 1)
            return (TRUE);
        else
            return (FALSE);
    }
}

?>