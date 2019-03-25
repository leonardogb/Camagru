<?php ob_start(); ?>
<?php $title = "Profile"; ?>
<?php $style = "css/profile.css" ?>
<?php $user = $_SESSION['user']; ?>

<div class="perfilGeneral">
    <div class="change_login">
        <h2>Modifier Login</h2>
        <form action="index.php?action=newLogin" method="POST" id="changeLogin">
            <h4>Nouveau Login</h4><input type="text" name="newLogin" required>
            <input type="submit" name="submit" value="Envoyer">
        </form>
    </div>
    <div>
    <h2> Modifier adresse mail</h2>
        <form action="index.php?action=newMail" method="POST" id="changeMail">
            <h4>Nouveau Mail</h4><input type="email" name="newMail" required>
            <input type="submit" name="submit" value="Envoyer">
        </form>
    </div>
    <div>
        <h2> Modifier mot de passe</h2>
        <form action="index.php?action=newMDP" method="POST" id="changeMDP">
        <h4>Mot de passe </h4><input type="password" name="oldMDP" required>
        <h4>Nouveau mot de passe</h4><input type="password" name="newMDP" required>
        <h4>Confirmez votre mot de passe</h4><input type="password" name="mdp2" required>
            <input type="submit" name="submit" value="Envoyer">
        </form>
    </div>
    
    </div>
    <div>
        <form action="index.php?action=changePreferences" method="POST">
            <h4>Mail de notification pour les commentaires d'image : <strong><?php echo $estado; ?></strong>
            <input type="submit" name="submit" value="Changer"></h4>
        </form>
        </div>
    <?php
        if (isset($_SESSION['id_user'], $_SESSION['user']) && $_SESSION['id_user'] == 1 && $_SESSION['user'] == "admin")
        {
            print("<div><a class=\"reset\" href=\"index.php?action=reset\">Reset Database</a></div>");
        }
    ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>