function miModal(imagen)
{
  modalImg = document.getElementById("modalImg");
  modalImg.setAttribute("src", imagen.getAttribute("src"));
  document.getElementById("modal").style.display = "flex";
  formulario = document.getElementById("commentForm");
  formulario.setAttribute("action", "index.php?action=newComment" + "&id_img=" + imagen.getAttribute("value"));
}
//Modificar para actualizar los likes sin actualizar la página
function changeLike()
{
  var xhr = new XMLHttpRequest();
      
  xhr.onreadystatechange = function()
  {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != "")
    {

    };
};

  xhr.open("POST", "config/fotos.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("filtro=" + "../filtros/" + file + "&foto=" + data);

}

function changeForm()
{
  var iniciar = document.getElementById("iniciar");
  var reiniciar = document.getElementById("reiniciar");
  var changeForm = document.getElementById("changeForm");
  iniciar.style.display = "none";
  reiniciar.style.display = "block";
  changeForm.style.display = "none";
}