<?php
include("class/class.php");
$ald=new Aldea();
$ald->comprobar_recursos('no');
$tro=new Tropas();
$ciudad=$_SESSION['ju_ciudad'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enviar Tropas</title>


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
 
     
<form id="enviar_tropas" name="form_mover_tropas" method="post" action="procesa_tropas.php">

	<table border="0" cellspacing="0" cellpadding="0" >

	<h3>Enviar tropas</h3>

	<tr>
		<td><img src="img/elementos/tropas/legionario.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_1" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa1');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/pretoriano.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_2" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa2');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/triario.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_3" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa3');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/caballeria_l.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_4" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa4');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/caballeria_p.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_5" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa5');?></td>
	</tr>
	
	<tr>
		<td><img src="img/elementos/tropas/general.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_6" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa6');?></td>
	</tr>
	
	<tr>
		<td><img src="img/elementos/tropas/ariete.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_7" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa7');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/onagro.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_8" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa8');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/senador.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_9" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa9');?></td>
	</tr>

	<tr>
		<td><img src="img/elementos/tropas/colono.png" class="icono_tropa"></td>
		<td><input type="text" name="tropa_10" value="0" /></td>
		<td><?php $tro->mostrar_tropa('tropa10');?></td>
	</tr>

	</table>

	<?php
	if (isset($_GET['x']) and isset($_GET['y']))
	{
	?>
		<br/><br/>
		<b>X</b> <input type="text" name="x" value="<?php echo $_GET['x'];?>" required/>
		<b>Y</b> <input type="text" name="y" value="<?php echo $_GET['y'];?>" required/>
	<?php
	}
	else
	{
	?>
		<b>X</b> <input type="text" name="x" required/>
		<b>Y</b> <input type="text" name="y" required/>
	<?php
	}
	?>
	<br /><br/>
	<p>Reforzar: </p> <input type="radio" name="accion" value="reforzar" required/><br />
	<!--Atracar: <input type="radio" name="accion" value="atracar" /><br />-->
	<p>Atacar: </p> <input type="radio" name="accion" value="atacar" checked="checked" /><br />
	<br />
	
	

	<input type="submit" value="Enviar tropas" class="boton">

</form>


        </div><!--/#wrap_centro-->


        <div id="info_aldea">

        </div>


        </div><!--/#wrap_aldea-->

    </div><!--/#right-->


</div><!--/#bottom-->


</div>
</div><!--/#wrap-->







<?php
/*if (isset($_GET['accion']))
{
	?>
	<h3>Confirmar <?php echo $_POST['accion'];?></h3>
	<br />
	Legionarios: <?php echo $_POST['tropa_1'];?><br />
	Pretorianos: <?php echo $_POST['tropa_2'];?><br />
	Triarios: <?php echo $_POST['tropa_3'];?><br />
	Caballeria Ligera: <?php echo $_POST['tropa_4'];?><br />
	Caballeria Pesada: <?php echo $_POST['tropa_5'];?><br />
	Generales: <?php echo $_POST['tropa_6'];?><br />
	Arietes: <?php echo $_POST['tropa_7'];?><br />
	Onagros: <?php echo $_POST['tropa_8'];?><br />
	Senadores: <?php echo $_POST['tropa_9'];?><br />
	Colonos: <?php echo $_POST['tropa_10'];?><br />
	<?php
}*/
?>

</body>
</html>
