<?php
session_start();

if (isset($_POST['login'], $_POST['passwd'], $_POST['submit']) && $_POST['submit'] == "Login")
{
    include ("database.php");
    require ("funciones.php");

    $login = htmlspecialchars($_POST['login']);
    $pass = hash("Whirlpool", htmlspecialchars($_POST['passwd']));

    try
    {
        $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

        if (!user_exist($login))
        {
            $_SESSION['error'] = "L'utilisateur ou le mot de passe n'est pas valide.";
        }
        else
        {
            $sql = $conn->query("SELECT id, passwd, active FROM users WHERE `login` = '$login'");
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            if ($sql['active'] == 1)
            {
                if ($sql['passwd'] == $pass)
                {
                    $_SESSION['user'] = $login;
                    $_SESSION['id_user'] = $sql['id'];
                    header('location: ../index.php');
                }
                else
                    $_SESSION['error'] = "L'utilisateur ou le mot de passe n'est pas valide.";
            }
            else if ($sql['active'] == 0)
                $_SESSION['error'] = "Vous devez activer votre compte pour acceder.\n";
        }
        header('location: ../index.php');
    }
    catch (PDOException $e)
    {
        $_SESSION['error'] = $e->getMessage();
        header('location: ../index.php');
    }
}

?>