<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link href="css/style.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?= $style ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    </head>
        
    <body>
        <div class="menu">
            <a href="index.php?action=galeria" >Galerie</a>
            <a href="index.php?action=foto">Photomaton</a>
            <a href="index.php?action=profile">Profile</a>
        </div>
        <div class="desconectar">
            <h2>Bienvenue <?= $user ?></h2>
            <form action="index.php" method="post">
                <input type="submit" name="submit" value="Déconnexion"/>
            </form>
        </div>
        <?php
            if (isset($_SESSION['message']))
            {
                print("<div><p class=\"message\">" . $_SESSION['message'] . "</p></div>");
                unset($_SESSION['message']);
            }
        ?>
        <?php
            if (isset($_SESSION['error']))
            {
                print("<div><p class=\"error\">" . $_SESSION['error'] . "</p></div>");
                unset($_SESSION['error']);
            }
        ?>
        <?= $content ?>
        <div>
            <div class="footer">© lgarcia-
        </div>
    </body>
</html>