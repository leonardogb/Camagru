<?php

require_once('model/UserManager.php');
require_once('model/ImageManager.php');
require_once('model/CommentManager.php');
require_once('model/LikesManager.php');

function fotomaton()
{
    $imageManager = new ImageManager();

    $fotosUser = $imageManager->get_imgdb($_SESSION['id_user']);
    require ('view/takePhoto.php');
}

function acceso()
{
    require ('view/access.php');
}

function login()
{
    require ('view/loginView.php');
}

function galeria($start)
{
    $imageManager = new ImageManager();
    $commentManager = new CommentManager();
    $likesManager = new LikesManager();
    
    $limit = 6;
    $nbImg = $imageManager->get_nbImg();
    $nbPages =  intval($nbImg / $limit);
    if ($nbImg % $limit)
        $nbPages++;
    $start = str_replace("'", "\'", $start);
    if ($start >= 0)
    {
        $fotos = $imageManager->get_rangeImg(6, $start * $limit);
        $comentarios = $commentManager->get_commentImg();
        $likes = $likesManager->get_likes();
        require ('view/galerieView.php');
    }
}

function addComentario($id_user, $id_galeria, $comment)
{
    $userManager = new UserManager();
    $commentManager = new CommentManager();
    $imageManager = new ImageManager();

    $author = $userManager->get_user($id_user);
    $author = $author['login'];
    $commentClean = str_replace("'", "\'", $comment);

    $imagen = $imageManager->get_imgById($id_galeria);
    $userImg = $userManager->get_user($imagen['id_user']);
    $mailUserImg = $userImg['mail'];

    if ($id_user == $userImg['id'] || $userImg['mailComment'] == 0)
        $envoyer = FALSE;
    else
        $envoyer = TRUE;

    $commentManager->addComment($id_user, $id_galeria, $author, $commentClean, $mailUserImg, $envoyer);
    
    header('Location: index.php?action=galeria');
}

function profile()
{
    $userManager = new UserManager();

    $user = $userManager->get_user($_SESSION['id_user']);
    if ($user['mailComment'] == 1)
        $estado = "Activé";
    else
        $estado = "Désactivé";
    require ('view/profileView.php');
}

function changeLogin($id_user, $newLogin)
{
    $userManager = new UserManager();

    $affectedLines = $userManager->cambiaLogin($id_user, $newLogin);
    if ($affectedLines == 1)
    {
        $_SESSION['user'] = $newLogin;
        $_SESSION['message'] = "Votre login a été changé";
    }
    header('Location: index.php?action=profile');
}

function changeMail($id_user, $newMail)
{
    $userManager = new UserManager();

    $affectedLines = $userManager->cambiaMail($id_user, $newMail);
    if ($affectedLines)
    {
        $_SESSION['message'] = "Votre adresse mail a été changée";
    }
    header('Location: index.php?action=profile');
}

function changePassword($id_user, $oldMDP, $newMDP)
{
    $userManager = new UserManager();

    if ($userManager->isValidPassword($id_user, $oldMDP))
    {
        if ($userManager->cambiaPassword($id_user, $newMDP))
            $_SESSION['message'] = "Votre mot de passe a été changé";
        else
            $_SESSION['error'] = "Erreur";
    }
    else
        $_SESSION['error'] = "Le mot de passe n'est pas valide.";
    header('location: index.php?action=profile');
}

function resetdb()
{
    $userManager = new UserManager();

    $userManager->dropdb();
    $_SESSION['message'] = "La base de données a été réinitialisée";
    header('location: index.php');
}

function newLike($id_user, $id_img)
{
    $likesManager = new LikesManager();

    if ($likesManager->likeExist($id_user, $id_img))
        $likesManager->deleteLike($id_user, $id_img);
    else
        $likesManager->addLike($id_user, $id_img);
    header('location: index.php?action=galeria');
}

function reiniMail($mail)
{
    $userManager = new UserManager();

    $_SESSION['error'] = $userManager->reinitialiseMDP($mail);
    header('location: index.php?action=Login');
}

function newUsuario($login, $passwd, $email, $cle)
{
    $userManager = new UserManager();

    if ($userManager->user_exist($login) || $userManager->mail_exist($email))
        $_SESSION['error'] = "Le login ou l'adresse mail n'est pas disponible";
    else
    {
        if($userManager->newUser($login, $passwd, $email, $cle))
            $_SESSION['message'] = "Votre compte à été creé, merci de vérifier votre boîte mail";
        else
            $_SESSION['error'] = "Erreur lors de la création du compte";
    }

    header('location: index.php?action=acceso');
}

function accesoLogin($login, $pass)
{
    $userManager = new UserManager();

    if (!$userManager->user_exist($login))
    {
        $_SESSION['error'] = "L'utilisateur ou le mot de passe n'est pas valide";
    }
    else
    {
        $userdb = $userManager->get_userLogin($login);
        if ($userdb['active'] == 1)
        {
            if ($userdb['passwd'] == $pass)
            {
                $_SESSION['user'] = $login;
                $_SESSION['id_user'] = $userdb['id'];
            }
            else
                $_SESSION['error'] = "L'utilisateur ou le mot de passe n'est pas valide";
        }
        else if ($userdb['active'] == 0)
            $_SESSION['error'] = "Vous devez activer votre compte pour acceder";
    }
    header('location: index.php');
}

function changePreferences($id_user)
{
    $userManager = new UserManager();

    $user = $userManager->get_user($id_user);
    if ($user['mailComment'] == 0)
        $activar = 1;
    else if ($user['mailComment'] == 1)
        $activar = 0;
    if ($userManager->preferences($id_user, $activar))
        $_SESSION['message'] = "Vos préférences ont été changées";
        
    header('location: index.php?action=profile');
}

function deleteImg($id_user, $id_img)
{
    $imageManager = new ImageManager();
    $commentManager = new CommentManager();
    $likesManager = new LikesManager();

    $imageManager->deleteImage($id_user, $id_img);
    $commentManager->deleteComment($id_img);
    $likesManager->deleteLikes($id_img);
}
?>