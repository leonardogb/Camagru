<?php

?>
<?php $title = "Access to CAMAGRU"; ?>
<?php $style = "css/index.css"; ?>
<?php ob_start(); ?>

<div class="general">
    <div class="register">
        <h2>Register</h2>
        <form action="index.php?action=newUser" method="POST">
            <input type="text" name="login" placeholder="Username" required><br/>
            <input type="password" name="passwd" placeholder="Password" required><br/>
            <input type="password" name="passwd2" placeholder="Confirm password" required><br/>
            <input type="email" name="email" placeholder="Email" required><br/>
            <input type="submit" name="submit" value="Create account">
        </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require ('template2.php'); ?>