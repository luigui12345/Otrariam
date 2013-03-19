<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/estilos.css" />

<!--<script type="text/javascript" src="js/jquery.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

</head>
<body>


<nav><p>"Veni Vidi Vici" - <span>Julio César</span></p></nav>


<div id="wrap">


<img src="img/elementos/fondos/fondo.jpg" id="fondo">


<div id="wrap_r">


        <div id="contenido">	
                

        <div id="intro">Expande. Conquista. Domina<br><br><span>Solo una capital saldrá vencedora.</span></div>
        

        <div id="wrap_panel">

        <div id="panel"><span id="js_login">Login</span> - <span id="js_registro">Registro</span></div>

        <div id="login">


            <div id="form_login">
                
                <form name="form_login" method="post" action="procesa_login.php">

                <input type="text" placeholder="Usuario" maxlength="30" class="input" name="nombre" required><br><br>
                <input type="password" placeholder="Contraseña" maxlength="30" class="input" name="password" required/><br><br>

                <input type="submit" value="Login" class="input_login">

                </form>

            </div>


            <div id="form_registro">

            <form name="form_registro" method="post" action="procesa_registro.php">

                <input type="text" name="nombre" placeholder="Usuario" maxlength="50" class="input"><br><br>
                <input type="password" name="password" placeholder="Contraseña" maxlength="50" class="input"><br><br>
                <input type="text" name="correo" placeholder="Correo electrónico" maxlength="50" class="input"><br><br>

                <input type="submit" value="Registrarse" class="input_login">

            </form>


            </div>


        </div>

        </div><!--Wrap_panel-->


        
        </div><!--Contenido-->


</div><!--Wrap_r-->


</div><!--Wrap-->




</body>
</html>
