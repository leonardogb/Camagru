var video = document.getElementById("video");
var canvas = document.getElementById("canvas");
var imgUpload = document.getElementById("imgUpload");
var width = 650;
var height = 600;
var file = "";
var videoFunciona = false;
var data = null;
var constraints = {audio: false, video: true};
var image = new Image();

imgUpload.onchange = function(event)
{
  
  
  canvas.style.display = "block";
  canvas.width = 650;
  canvas.height = 600;
  
  if (this.files[0])
    image.src = window.URL.createObjectURL(this.files[0]);

  image.addEventListener("load", cargado);

  function cargado(e)
  {
    canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
    data = canvas.toDataURL('image/png');
    video.style.display = "none";
    
    if (videoFunciona)
    {
      let stream = video.srcObject;
      let tracks = stream.getTracks();
    
      tracks.forEach(function(track) {
        track.stop();
      });
    
      video.srcObject = null;
    }
    videoFunciona = false;
      
  }

}

navigator.mediaDevices.getUserMedia(constraints).then(videoOk).catch(videoKo);

function videoOk(mediaStream)
{
  video.srcObject = mediaStream;
  videoFunciona = true;
  video.onloadedmetadata = function(event)
  {
    video.play();
  };
  document.getElementById('video').style.display = "block";
  document.getElementById('canvas').style.display = "none";
}

function videoKo(error)
{
  console.log(error.name + ": " + error.message);
  document.getElementById('imgUpload').style.display = "block";
  document.getElementById('video').style.display = "none";
}

video.addEventListener('canplay', function(ev)
{
      height = video.videoHeight / (video.videoWidth/width);
  }, false);

startbutton.addEventListener('click', function(ev)
{
    hacerfoto();
  ev.preventDefault();
}, false);

function hacerfoto()
{
    canvas.width = width;
    canvas.height = height;
    if (videoFunciona)
    {
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      data = canvas.toDataURL('image/png');
    }

    if (document.getElementById('gato').checked)
      file = "gato.png";
    else if (document.getElementById('bigote').checked)
      file = "bigote.png";
    else if (document.getElementById('caca').checked)
      file = "caca.png";
    else if (document.getElementById('marco').checked)
      file = "marco.png";

    

    var xhr = new XMLHttpRequest();
  
    xhr.onreadystatechange = function()
    {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != "")
      {
        var respuesta = xhr.responseText.split(' ');

        nuevaImg = document.createElement('img');
        newDiv = document.createElement('div');
        newDiv.style.width = "100%";

        nuevaImg.src = respuesta[0];
        newDiv.setAttribute("id", respuesta[1]);
        nuevaImg.setAttribute("value", respuesta[1]);
        nuevaImg.setAttribute("onclick", "deleteImg(this)");
        newDiv.appendChild(nuevaImg);
        minifotos = document.getElementById('minifotos');
        minifotos.appendChild(newDiv);

        if (canvas.getAttribute("style") == "display: block;")
          canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
        
    };
  };
  if (file && file != "" && data)
  {
    xhr.open("POST", "config/fotos.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("filtro=" + "../filtros/" + file + "&foto=" + data);
  }
    
}

function mostrarbt()
{
  document.getElementById('startbutton').style.display = "block";
  if (document.getElementById('gato').checked)
  {
    document.getElementById('gatoV').style.display = "block";
    document.getElementById('bigoteV').style.display = "none";
    document.getElementById('cacaV').style.display = "none";
    document.getElementById('marcoV').style.display = "none";
  }
  else if (document.getElementById('bigote').checked)
  {
    document.getElementById('gatoV').style.display = "none";
    document.getElementById('bigoteV').style.display = "block";
    document.getElementById('cacaV').style.display = "none";
    document.getElementById('marcoV').style.display = "none";
  }
  else if (document.getElementById('caca').checked)
  {
    document.getElementById('gatoV').style.display = "none";
    document.getElementById('bigoteV').style.display = "none";
    document.getElementById('cacaV').style.display = "block";
    document.getElementById('marcoV').style.display = "none";
  }
  else if (document.getElementById('marco').checked)
  {
    document.getElementById('gatoV').style.display = "none";
    document.getElementById('bigoteV').style.display = "none";
    document.getElementById('cacaV').style.display = "none";
    document.getElementById('marcoV').style.display = "block";
  }
}

function deleteImg(img)
{
var xhr = new XMLHttpRequest();

xhr.onreadystatechange = function()
{
  if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
  {
    var imagen = document.getElementById(img.getAttribute("value"));
    imagen.remove();
  }
}

xhr.open("POST", "index.php?action=deleteImg", true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.send("id_img=" + img.getAttribute("value"));
}