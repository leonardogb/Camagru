<?php

session_start();

require ("controller/controller.php");
require ("config/database.php");

if (isset($_POST['submit']) && $_POST['submit'] == "Déconnexion")
{
    unset($_SESSION['user']);
    unset($_SESSION['id_user']);
    if (isset($_SESSION['error']))
        unset($_SESSION['error']);
    if (isset($_SESSION['message']))
        unset($_SESSION['message']);
}
    
try
{
    @$conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
}
catch (PDOException $e)
{
    header("location: config/setup.php");
}

try
{

    if (isset($_SESSION['user'], $_SESSION['id_user']))
    {
        if (isset($_GET['action']))
        {
            if ($_GET['action'] == "galeria")
            {
                if (isset($_GET['start']))
                    $start = $_GET['start'];
                else
                    $start = 0;
                galeria($start);
            }
                
            else if ($_GET['action'] == "foto")
                fotomaton();
            else if ($_GET['action'] == "profile")
            {
                profile();
            }
            else if ($_GET['action'] == "newComment")
            {
                if (isset($_GET['id_img'], $_POST['comment'], $_POST['submit']) && !empty($_POST['comment']) && $_POST['submit'] == "Send")
                {
                    
                    $comment = htmlspecialchars($_POST['comment']);
                    addComentario($_SESSION['id_user'], $_GET['id_img'], $comment);
                }
            }
            else if ($_GET['action'] == "newLike")
            {
                if (isset($_GET['id_img']) && !empty($_GET['id_img']))
                    newLike($_SESSION['id_user'], $_GET['id_img']);
            }
            else if ($_GET['action'] == "newLogin")
            {
                if (isset($_POST['newLogin'], $_POST['submit']) && !empty($_POST['newLogin']))
                {
                    $newLogin = htmlspecialchars($_POST['newLogin']);
                    changeLogin($_SESSION['id_user'], $newLogin);
                }
            }
            else if ($_GET['action'] == "newMail")
            {
                if (isset($_POST['newMail'], $_POST['submit']) && !empty($_POST['newMail']))
                {
                    $newMail = htmlspecialchars($_POST['newMail']);
                    if (preg_match("#^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$#", $newMail))
                        changeMail($_SESSION['id_user'], $newMail);
                    else
                    {
                        $_SESSION['error'] = "Votre adresse mail n'est pas valide";
                        header('location: index.php?action=profile');
                    }
                }
            }
            else if ($_GET['action'] == "newMDP")
            {
                if (isset($_POST['oldMDP'], $_POST['newMDP'], $_POST['mdp2'], $_POST['submit']) && !empty($_POST['oldMDP']) && !empty($_POST['newMDP']))
                {
                    $oldMDP = htmlspecialchars($_POST['oldMDP']);
                    $newMDP = htmlspecialchars($_POST['newMDP']);
                    $mdp2 = htmlspecialchars($_POST['mdp2']);
                    if ($newMDP == $mdp2)
                    {
                        if (preg_match("#[0-9]{2,}#", $newMDP) && strlen($newMDP) > 7)
                        {
                            changePassword($_SESSION['id_user'], $oldMDP, $newMDP);
                        }
                        else
                            $_SESSION['error'] = "Votre mot de passe doit contenir un minimum de 8 caractères dont au moins 2 chiffres";
                    }
                    else
                        $_SESSION['error'] = "Le mot de passe de confirmation n'est pas valide";
                    header('location: index.php?action=profile');
                }
            }
            else if ($_GET['action'] == "reset")
                resetdb();
            else if (isset($_POST['submit']) && $_GET['action'] == "changePreferences" && $_POST['submit'] == "Changer")
                changePreferences($_SESSION['id_user']);
            else if ($_GET['action'] == "deleteImg")
            {
                if (isset($_POST['id_img']))
                {
                    deleteImg($_SESSION['id_user'], $_POST['id_img']);
                }
            }
                
        } 
        else
            fotomaton();
    }
    else if (isset($_GET['action']) && $_GET['action'] == "galeria")
    {
        if (isset($_GET['start']))
            $start = $_GET['start'];
        else
            $start = 0;
        galeria($start);
    }
        
    else if (isset($_GET['action']) && $_GET['action'] == "acceso")
        acceso();
    else if (isset($_GET['action']) && $_GET['action'] == "reMDP")
    {
        if (isset($_POST['submit'], $_POST['mailRec']) && $_POST['submit'] == "Reinitialise")
        {
            $mail = htmlspecialchars($_POST['mailRec']);
            reiniMail($mail);
        }
    }
    else if (isset($_GET['action']) && $_GET['action'] == "newUser")
    {
        if (isset($_POST['submit'], $_POST['login'], $_POST['passwd'], $_POST['passwd2'], $_POST['email']) && $_POST['submit'] == "Create account")
        {
            $login = htmlspecialchars($_POST['login']);
            $login = str_replace("'", "\'", $login);
            if (preg_match("#^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$#", $_POST['email']))
            {
                $email = $_POST['email'];

                $email = str_replace("'", "\'", $email);
                $email = htmlspecialchars($email);
                $passwd = str_replace("'", "\'", $_POST['passwd']);
                $passwd = htmlspecialchars($passwd);
                $passwd = hash("Whirlpool", $passwd);
                $passwd2 = str_replace("'", "\'", $_POST['passwd2']);
                $passwd2 = htmlspecialchars($passwd2);
                $passwd2 = hash("Whirlpool", $passwd2);
                $cle = hash("Whirlpool", $email . $login . uniqid());

                if ($passwd == $passwd2)
                {
                    if (preg_match("#[0-9]{2,}#", $_POST['passwd']) && strlen($_POST['passwd']) > 7)
                    {
                        if (strlen($login) >= 30)
                        {
                            $_SESSION['error'] = "Votre login est trop long";
                            header('location: index.php?action=acceso');
                        }
                        else
                        {
                            newUsuario($login, $passwd, $email, $cle);
                        }
                    }
                    else
                    {
                        $_SESSION['error'] = "Votre mot de passe doit contenir un minimum de 8 caractères dont au moins 2 chiffres";
                        header('location: index.php?action=acceso');
                    }
                }
                else
                {
                    $_SESSION['error'] = "Votre mot de passe de confirmation n'est pas valide";
                    header('location: index.php?action=acceso');
                }
            }
            else
            {
                $_SESSION['error'] = "Votre adresse mail n'est pas valide";
                header('location: index.php?action=acceso');
            }

            
        }
    }
    else if (isset($_GET['action']) && $_GET['action'] == "entrar")
    {
        if (isset($_POST['login'], $_POST['passwd'], $_POST['submit']) && $_POST['submit'] == "Login")
        {
            $login = htmlspecialchars($_POST['login']);
            $pass = hash("Whirlpool", htmlspecialchars($_POST['passwd']));
            accesoLogin($login, $pass);
        }
    }
    else
    {
        if ((isset($_POST['submit']) && $_POST['submit'] == "Login") || isset($_SESSION['error']))
            login();
        else if (isset($_GET['action']) && $_GET['action'] == "newComment")
        {
            $_SESSION['error'] = "Vous devez être connecté pour commenter.";
            header('location: index.php?action=galeria');
        }
        else if (isset($_GET['action']) && $_GET['action'] == "newLike")
        {
            $_SESSION['error'] = "Vous devez être connecté pour liker.";
            header('location: index.php?action=galeria');
        }
        else
            acceso();
    }
}
catch (PDOException $e)
{
    print($e->getMessage());
}