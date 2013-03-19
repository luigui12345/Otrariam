<?php
class Tropas
{
	private $id_ciudad;
	private $mysqli;
	private $t_actual;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->id_ciudad=$_SESSION['ju_ciudad'];
		$this->t_actual=strtotime(date('Y-m-d H:i:s'));
	}

	public function mostrar_movimientos_tropas() //Para mostrar los movimientos de tropas que esta realizando mi aldea
	{
		?>
		<div id="movimientos_tropas">
		<?php
		//Buscamos los ataques que estoy haciendo
		echo "<b>Ataques enviados:</b>";
		echo "<hr/>";
		$sql="select * from ataques where id_ciudad_atacante=$this->id_ciudad and (objetivo='atacar' or objetivo ='atracar') order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			//Mostramos un reporte de los datos
			?>
			<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
				
				<thead>
				<tr>
					<td colspan="100%"><b>
						<?php echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>"; ?>
					</b></td>
				</tr>
				</thead>

				<tbody>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo Datos::tropa('tropa'.$i);?></td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo $reg['tropa'.$i];?></td>
				<?php
				}
				?>
				</tr>
				</tbody>
			</table>
			<br />
			<?php
		}
		//Y asi con el resto de movimientos
		
		echo "<b>Ataques recibidos:</b><br />";
		echo "<hr/>";
		$sql="select * from ataques where id_ciudad_atacada=$this->id_ciudad and (objetivo='atacar' or objetivo ='atracar') order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
				
				<thead>
				<tr>
					<td colspan="100%"><b>
						<?php echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>"; ?>
					</b></td>
				</tr>
				</thead>

				<tbody>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo Datos::tropa('tropa'.$i);?></td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo "?";?></td>
				<?php
				}
				?>
				</tr>
				</tbody>
			</table>
			<br />
			<?php
		}
		echo "<b>Refuerzos enviados:</b><br />";
		echo "<hr/>";
		$sql="select * from ataques where id_ciudad_atacante=$this->id_ciudad and objetivo='reforzar' order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> refuerza a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
				
				<thead>
				<tr>
					<td colspan="100%"><b>
						<?php echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>"; ?>
					</b></td>
				</tr>
				</thead>

				<tbody>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo Datos::tropa('tropa'.$i);?></td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo $reg['tropa'.$i];?></td>
				<?php
				}
				?>
				</tr>
				
				</tbody>
			</table>
			<br />
			<?php
		}
		echo "<b>Refuerzos recibidos:</b><br />";
		echo "<hr/>";
		$sql="select * from ataques where id_ciudad_atacada=$this->id_ciudad and objetivo='reforzar' order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> refuerza a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
				
				<thead>
				<tr>
					<td colspan="100%"><b>
						<?php echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>"; ?>
					</b></td>
				</tr>
				</thead>

				<tbody>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo Datos::tropa('tropa'.$i);?></td>
				<?php
				}
				?>
				</tr>
				<tr>
				<?php
				for ($i=1;$i<11;$i++)
				{
				?>
					<td><?php echo "?";?></td>
				<?php
				}
				?>
				</tr>
				
				</tbody>
			</table>
			<br />
			<?php
		}
		?>
			</div>
		<?php
	}

	public function ordenar_reclutar()
	{
		$no_disponible=0;
		$n_tropa=array();
		$n_tropa=array(0,0,0,0,0,0,0,0,0,0);

		for ($i=1;$i<11;$i++) //Asiganmos las tropas que reclutamos a un array
		{
			if (isset($_POST["n_tropa$i"]))
			{
				$n_tropa[$i-1]=$_POST["n_tropa$i"];
			}
		}

		for ($i=0;$i<count($n_tropa);$i++) //Recorremos todas las tropas para hacer comprobaciones
		{
			if (is_numeric($n_tropa[$i]) and $n_tropa[$i] > 0) //Si es un numero positivo lo comprobamos
			{
				$sql="select * from datos_tropas where tropa = 'tropa".($i+1)."'"; //Buscamos los datos de dicha tropa
				$res=$this->mysqli->query($sql);
				$reg=$res->fetch_array();

				$requisitos=explode('|',$reg['requisitos']);
				for($j=0;$j<count($requisitos);$j++) //Comprobamos sus requisitos
				{
					$requisitos2=explode('_',$requisitos[$j]);

					$sql="select * from edificios_aldea where edificio = '$requisitos2[0]' and nivel >= $requisitos2[1] and id_ciudad = $this->id_ciudad";
					$res=$this->mysqli->query($sql);
					if ($res->num_rows==0) //Si no cumple algun requisito se considera no disponible
					{
						$no_disponible=1;
					}
				}

				if ($no_disponible==0) //Si ha pasado los requisitos
				{
					//Comprobamos los costes del reclutamiento
					$costes=array($reg['madera']*$n_tropa[$i],$reg['barro']*$n_tropa[$i],$reg['hierro']*$n_tropa[$i],$reg['cereal']*$n_tropa[$i]);
					$sql="select * from mapa where id_casilla = $this->id_ciudad";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();

					//Si no se puede reclutar
					if ($costes[0]>$red['madera'] || $costes[1]>$red['barro'] || $costes[2]>$red['hierro'] || $costes[3]>$red['cereal'])
					{
						header("Location:militar.php?m=1");
						exit;
					}
					else //Si se puede reclutar
					{
						$sql="update mapa set madera = madera-$costes[0], barro=barro-$costes[1],hierro=hierro-$costes[2],cereal=cereal-$costes[3] where id_casilla = $this->id_ciudad";
						$res=$this->mysqli->query($sql);
						$sql="select * from cola_produccion where id_ciudad = $this->id_ciudad and tropa='tropa".($i+1)."'";
						$res=$this->mysqli->query($sql);

						if ($res->num_rows > 0)
						{
							$ref=$res->fetch_array();
							$sql="update cola_produccion set n_tropas=n_tropas+$n_tropa[$i] where id_produccion=".$ref['id_produccion'];
						}
						else
						{
							$sql="insert into cola_produccion values(null,'tropa".($i+1)."',$n_tropa[$i],0,$this->id_ciudad,$this->t_actual)";
						}
						$res=$this->mysqli->query($sql);
					}
		
				}

			}
			$no_disponible=0;//Lo ponemos a cero de nuevo para la proxima comprobacion
		}
		header("Location:militar.php");
		exit;
	}

	public function mostar_reclutamiento() //Muestra las tropas que se estan reclutando
	{
		$sql="select * from cola_produccion where id_ciudad=$this->id_ciudad";
		$resp=$this->mysqli->query($sql);
		if ($resp->num_rows>0)
		{
			while($reg=$resp->fetch_array())
			{
				$i=0;
				$sql="select * from datos_tropas where tropa = '".$reg['tropa']."'";
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();
				?>
				<script type="text/javascript">
				reclutamiento[<?php echo $i;?>]=1;
				$('#mostrar_reclutamiento').append("<?php echo "Reclutando ".($reg['n_tropas']-$reg['n_tropas_reclutadas'])." ".Datos::tropa($reg['tropa'])."(s) acabara en <span id='reclutamiento_".$i."'>".-($this->t_actual-$red['tiempo']*$reg['n_tropas']-$reg['fecha'])."</span>s<br />";?>");
				</script>
				<?php
				$i++;
			}
			?>
			<script type="text/javascript">
			function actualiza_reclutar()
			{
				for (var i=0;i<10;i++)
				{
					if (reclutamiento[i]==1)
					{
						$("#reclutamiento_"+i).html($("#reclutamiento_"+i).html()-1);
						if (parseInt($("#reclutamiento_"+i).html())<=0)
						{
							location.reload();
						}
					}
				}
			}
			setInterval('actualiza_reclutar()',1000);
			</script>
			<?php
		}

	}

	public function mostrar_tropa($tropa) //Mostramos el numero de tropas que tiene una ciudad de una determinada clase
	{
		$sql="select * from tropas where id_ciudad=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		echo "(".$reg[$tropa].")";
	}

	public function mostrar_tropas() //Mostramos todas las tropas de una ciudad en el menu
	{
		$refuerzo=$this->tropas_ciudad($this->id_ciudad);

		?>

		<p><img src='img/elementos/tropas/legionario.png' class='icono_tropa'>Legionarios: <?php echo $refuerzo[0]; ?></p>
		<p><img src='img/elementos/tropas/pretoriano.png' class='icono_tropa'>Pretorianos: <?php echo $refuerzo[1]; ?></p>
		<p><img src='img/elementos/tropas/triario.png' class='icono_tropa'>Triarios: <?php echo $refuerzo[2]; ?></p>
		<p><img src='img/elementos/tropas/caballeria_l.png' class='icono_tropa'>Caballería ligera: <?php echo $refuerzo[3]; ?></p>
		<p><img src='img/elementos/tropas/caballeria_p.png' class='icono_tropa'>Caballería pesada: <?php echo $refuerzo[4]; ?></p>
		<p><img src='img/elementos/tropas/general.png' class='icono_tropa'>Generales: <?php echo $refuerzo[5]; ?></p>
		<p><img src='img/elementos/tropas/ariete.png' class='icono_tropa'>Arietes: <?php echo $refuerzo[6]; ?></p>
		<p><img src='img/elementos/tropas/onagro.png' class='icono_tropa'>Onagros: <?php echo $refuerzo[7]; ?></p>
		<p><img src='img/elementos/tropas/colono.png' class='icono_tropa'>Colonos: <?php echo $refuerzo[8]; ?></p>

		<?php
	}

	public function ordenar_movimiento_tropas($accion)
	{
		$sql="select * from tropas where id_ciudad=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		for ($i=1;$i<11;$i++) //Comprobamos que tenemos las tropas que queremos mandar
		{
			if ($reg["tropa$i"]<$_POST["tropa_$i"] || $_POST["tropa_$i"] < 0)
			{
				header("Location:index.php");
				exit;
			}
		}

		//Nos quitamos las tropas que mandamos y creamos el ataque
		$sql="update tropas set tropa1=tropa1-".$_POST['tropa_1']
		.", tropa2=tropa2-".$_POST['tropa_2']
		.", tropa3=tropa3-".$_POST['tropa_3']
		.", tropa4=tropa4-".$_POST['tropa_4']
		.", tropa5=tropa5-".$_POST['tropa_5']
		.", tropa6=tropa6-".$_POST['tropa_6']
		.", tropa7=tropa7-".$_POST['tropa_7']
		.", tropa8=tropa8-".$_POST['tropa_8']
		.", tropa9=tropa9-".$_POST['tropa_9']
		.", tropa10=tropa10-".$_POST['tropa_10']." where id_ciudad = $this->id_ciudad";
		$res=$this->mysqli->query($sql);

		$sql="select * from mapa where x = ".$_POST['x']." and y = ".$_POST['y'];
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$sql="insert into ataques values (null,'$accion',$this->id_ciudad,".$reg['id_casilla'].",".$_POST['tropa_1'].","
		.$_POST['tropa_2'].",".$_POST['tropa_3'].",".$_POST['tropa_4'].",".$_POST['tropa_5'].",".$_POST['tropa_6'].","
		.$_POST['tropa_7'].",".$_POST['tropa_8'].",".$_POST['tropa_9'].",".$_POST['tropa_10'].",$this->t_actual)";
		$res=$this->mysqli->query($sql);

		header("Location:index.php");
	}


	public function tropas_ciudad($id)
	{
		$sql="select * from tropas where id_ciudad=$id";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		//Las tropas que hay en la ciudad
		$refuerzo=array($reg['tropa1'],$reg['tropa2'],$reg['tropa3'],$reg['tropa4'],$reg['tropa5'],$reg['tropa6'],
		$reg['tropa7'],$reg['tropa8'],$reg['tropa9'],$reg['tropa10']);

		$sql="select * from tropas_refuerzos where id_ciudad_reforzada=$id";
		$res=$this->mysqli->query($sql);
		while($red=$res->fetch_array()) //Añadimos las tropas que estan como refuerzo
		{
			$refuerzo[0]+=$red['tropa1'];
			$refuerzo[1]+=$red['tropa2'];
			$refuerzo[2]+=$red['tropa3'];
			$refuerzo[3]+=$red['tropa4'];
			$refuerzo[4]+=$red['tropa5'];
			$refuerzo[5]+=$red['tropa6'];
			$refuerzo[6]+=$red['tropa7'];
			$refuerzo[7]+=$red['tropa8'];
			$refuerzo[8]+=$red['tropa9'];
			$refuerzo[9]+=$red['tropa10'];
		}

		return $refuerzo;
	}

	public function relacionTropasRefuerzos($id) //Relacion de las tropas de una ciudad con los refuerzos que tiene
	{
		$sql="select * from tropas where id_ciudad=$id";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		//Las tropas que hay en la ciudad
		$refuerzo=array($reg['tropa1'],$reg['tropa2'],$reg['tropa3'],$reg['tropa4'],$reg['tropa5'],$reg['tropa6'],
		$reg['tropa7'],$reg['tropa8'],$reg['tropa9'],$reg['tropa10']);

		$sql="select * from tropas_refuerzos where id_ciudad_reforzada=$id";
		$res=$this->mysqli->query($sql);
		while($red=$res->fetch_array()) //Añadimos las tropas que estan como refuerzo
		{
			$refuerzo[0]+=$red['tropa1'];
			$refuerzo[1]+=$red['tropa2'];
			$refuerzo[2]+=$red['tropa3'];
			$refuerzo[3]+=$red['tropa4'];
			$refuerzo[4]+=$red['tropa5'];
			$refuerzo[5]+=$red['tropa6'];
			$refuerzo[6]+=$red['tropa7'];
			$refuerzo[7]+=$red['tropa8'];
			$refuerzo[8]+=$red['tropa9'];
			$refuerzo[9]+=$red['tropa10'];
		}

		$sql="select * from tropas_refuerzos where id_ciudad_reforzada=$id";
		$res=$this->mysqli->query($sql);
		$relacionRefuerzos=array();
		$relacionTropas=array();

		while($red=$res->fetch_array())
		{
			for ($i=0;$i<10;$i++)
			{
				if ($red['tropa'.$i+1]==0 || $refuerzo[$i]==0) //Si no hay un refuerzo de esa unidad
				{
					$relacionTropas[$i]=0;
				}
				else
				{
					//El porcentaje del refuerzo del total de tropas
					$relacionTropas[$i]=$red["tropa".($i+1)]/$refuerzo[$i]*100;
				}
			}
			//La relacion de refuerzos con una ciudad en concreto
			$relacionRefuerzos[]=array($red['id_refuerzos'],$relacionTropas);
		}

		return $relacionRefuerzos;
	}

	public function datos_tropa($tropa) //Debuelve los datos de una tropa
	{
		$sql="select * from datos_tropas where tropa = '$tropa'";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$datos=array($reg['ataque'],$reg['defensa'],$reg['defensa_caballeria'],$reg['capacidad']);

		return $datos;
	}

	public function mostrar_reportes() //Muestra la lista de reportes
	{
		?>

		<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">

			<thead>
			<tr>
			<td><i class="icon-flag"></i></td>
			<td>Informe</td>
			<td>Fecha</td>
			<td><i class="icon-time"></i></td>
			</tr>
			</thead>

			<tbody>

		<?php
		$sql="select * from reportes_tropas where id_ciudad_atacada=$this->id_ciudad or id_ciudad_atacante=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		while($reg=$res->fetch_array())
		{
			?>
			<tr>

				<td>
					<?php
					if($reg['objetivo']=='atacar'){
						?><img src='img/elementos/tropas/legionario.png' class='icono_reporte' title='Ataque'><?php
					}else if($reg['objetivo']=='reforzar'){
						?><img src='img/elementos/tropas/pretoriano.png' class='icono_reporte' title='Defensa'><?php
					}
					?>
				</td>

				<td><a href="reportes.php?s=1&r=<?php echo $reg['id_reporte'];?>">
					<?php echo Datos::ciudad($reg['id_ciudad_atacante'])." ataca a ".Datos::ciudad($reg['id_ciudad_atacada'])."</a>"; ?></td>

				<td><?php echo date("m/d/Y",$reg['fecha']).""; ?></td>
				<td><?php echo date("h:i:s",$reg['fecha']).""; ?></td>
			
			</tr>


			<?php
		}
		?>
			</tbody>
		</table>
		<?php
	}

	public function mostrar_reporte() //Muestra un reporte
	{
		$sql="select * from reportes_tropas where id_reporte=".$_GET['r'];
		$res=$this->mysqli->query($sql);
		if($reg=$res->fetch_array())
		{
			$tropas=explode('-',$reg['tropas_atacante']); 		//Tropas que mando el atacante
			$tropasp=explode('-',$reg['tropasp_atacante']); 	//Tropas que perdio el atacante
			$tropasd=explode('-',$reg['tropas_atacadas']); 		//Tropas que defendieron
			$tropasdp=explode('-',$reg['tropasp_atacadas']);	 //Tropas que perdieron los defensores
			$recursos=explode('-',$reg['botin']); //Botin del ataque

			if ($reg['id_ciudad_atacante']==$this->id_ciudad && $reg['objetivo']=='atacar' || $reg['id_ciudad_atacada']==$this->id_ciudad && $reg['objetivo']=='atacar')
			{
				?>

				<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
					<tr><td colspan="100%">
						Batalla producida el <b><?php echo date("m/d/Y",$reg['fecha']);?></b> a las <b><?php echo date("h:i:s",$reg['fecha']);?></b>
					</td></tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
					<tr><td colspan="100%">Botín: 
						<?php echo "<img src='img/elementos/recursos/madera.png' class='recurso_reporte' title='Madera'>".$recursos[0].""; ?>
						<?php echo "<img src='img/elementos/recursos/ladrillo.png' class='recurso_reporte' title='Ladrillo'>".$recursos[1].""; ?>
						<?php echo "<img src='img/elementos/recursos/hierro.png' class='recurso_reporte' title='Hierro'>".$recursos[2].""; ?>
						<?php echo "<img src='img/elementos/recursos/cereal.png' class='recurso_reporte' title='Cereal'>".$recursos[3].""; ?>
					</td></tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
					<tr><td colspan="100%"><img src='img/elementos/tropas/legionario.png' class='icono_reporte' title='Defensa'>
						Agresor: <?php echo Datos::usuario(Datos::propietario($reg['id_ciudad_atacante']));?>
						 de  <?php echo Datos::aldea($reg['id_ciudad_atacante']);?></td></tr>
					<tr>
						<td>-</td>
						<?php
						for ($i=1;$i<11;$i++)
						{
							?>
							<td><?php echo Datos::tropa("tropa$i");?></td>
							<?php
						}
						?>
					</tr>
					<tr>
						<td><b>Tropas</b></td>
						<?php
						for ($i=0;$i<10;$i++)
						{
							?>
							<td><?php echo $tropas[$i];?></td>
							<?php
						}
						?>
					</tr>
					<tr>
						<td><b>Pérdidas</b></td>
						<?php
						for ($i=0;$i<10;$i++)
						{
							?>
							<td><?php echo $tropasp[$i];?></td>
							<?php
						}
						?>
					</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">
					<tr><td colspan="100%"><img src='img/elementos/tropas/pretoriano.png' class='icono_reporte' title='Defensa'>
						Defensor: <?php echo Datos::usuario(Datos::propietario($reg['id_ciudad_atacada']));?>
						 de  <?php echo Datos::aldea($reg['id_ciudad_atacada']);?></td></tr>
					<tr>
						<td>-</td>
						<?php
						for ($i=1;$i<11;$i++)
						{
							?>
							<td><?php echo Datos::tropa("tropa$i");?></td>
							<?php
						}
						?>
					</tr>
					<tr>
						<td><b>Tropas</b></td>
						<?php
						for ($i=0;$i<10;$i++)
						{
							?>
							<td><?php echo $tropasd[$i];?></td>
							<?php
						}
						?>
					</tr>
					<tr>
						<td><b>Pérdidas</b></td>
						<?php
						for ($i=0;$i<10;$i++)
						{
							?>
							<td><?php echo $tropasdp[$i];?></td>
							<?php
						}
						?>
					</tr>
				</table>

				<?php
			}
		}
	}
}
?>