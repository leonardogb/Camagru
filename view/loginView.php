<?php $style = "css/login.css"; ?>
<?php $title = "Login"; ?>
<?php ob_start(); ?>
        <div class="general">
            <div class="login">
                <div id="iniciar">
                <h2>Login</h2>
                    <form action="index.php?action=entrar" method="POST">
                        <input type="text" name="login" placeholder="Username" required><br/>
                        <input type="password" name="passwd" placeholder="Password" required><br/>
                        <input type="submit" name="submit" value="Login">
                    </form>
                </div>
                <div id="changeForm" onclick="changeForm()"><h3>Mot de passe oublié ?</h3></div>
                <div id="reiniciar">
                    <h2>Mot de passe oublié</h2>
                    <form action="index.php?action=reMDP" method="POST">
                        <input type="email" name="mailRec" placeholder="Email" required><br/>
                        <input type="submit" name="submit" value="Reinitialise">
                    </form>
                </div>
                <div class="home" onclick="document.location.href='index.php'">Back to main page</div>
            </div>
                <?php
                if (isset($_SESSION['error']))
                {
                    print("<div><p class=\"error\">" . $_SESSION['error'] . "</p></div>");
                    unset($_SESSION['error']);
                }
                ?>
        </div>
        <script src="js/gallery.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require ('template2.php'); ?>