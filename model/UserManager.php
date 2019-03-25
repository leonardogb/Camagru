<?php

require_once ("Manager.php");

class UserManager extends Manager
{
    public function dropdb()
    {
        $conn = $this->dbConnect2();

        $conn->exec("DROP DATABASE IF EXISTS camagrudb");
    }

    public function user_exist($user)
    {
        $conn = $this->dbConnect();

        $user = str_replace("'", "\'", $user);
        $user = htmlspecialchars($user);
        $stmt = $conn->query("SELECT COUNT(id) FROM users WHERE login = '$user'");
        $res = $stmt->fetchColumn(0);
        if ($res)
            return (TRUE);
        else
            return (FALSE);
    }

    function mail_exist($mail)
    {

        $conn = $this->dbConnect();

        $mail = str_replace("'", "\'", $mail);
        $mail = htmlspecialchars($mail);
        $stmt = $conn->query("SELECT COUNT(id) FROM users WHERE mail = '$mail'");
        $res = $stmt->fetchColumn(0);
        if ($res)
            return (TRUE);
        else
            return (FALSE);
    }

    public function get_user($id_user)
    {
        $conn = $this->dbConnect();

        $id_user = str_replace("'", "\'", $id_user);
        $id_user = htmlspecialchars($id_user);
        $respuesta = $conn->query("SELECT id, `login`, mail, mailComment FROM users WHERE id = '$id_user'");
        $res = $respuesta->fetch(PDO::FETCH_ASSOC);
        return ($res);
    }

    public function get_userLogin($login)
    {
        $conn = $this->dbConnect();

        $login = str_replace("'", "\'", $login);
        $login = htmlspecialchars($login);
        $respuesta = $conn->query("SELECT id, passwd, active FROM users WHERE `login` = '$login'");
        $res = $respuesta->fetch(PDO::FETCH_ASSOC);
        return ($res);
    }

    public function cambiaLogin($id_user, $newLogin)
    {

        $conn = $this->dbConnect();

        $id_user = str_replace("'", "\'", $id_user);
        $id_user = htmlspecialchars($id_user);
        $newLogin = str_replace("'", "\'", $newLogin);
        $newLogin = htmlspecialchars($newLogin);
        $changeUser = "UPDATE users SET `login`='$newLogin' WHERE id='$id_user'";
        $respuesta = $conn->exec($changeUser);
        
        return ($respuesta);
    }

    public function cambiaMail($id_user, $newMail)
    {
        $conn = $this->dbConnect();

        $id_user = str_replace("'", "\'", $id_user);
        $id_user = htmlspecialchars($id_user);
        $newMail = str_replace("'", "\'", $newMail);
        $newMail = htmlspecialchars($newMail);
        $changeUser = "UPDATE users SET mail='$newMail' WHERE id='$id_user'";
        $respuesta = $conn->exec($changeUser);
        
        return ($respuesta);
    }

    public function isValidPassword($id_user, $mdp)
    {
        $conn = $this->dbConnect();

        $id_user = str_replace("'", "\'", $id_user);
        $id_user = htmlspecialchars($id_user);
        $mdp = str_replace("'", "\'", $mdp);
        $mdp = htmlspecialchars($mdp);
        $getMdp = "SELECT passwd FROM users WHERE id = '$id_user'";
        $respuesta = $conn->query($getMdp);
        $respuesta = $respuesta->fetch(PDO::FETCH_ASSOC);

        $mdp2 = hash("Whirlpool", $mdp);
        if ($respuesta['passwd'] == $mdp2)
            return (TRUE);
        else
            return (FALSE);
    }

    public function cambiaPassword($id_user, $newMDP)
    {
   
        $conn = $this->dbConnect();

        $id_user = str_replace("'", "\'", $id_user);
        $id_user = htmlspecialchars($id_user);
        $newMDP = str_replace("'", "\'", $newMDP);
        $newMDP = htmlspecialchars($newMDP);
        $pass = hash("Whirlpool", $newMDP);
        $changePass = "UPDATE users SET passwd='$pass' WHERE id='$id_user'";
        $respuesta = $conn->exec($changePass);
        if ($respuesta == 1)
            return (TRUE);
        else
            return (FALSE);
    }

    public function reinitialiseMDP($mail)
    {
        $conn = $this->dbConnect();

        $mail = str_replace("'", "\'", $mail);
        $mail = htmlspecialchars($mail);
        $getUser = "SELECT id, `login` FROM users WHERE mail = '$mail'";
        $respuesta = $conn->query($getUser);
        $respuesta = $respuesta->fetch(PDO::FETCH_ASSOC);
        if ($respuesta['id'] && $respuesta['login'])
        {
            $passTmp = $respuesta['login'] . $respuesta['id'] . uniqid();

            $this->cambiaPassword($respuesta['id'], $passTmp);

            $destinataire = $mail;
            $sujet = "Reinitialisation du mot de passe" ;

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: <lgarcia-@student.le-101.fr>' . "\r\n";
            $message = '
            <html>
            <head>
                <title>' . $sujet . '</title>
            </head>
            <body>
                Vous venez de réinitialiser votre mot de passe. <br>
                <p>Votre mot de passe temporaire est : <strong>' . $passTmp . '</strong></p><br>
                Vous pouvez changer votre mot de passe despuis votre profil personnel.
                <p>---------------<br>
                Ceci est un mail automatique, Merci de ne pas y répondre.
                </p>
            </body>
            </html>
            ';
            if (mail($destinataire, $sujet, $message, $headers))
                return ("Mail de reinitialisation envoyé.");
            else
                return ("Le mail de reinitialisation ne peut pas être envoyé.");

        }
        else
            return ("Le mail n'est pas dans la base de données.");
    }

    public function newUser($login, $passwd, $email, $cle)
    {
        $conn = $this->dbConnect();

        $add = "INSERT INTO camagrudb.users (login, passwd, mail, cle) VALUES ('$login', '$passwd', '$email', '$cle')";
        $conn->exec($add);
        
        $path = basename(getcwd());
        $destinataire = $email;
        $sujet = "Activer votre compte" ;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: <lgarcia-@student.le-101.fr>' . "\r\n";
        $message = '
        <html>
        <head>
            <title>' . $sujet . '</title>
        </head>
        <body>
            Bienvenue sur Camagru ' . $login . '. </br>
            Pour activer votre compte, veuillez cliquer sur le lien ci dessous
            ou copier/coller dans votre navigateur internet. </br>
            <a href="http://localhost:8100/' . $path . '/config/activation.php?log='.urlencode($login).'&cle='.urlencode($cle) . '">Verifier compte</a>
            <p>---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.
            </p>
        </body>
        </html>
        ';
        if (mail($destinataire, $sujet, $message, $headers))
            return (TRUE);
        else
            return (FALSE);
    }

    public function preferences($id_user, $activar)
    {
        $conn = $this->dbConnect();

        $change = "UPDATE users SET mailComment='$activar' WHERE id='$id_user'";
        $respuesta = $conn->exec($change);
        if ($respuesta == 1)
            return (TRUE);
        else
            return (FALSE);
    }
}