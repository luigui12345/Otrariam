<?php
class Mapa
{
	private $id_ciudad;
	private $mysqli;
	private $t_actual;
	private $comerciantes_disponibles;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->id_ciudad=$_SESSION['ju_ciudad'];
		$this->t_actual=strtotime(date('Y-m-d H:i:s'));
		$sql="select x,y from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		$this->x=$reg['x'];
		$this->y=$reg['y'];
	}

	public function variables_mapa() //Configura las coordenadas para que no se salgan del mapa
	{
		if (is_numeric($_GET['x']) && is_numeric($_GET['y']))
		{
			$this->x = safe($_GET['x']);
			$this->y = safe($_GET['y']);

			//Si se sale del mapa
			if ($this->x > 8)
			{
				$this->x = 8;
			}
			if ($this->x < 3)
			{
				$this->x = 3;
			}
			if ($this->y > 8)
			{
				$this->y = 8;
			}
			if ($this->y < 3)
			{
				$this->y = 3;
			}
		}

		else
		{
			header("Location:index.php");
		}
	}

	public function mapa() //Crea el mapa
	{
		$this->variables_mapa(); //Inicia las coordenadas

		for ($i=-2;$i<3;$i++) //Hacemos que se muestren 5 filas
		{
			for ($j=-2;$j<3;$j++) //Hacemos que se muestren 5 cuadros por fila
			{	
				$sql="select nombre,x,y from mapa where x = $this->x + $j and y = $this->y + $i"; //Comprueba si el terreno esta ocupado
				$res=$this->mysqli->query($sql);
				$reg=$res->fetch_array();
				if ($reg["nombre"]!="Terreno Libre") //Sino esta ocupado se pone verde
				{
					?>
					<a href="aldea.php?x=<?php echo $reg['x'];?>&y=<?php echo $reg['y'];?>"><div class="casilla1" title="<?php echo $reg["nombre"]; ?>"><p><?php echo $reg['x']."-".$reg['y']; ?></p><?php		
				}
				else //Si esta ocupado se pone rojo
				{
					?>
					<a href="#"><div class="casilla2" title="<?php echo $reg["nombre"]; ?>"><p><?php echo $reg['x']."-".$reg['y']; ?></p><?php	
				}
				
				?>
				</div>
				</a>
				<?php
			}
		}
	}

	public function botones_mapa() //Muestra los botones para navegar por el mapa
	{
		if (!isset($_GET['ajax'])) //Sino estamos moviendo el mapa por ajax
		{
			?>
			<div id="tabla_mapa">

				<div id="mapa_up" onclick='mover("arriba")'></div>

				<div id="izquierda"><div id="mapa_left" onclick='mover("izquierda")'></div></div>
				
				<div id="derecha"><div id="mapa_right" onclick='mover("derecha")'></div></div>
				
				<div id="mapa_bottom" onclick='mover("abajo")'></div>

			</div>
			<?php
		}
	}

	public function detalles_aldea()//Datos de la aldea al hacer click en ella en el mapa
	{
		if (is_numeric($_GET['x']) && is_numeric($_GET['y']))
		{
			$x=safe($_GET['x']);
			$y=safe($_GET['y']);
			//Datos de la aldea seleccionada
			$sql="select id_usuario,x,y,habitantes from mapa where x = $x and y = $y limit 1";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();
			?>

			<div id="perfil_aldea">

			<!--Detalles de la aldea-->
			<p>Eje X: <b><?php echo $reg['x']; ?></b></p>
			<p>Eje Y: <b><?php echo $reg['y']; ?></b></p>

			<p>Propietario: <b><?php echo Datos::usuario($reg['id_usuario']);?></b></p>
			<p>Habitantes: <b><?php echo $reg['habitantes']; ?></b></p>
			</div>

			<img src="img/elementos/aldea/ciudad.png" class="img_aldea">

			<div id="bottom_perfil_aldea">
			<a href='edificio.php?s=<?php echo Datos::slotPorEdificio('mercado');?>&x=<?php echo $reg['x']; ?>&y=<?php echo $reg['y']; ?>'>Enviar Recursos<i class="icon-double-angle-right"></i></a><br />
			<a href='redactar_mensaje.php?usuario=<?php echo Datos::usuario($reg['id_usuario']);?>'>Enviar Mensaje<i class="icon-double-angle-right"></i></a><br />
			<a href='mover_tropas.php?x=<?php echo $reg['x']; ?>&y=<?php echo $reg['y']; ?>'>Enviar Tropas<i class="icon-double-angle-right"></i></a>
			</div>

			<?php
		}

	}
}

?>