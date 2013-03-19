<?php
include("class/class.php");
$ald=new Aldea();
$ald->comprobar_recursos('no');
$ciudad=$_SESSION['ju_ciudad'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aldea</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/aldea.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
-->

</head>
<body onLoad="actualiza_recursos()">


<nav><p><a href="logout.php">Salir</a></p></nav>



<div id="wrap">



<div id="top">

    <div id="menu">

        <?php include("include/menu.php"); ?>

    </div>

</div>




<div id="bottom">

    <div id="left">
        <?php include("include/left.php");?>
    </div>

    <div id="right">

        <div id="recursos">

        <?php include("include/recursos.php"); ?>

        </div>

        <div id="wrap_aldea">

        
        <div id="aldea">





<div id="wrapper_recursos">
<?php
$ald->datos_edificios('leñador');
$ald->datos_edificios('barrera');
$ald->datos_edificios('mina');
$ald->datos_edificios('granja');
?>


</div><!--Wrapper_recursos-->



        <div id="tiempo">

        </div>


        
        </div>


            <div id="info_aldea">

                <?php include("include/produccion.php");?>

            </div>




        </div>



</div><!--Aldea-->


</div>



</div>


</body>
</html>
<?php
$ald->comprobar_recursos('si');
?>
