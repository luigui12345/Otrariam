<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/estilos.css" />

<!--<script type="text/javascript" src="js/jquery.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--Slider-->
<link rel="stylesheet" type="text/css" href="estilos/slider.css" />


<!--Google Analytics-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39366535-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


</head>
<body>


<nav>

    <ul id="center_nav">

    <li id="menu_logo"><img src="img/elementos/logos/logo.png" id="logo"></li>

    <li id="menu_login">
            
            <div id="login"><p>Iniciar sesión</p></div>

            <!--Login-->
            <form class="form-1" id="form_login" name="form_login" method="post" action="procesa_login.php">
                <p class="field">
            <input type="text" placeholder="Usuario" maxlength="30" name="nombre" required>
                    <i class="icon-user"></i>
                </p>
                <p class="field">
            <input type="password" placeholder="Contraseña" maxlength="30" name="password" required/>
                    <i class="icon-lock"></i>
                </p>       
                <p class="submit">
            <button type="submit" name="submit"><i class="icon-arrow-right icon-large"></i></button>
                </p>

                <!--<p class="recordar">
                    <input type="checkbox"> No cerrar sesión
                </p>-->
            </form>

    </li>

    <a href="https://github.com/Flash-back/JuegoEnProceso" target="_blank" title="Descargar código fuente">
        <li id="menu_github">
            <i class="icon-github"></i>
        </li>
    </a>

    </ul><!--center_nav-->

</nav>


<div id="wrap">

<div id="center_wrap">


<div id="intro">
    
    <h2>Expande. Conquista. Domina.</h2>
    <br>
    <p>
        <strong>Otrariam</strong> es un apasionante juego de navegador que te sumerge en la Antigua Roma.
        Deberás convertir tu aldea en un gran imperio mediante la diplomacia, la guerra y el comercio.
    </p>

</div>


<div id="menu_l">


<div class="slider">
    <input type="radio" name="slide_switch" id="id1"/>
    <label for="id1">
        <img src="img/slider/slider2.png"/>
    </label>
    <img src="img/slider/slider2.png"/>
    
    <!--Lets show the second image by default on page load-->
    <input type="radio" name="slide_switch" id="id2" checked="checked"/>
    <label for="id2">
        <img src="img/slider/slider0.png" width="100"/>
    </label>
    <img src="img/slider/slider0.png"/>
    
    <input type="radio" name="slide_switch" id="id3"/>
    <label for="id3">
        <img src="img/slider/slider1.png" width="100"/>
    </label>
    <img src="img/slider/slider1.png"/>
    
    <input type="radio" name="slide_switch" id="id4"/>
    <label for="id4">
        <img src="img/slider/slider3.png" width="100"/>
    </label>
    <img src="img/slider/slider3.png"/>

    <input type="radio" name="slide_switch" id="id5"/>
    <label for="id5">
        <img src="img/slider/slider4.png" width="100"/>
    </label>
    <img src="img/slider/slider4.png"/>
</div>


</div><!--menu_r-->



<div id="menu_r">	

    <div id="wrap_registro">

    <div id="intro_registro">
        <span>Registro</span><br/>
        <p>Regístrate en unos segundos. Comienza la historia de tu imperio.</p>
    </div>
    
    <div id="registro">



            <form class="form" name="form_registro" method="post" action="procesa_registro.php">

            <div class="seccion_input">
                <p class="field">
            <input type="text" name="nombre" placeholder="Usuario" maxlength="50" class="input">
                    <i class="icon-user icon-large"></i>
                </p>
            </div>
            
            <div class="seccion_input">
                <p class="field">
            <input type="password" name="password" placeholder="Contraseña" maxlength="50" class="input">
                    <i class="icon-lock icon-large"></i>
                </p>
            </div>

             <div class="seccion_input">
                <p class="field">
            <input type="text" name="correo" placeholder="Correo electrónico" maxlength="50" class="input">
                    <i class="icon-envelope icon-large"></i>
                </p>
            </div>

            <input type="submit" value="Registrarse" class="input_login">

        </form>


    </div><!--#Registro-->
    
    </div><!--#Wrap_Registro-->
        
</div><!--menu_l-->

</div><!--center_wrap-->


</div><!--Wrap-->




</body>
</html>
