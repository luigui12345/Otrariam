<?php
include("class/class.php");
include("class/perfil.php");
$ald=new Aldea();
$per=new Perfil();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Perfil</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/aldea.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
-->

</head>
<body>




<div id="wrap">
<div id="wrap_center">


<div id="top">
    
    <div id="logo">
        
    </div>

    <div id="menu">
        <?php include("include/menu.php"); ?>
    </div>

</div>


<div id="bottom">

    <div id="left">
        <?php include("include/left.php");?>
    </div>

    <div id="right">


        <div id="wrap_aldea">
        
        <div id="wrap_centro">

            <?php
			$per->mostrar_pefil();
			?>

        </div><!--/#construir-->


        <div id="info_aldea">

        </div>


        </div><!--/#wrap_aldea-->

    </div><!--/#right-->


</div><!--/#bottom-->


</div>
</div><!--/#wrap-->



</body>

</html>