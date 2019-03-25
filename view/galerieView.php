
<?php ob_start(); ?>
<?php $title = "Galerie"; ?>
<?php
    if (isset($_SESSION['user']))
        $user = $_SESSION['user'];
    else
        $user = "Invité(e)"; ?>
<?php $style = "css/photos.css" ?>
<div id="fotos">
        <?php
            if ($fotos)
            {
                foreach ($fotos as $key)
                {
                    print ("<div class=\"divImg\"><img src=\"ffinales/" . $key['img'] . "\" value=\"" . $key['id'] . "\" onclick=\"miModal(this)\">");
                    if ($likes)
                    {
                        $i = 0;
                        foreach ($likes as $like)
                        {
                            if ($key['id'] == $like['id_galeria'])
                                $i++;
                        }
                        print("<div class=\"like\"><a href=\"index.php?action=newLike&id_img=" . $key['id'] . "\">Like " . $i . "</a></div>");
                    }
                    else
                    {
                        print("<div class=\"like\"><a href=\"index.php?action=newLike&id_img=" . $key['id'] . "\">Like 0</a></div>");
                    }
                    if ($comentarios)
                    {
                        print("<div class=\"commentBlock\">");
                        foreach ($comentarios as $keyComment)
                        {
                            if ($keyComment['id_galeria'] == $key['id'])
                            {
                                $datetime = date_create($keyComment['comment_date']);
                                $fechaHora = date_format($datetime, ' d/m/Y à H:i:s');
                                print("<p><strong>" . $keyComment['author'] . " :</strong>" . $fechaHora .
                                "</p><p>" . $keyComment['comment'] . "</p>");
                            }
                        }
                        print("</div>");
                    }
                    print("</div>");
                }
                
            }
            
        ?>
        <?php
            print("</div>");
            if ($nbPages > 1)
            {
                print("<div id=liens>");
                $i = 0;
                while ($i < $nbPages)
                {
                    print("<a href=\"index.php?action=galeria&start=" . $i . "\"> Page " . $i . " | </a>");
                    $i++;
                }
                
            }
        ?>
</div>
<div id="modal">
    <div id="conteneur">
        <span id="close">&times;</span>
        <img id="modalImg">
        <div>
            <form method="POST" id="commentForm">
                <textarea maxlength="254" rows="4" cols="50" name="comment" form="commentForm" placeholder="Comentarios..." required></textarea>
                <input type="submit" name="submit" value="Send">
            </form>
        </div>
    </div>
</div>
<script>
    var modal = document.getElementById("modal");
    var span = document.getElementById("close");
    window.onclick = function(event) {
    if (event.target == modal) {
      document.getElementById("modal").style.display = "none";
    }
  }
  span.onclick = function() {
    document.getElementById("modal").style.display = "none";
  }
</script>
<script src="js/gallery.js"></script>
<?php $content = ob_get_clean(); ?>
<?php
    if (isset($_SESSION['user']))
        require ("template.php");
    else
        require ("template2.php");?>