<?php
include("class/class.php");
include("class/admin.php");
?>
<html>
<head>
</head>
<body>
	<h2>Eliminar Usuario</h2>
	<form name="form_eliminar_usuario" method="post" action="procesa_alfatemp9omega.php?a=1">
		Usuario: <input type="text" name="usuario" required/><br />
		<input type="submit" value="Eliminar" />
	</form>
	<br />
	<h2>Add Tropas</h2>
	<form name="form_add_tropas" method="post" action="procesa_alfatemp9omega.php?a=2">
		<table border="0" cellspacing="0" cellpadding="0" >

		<tr>
			<td><img src="img/elementos/tropas/legionario.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa1" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/pretoriano.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa2" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/triario.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa3" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/caballeria_l.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa4" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/caballeria_p.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa5" value="0" /></td>
		</tr>
		
		<tr>
			<td><img src="img/elementos/tropas/general.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa6" value="0" /></td>
		</tr>
		
		<tr>
			<td><img src="img/elementos/tropas/ariete.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa7" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/onagro.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa8" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/senador.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa9" value="0" /></td>
		</tr>

		<tr>
			<td><img src="img/elementos/tropas/colono.png" class="icono_tropa" width="20" height="20"></td>
			<td><input type="text" name="tropa10" value="0" /></td>
		</tr>

		</table>
		<br />
		<br />
		Ciudad <input type="text" name="ciudad" />
		<br />
		<input type="submit" value="Add" />
	</form>
	<br />
	<h2>Llenar almacen</h2>
	<form name="form_llenar_almacen" method="post" action="procesa_alfatemp9omega.php?a=3">
		Ciudad <input type="text" name="ciudad" />
		<input type="submit" value="Llenar" />
	</form>
	<br />
	<h2>Dar Recursos</h2>
	<form name="form_dar_recursos" method="post" action="procesa_alfatemp9omega.php?a=4">
		Madera <input type="text" name="madera" /><br />
		Barro <input type="text" name="barro" /><br />
		Hierro <input type="text" name="hierro" /><br />
		Cereal <input type="text" name="cereal" /><br />
		Ciudad <input type="text" name="ciudad" />
		<input type="submit" value="Dar" />
	</form>
</body>
</html>