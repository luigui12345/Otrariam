<?php

class Ranking
{
	private $mysqli;

	public function __construct()
	{
		$this->mysqli=DB::Get();
	}

	public function mostrar_ranking() //Muestra el ranking
	{
		if (isset($_GET['p']) and is_numeric($_GET['p'])) //Si se solicita una pagina valida
		{
			$pagina=ceil($_GET['p']);
		}
		else //Sino estamos en la primer pagina
		{
			$pagina=1;
		}
		$nRegistros=5; //Numero de registros por pagina
		$p1=$pagina*($nRegistros)-$nRegistros;

		$puntos=0; //Puntos para clasificar

		$sql="select * from usuarios";
		$res=$this->mysqli->query($sql);
		$n_paginas=ceil($res->num_rows/$nRegistros); //Numero de paginas

		$sql="select * from usuarios limit $p1,$nRegistros";
		$res=$this->mysqli->query($sql);
		?>
		<table border="0" cellspacing="0" cellpadding="0" class="tabla_ranking">

			<thead>
			<tr>
			<td>Jugador</td>
			<td>Puntuación</td>
			</tr>
			</thead>

			<tbody>

		<?php
		while($reg=$res->fetch_array())
		{
			$sql="select * from mapa where id_usuario = ".$reg['id_usuario'];
			$resp=$this->mysqli->query($sql);
			while($red=$resp->fetch_array())
			{
				$puntos=$puntos+$red['habitantes'];
			}
			?>
			
			<tr>
				<td><a href='perfil.php?usuario=<?php echo $reg['nombre'];?>'><?php echo $reg['nombre']?></a></td>
				<td><?php echo $puntos;?></td>
			</tr>

			<?php
			$puntos=0;
			
		}
		?>
			
			</tbody>
		</table>


		<table border="0" cellspacing="0" cellpadding="0" class="tabla_flechas">
			<tr>

		<?php
		
		/*Flechas*/

		if ($pagina==1)
		{
			?>
				<td><i class="icon-double-angle-left"></i></td>
				<td><i class="icon-angle-left"></i></td>
			<?php
		}
		else
		{
			?>
				<td><a href="ranking.php?p=1" title="Primero"><i class="icon-double-angle-left"></i></a></td>
				<td><a href="ranking.php?p=<?php echo $pagina-1;?>" title="Anterior"><i class="icon-angle-left"></i></a></td>
			<?php
		}


		if ($pagina==$n_paginas)
		{
			?>
				<td><i class="icon-angle-right"></i></td>
				<td><i class="icon-double-angle-right"></i></td>
			<?php
		}
		else
		{
			?>
				<td><a href="ranking.php?p=<?php echo $pagina+1;?>" title="Siguiente"><i class="icon-angle-right"></i></a></td>
				<td><a href="ranking.php?p=<?php echo $n_paginas;?>" title="Último"><i class="icon-double-angle-right"></i></a></td>
			<?php
		}?>
				</tr>
		</table>
		<?php
	}
}

?>