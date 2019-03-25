<?php if (isset($_SESSION['user'])) { ?>
<?php $title = "Photomaton"; ?>
<?php $style = "css/photos.css"; ?>
<?php $user = $_SESSION['user']; ?>
<?php ob_start(); ?>
<div id="superior">
    <div id="colum1">
        <div class="camara">

            <img id="gatoV" src="filtros/gato.png">
            <img id="bigoteV" src="filtros/bigote.png">
            <img id="cacaV" src="filtros/caca.png">
            <img id="marcoV" src="filtros/marco.png">

            <video id="video" style=""></video>
            <canvas id="canvas"></canvas>
            <div id="startbutton">PHOTO</div>
            
            <div>
                <input type="file" id="imgUpload" name="imgUpload" accept="image/png, image/jpeg" />
            </div>
        </div>
        <div class="selector">
            <form id="select">
                <div>
                    <img class="imgfiltro" src="filtros/gato.png">
                    <input type="radio" name="tipoF" id="gato" value="gato" onclick="mostrarbt()">
                </div>
                <div>
                    <img class="imgfiltro" src="filtros/bigote.png">
                    <input type="radio" name="tipoF" id="bigote" value="bigote" onclick="mostrarbt()">
                </div>
                <div>
                    <img class="imgfiltro" src="filtros/caca.png">
                    <input type="radio" name="tipoF" id="caca" value="caca" onclick="mostrarbt()">
                </div>
                <div>
                    <img class="imgfiltro" src="filtros/marco.png">
                    <input type="radio" name="tipoF" id="marco" value="marco" onclick="mostrarbt()">
                </div>
            </form>
        </div>
    </div>
        <div id="minifotos">
        <?php
            if ($fotosUser)
            {
                foreach ($fotosUser as $key)
                {
                    print ("<div><img id=\"" . $key['id'] . "\" src=\"ffinales/" . $key['img'] . "\" value=\"" . $key['id'] . "\" onclick=\"deleteImg(this)\"></div>");
                }
            }
            
        ?>
        </div>
</div>
        <script src="js/index.js"></script>

<?php $content = ob_get_clean(); ?>
<?php require ('template.php'); ?>
<?php } ?>