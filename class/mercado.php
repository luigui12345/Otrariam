<?php
class Mercado
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
		$this->comerciantes_disponibles=$this->comerciantesDisponibles();
	}

	public function enviar() //Envia recursos
	{
		if (!isset($_POST['x_ciudad'],$_POST['y_ciudad'],$_POST['madera'],$_POST['barro'],$_POST['hierro'],$_POST['cereal']))
		{
			header("Location:index.php");
			exit;
		}
		//Variables de formulario
		$x_ciudad=safen($_POST['x_ciudad']);
		$y_ciudad=safen($_POST['y_ciudad']);
		$recursos = safen($_POST['madera'])."-".safen($_POST['barro'])."-".safen($_POST['hierro'])."-".safen($_POST['cereal']);

		$sql="select madera,barro,hierro,cereal from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		if ($reg['madera']<$_POST['madera'])
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=5");
			exit;
		}
		if ($reg['barro']<$_POST['barro'])
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=5");
			exit;
		}
		if ($reg['hierro']<$_POST['hierro'])
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=5");
			exit;
		}
		if ($reg['cereal']<$_POST['cereal'])
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=5");
			exit;
		}

		//Datos de la ciudad a la que se envia
		$sql="select id_casilla from mapa where x = $x_ciudad and y = $y_ciudad limit 1";
		$res=$this->mysqli->query($sql);

		if ($res->num_rows>0) //Si existe la ciudad
		{
			$reg=$res->fetch_array();

			//Nos quitamos los recursos a enviar
			$sql="update mapa set madera = madera-".$_POST['madera'].",barro=barro-".$_POST['barro'].",hierro=hierro-".$_POST['hierro'].",cereal=cereal-".$_POST['cereal']." where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);

			//Hacemos el envio
			$sql="insert into intercambios values (null,$this->id_ciudad,'todo','$recursos','".$reg['id_casilla']."','enviar',0,$this->t_actual)";
			$res=$this->mysqli->query($sql);
			
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=3");
		}
		else
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=4");
		}

	}

	public function ofertar() //Crea una oferta en el mercado
	{
		//Variables de formulario
		$r_ofrezco=safe($_POST['recurso_ofrezco']);
		$ofrezco=safen($_POST['ofrezco']);
		$r_busco=safe($_POST['recurso_busco']);
		$busco=safen($_POST['busco']);

		//Datos de mi aldea
		$sql="select madera,barro,hierro,cereal from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		
		if ($reg[$r_ofrezco] < $ofrezco) //Si no tengo los recursos que oferto
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=2");
		}

		else //Si los tengo
		{
			//Me quito los recursos que ofrezco
			$sql="update mapa set $r_ofrezco = $r_ofrezco-$ofrezco where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);

			//Creo la oferta
			$sql="insert into ofertas values (null,'$r_ofrezco',$ofrezco,'$r_busco',$busco,$this->id_ciudad)";
			$res=$this->mysqli->query($sql);
			header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=1");
		}
	}

	function eliminar_oferta()
	{
		$sql="select * from ofertas where id_oferta=".$_GET['o']." limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		if ($reg['recurso_ofrece']=='madera')
		{
			$recurso='madera';
		}
		else if ($reg['recurso_ofrece']=='barro')
		{
			$recurso='barro';
		}
		else if ($reg['recurso_ofrece']=='hierro')
		{
			$recurso='hierro';
		}
		else if ($reg['recurso_ofrece']=='cereal')
		{
			$recurso='cereal';
		}
		$sql="update mapa set $recurso=$recurso+".$reg['cantidad_ofrece']." where id_casilla = ".$reg['id_ciudad'];
		$res=$this->mysqli->query($sql);
		$sql="delete from ofertas where id_oferta=".$_GET['o'];
		$res=$this->mysqli->query($sql);
		header("location:edificio.php?s=".Datos::slotPorEdificio('mercado'));
	}

	public function mostrar_ofertas($mias=null) //Muestra las ofertas del mercado
	{
		//Datos de mi ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$ret=$res->fetch_array();

		//Busco las ofertas que no son de mi ciudad
		if (isset($mias))
		{
			$sql="select * from ofertas where id_ciudad=$this->id_ciudad";
		}
		else
		{
			$sql="select * from ofertas where id_ciudad!=$this->id_ciudad";
		}
		$res=$this->mysqli->query($sql);
		?>
		<p>
		<?php
		while ($reg=$res->fetch_array()) //Mostramos todas la ofertas
		{
			$n_ciudad=Datos::ciudad($reg['id_ciudad']); //Muestra el nombre de la ciudad
			?>
			Ciudad: <?php echo $n_ciudad." | ";?>

			Ofrece:

			<?php
				if($reg['recurso_ofrece']=='madera'){
				echo "<img src='img/elementos/recursos/madera.png' class='recurso_reporte' title='Madera'>";
				}
				if($reg['recurso_ofrece']=='barro'){
				echo "<img src='img/elementos/recursos/ladrillo.png' class='recurso_reporte' title='Ladrillo'>";
				}
				if($reg['recurso_ofrece']=='hierro'){
				echo "<img src='img/elementos/recursos/hierro.png' class='recurso_reporte' title='Hierro'>";
				}
				if($reg['recurso_ofrece']=='cereal'){
				echo "<img src='img/elementos/recursos/cereal.png' class='recurso_reporte' title='Cereal'>";
				}
			?>
			<?php echo "<b>".$reg['cantidad_ofrece']."</b>";?> 

			|

			Busca:

			<?php
				if($reg['recurso_busca']=='madera'){
				echo "<img src='img/elementos/recursos/madera.png' class='recurso_reporte' title='Madera'>";
				}
				if($reg['recurso_busca']=='barro'){
				echo "<img src='img/elementos/recursos/ladrillo.png' class='recurso_reporte' title='Ladrillo'>";
				}
				if($reg['recurso_busca']=='hierro'){
				echo "<img src='img/elementos/recursos/hierro.png' class='recurso_reporte' title='Hierro'>";
				}
				if($reg['recurso_busca']=='cereal'){
				echo "<img src='img/elementos/recursos/cereal.png' class='recurso_reporte' title='Cereal'>";
				}
			echo "<b>".$reg['cantidad_busca']."</b>"; 


			if ($reg['id_ciudad']==$this->id_ciudad)
			{
				?>
				<a href="eliminar_oferta.php?o=<?php echo $reg['id_oferta'];?>" class="remove"><i class="icon-remove"></i></a>
				<?php
			}
			else
			{
				if ($reg['cantidad_busca'] > $ret[$reg['recurso_busca']] || $reg['cantidad_busca']/500 > $this->comerciantes_disponibles) //Si no puedes pagar la oferta
				{
					echo " <i>No tienes sufientes recursos</i>";
				}
				else //Si puedes pagarla
				{
					?>
					<a href='comerciar.php?id_oferta=<?php echo $reg['id_oferta'];?>'class="boton">Aceptar</a>
					<?php
				}
			}

		}

		?>
		</p>
		<?php

	}

	public function aceptar_oferta() //Aceptar la oferta
	{
		//Datos de mi ciudad
		$sql="select madera,barro,hierro,cereal from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$ret=$res->fetch_array();

		//Datos de la oferta aceptada
		$sql="select * from ofertas where id_oferta = ".$_GET['id_oferta']." limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		if ($reg['cantidad_busca'] > $ret[$reg['recurso_busca']] || $reg['cantidad_busca']/500 > $this->comerciantes_disponibles) //Si no puedes pagar la oferta
		{
			header("Location:index.php");
			exit;
		}

		//Nos quitamos los recursos que el busca para enviarlos
		$sql="update mapa set ".$reg['recurso_busca']." = ".$reg['recurso_busca']."".-$reg['cantidad_busca']." where id_casilla = $this->id_ciudad";
		$res=$this->mysqli->query($sql);

		//Creamos el intercambio
		$sql="insert into intercambios values 
		(null,".$reg['id_ciudad'].",'".$reg['recurso_ofrece']."',".$reg['cantidad_ofrece'].",$this->id_ciudad,'".$reg['recurso_busca']."',".$reg['cantidad_busca'].",$this->t_actual)";
		$res=$this->mysqli->query($sql);

		//Eliminamos la oferta
		$sql="DELETE FROM ofertas WHERE id_oferta = ".$_GET['id_oferta'];
		$res=$this->mysqli->query($sql);

		header("location:edificio.php?s=".Datos::slotPorEdificio('mercado')."&m=3");
	}

	public function procesar_comercio()
	{
		//Buscamos los intercambios en los que participa mi ciudad
		$sql="select * from intercambios where (id_ciudad_ofrece = $this->id_ciudad or id_ciudad_busca = $this->id_ciudad)";
		$resp=$this->mysqli->query($sql);

		//Mostramos todos lo intercambios
		$i=0;
		if ($resp->num_rows > 0) //Si hay algun proceso comercial
		{
			while($reg=$resp->fetch_array()) //Los mostramos todos
			{	
				//Cogemos los datos de mi ciudad y la que me compra
				$sql="select x,y from mapa where id_casilla =".$reg['id_ciudad_ofrece']."";
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();
				$sql="select x,y from mapa where id_casilla =".$reg['id_ciudad_busca']."";
				$res=$this->mysqli->query($sql);
				$rel=$res->fetch_array();

				//Cogemos la distancia que hay entre nosotros
				$distancia_x=$red['x']-$rel['x'];
				$distancia_y=$red['y']-$rel['y'];

				//La hacemos positiva
				if ($distancia_x<0)
				{
					$distancia_x = -($distancia_x);
				}
				if ($distancia_y<0)
				{
					$distancia_y= -($distancia_y);
				}
				$distancia_ciudades = ($distancia_x)+($distancia_y);//Las casillas que nos separan
				$tiempo_transporte = $distancia_ciudades*300; 		//Lo que van a tardar los comerciantes en llegar

					$t_acepto_oferta = $reg['fecha'];	//Hora a la que se acepto la oferta
					$t_restantes_comercio = $tiempo_transporte+$reg['fecha']-$this->t_actual; //Tiempo que queda para que llegue el cargamento

					if ($t_restantes_comercio <= 0) //Si ya ha llegado
					{

						if ($reg['recurso_busca']=='vuelta' && $this->t_actual>=$reg['fecha']+$tiempo_transporte) //Si ha vuelto el comerciante
						{
							$sql="delete from intercambios where id_intercambio=".$reg['id_intercambio'];
							$res=$this->mysqli->query($sql);	
						}
						else //Si ha llegado el comerciante
						{	
							//Para ver si es un envio
							$sql="select * from intercambios where (id_ciudad_ofrece = $this->id_ciudad or id_ciudad_busca = $this->id_ciudad) and recurso_busca='enviar'";
							$res=$this->mysqli->query($sql);
							if ($res->num_rows>0) //Si es un envio
							{
								$rem=$res->fetch_array();
								$recursos = explode('-',$rem['cantidad_ofrece']); //Cogemos las cuatro clases de recursos

								//Cargo a quien le llega los recursos
								$sql="update mapa set madera = madera+".$recursos[0].", barro = barro+".$recursos[1].",hierro = hierro+".$recursos[2].", cereal = cereal+".$recursos[3]." where id_casilla=".$rem['id_ciudad_busca'];
								$res=$this->mysqli->query($sql);

								$t_llega=$reg['fecha']+$tiempo_transporte; //Hora a la que debia llegar el comerciante
								//Hacemos que vuelva el comerciante
								$sql="update intercambios set recurso_busca='vuelta',fecha=".$t_llega." where id_ciudad_ofrece = ".$rem['id_ciudad_ofrece']."";
								$res=$this->mysqli->query($sql);
							}
							else
							{
								//Me cargo a mi y a quien le llega los recursos
								$sql="update mapa set ".$reg['recurso_busca']." = ".$reg['recurso_busca']."+".$reg['cantidad_busca']." where id_casilla = ".$reg['id_ciudad_ofrece'];
								$res=$this->mysqli->query($sql);
								$sql="update mapa set ".$reg['recurso_ofrece']." = ".$reg['recurso_ofrece']."+".$reg['cantidad_ofrece']." where id_casilla =".$reg['id_ciudad_busca'];
								$res=$this->mysqli->query($sql);

								$t_llega=$reg['fecha']+$tiempo_transporte; //Hora a la que debia llegar el comerciante
								//Hacemos que vuelva el comerciante
								$sql="update intercambios set recurso_busca='vuelta', recurso_ofrece='vuelta',fecha=".$t_llega." where id_intercambio=".$reg['id_intercambio'];
								$res=$this->mysqli->query($sql);
							}
			
						}

					}
					else //Si aun no ha llegado
					{
						if ($reg['id_ciudad_ofrece']==$this->id_ciudad) //Si yo soy quien compra
						{
							?>
							<script type="text/javascript" language="javascript">
							
							tiempo2= <?php echo $t_restantes_comercio;?>; //Tiempo que queda para que llegue el comerciante
							is = <?php echo $i;?>; //Numero de intercambios
							
							document.getElementById("intercambios").style.display="block"; //Mostramos los intercambios

							tiempoA[tiempoA.length]=<?php echo $t_restantes_comercio;?>; //Tiempo que le queda al viaje

							ns = tiempoA.length-1; //Numero de viajes
							
							var capa1 = document.getElementById("envios"); 		//Cogemos el div de envios
							var intercambio1 = document.createElement("div");	//Le añadimos un div para el intercambios
							intercambio1.id = "timer_comercio_busco_"+ns;		//Le damos un ID
							capa1.appendChild(intercambio1);					//Mostramos el div
							
							timer_comercio_busco(is);
							
							function timer_comercio_busco(num) //Timer de comercio
							{
								ns = tiempoA.length-1;
								while (ns >= 0) //Mostramos todos los intercambios
								{
									if (tiempoA[ns]==0)//Si ya ha llegado un intercambio
									{
										location.reload();
									}
									<?php
									if ($reg['recurso_busca']=='vuelta') //Si esta volviendo el mercader
									{?>
										document.getElementById("timer_comercio_busco_"+ns).innerHTML="<i>"+fecha(tiempoA[ns])+"</i>";
									<?php
									}
									else //Si esta yendo el mercado
									{
									?>
										document.getElementById("timer_comercio_busco_"+ns).innerHTML=fecha(tiempoA[ns]);
									<?php
									}
									?>
									tiempoA[ns]--; 	//Restamos un segundo
									ns -= 1;		//Pasamos al siguiente intercambio
								}
							}
							
							setInterval('timer_comercio_busco('+is+')', 1000); //Hacemos que cada segundo se actualice el timer
							</script>
							<?php
						}
						else if ($reg['id_ciudad_ofrece']!=$this->id_ciudad && ($reg['recurso_ofrece']!='todo' ||$reg['recurso_ofrece']=='todo' && $reg['recurso_busca']!='vuelta'))
						{
							?>
							<script type="text/javascript" language="javascript">

							tiempo2= <?php echo $t_restantes_comercio;?>; //Tiempo que queda para que llegue el comerciante
							i = <?php echo $i;?>;	//Numero de intercambios
							document.getElementById("intercambios").style.display="block"; //Mostramos los intercambios
							tiempoB[tiempoB.length]=<?php echo $t_restantes_comercio;?>;	//Tiempo que le queda al viaje

							var n = tiempoB.length-1;	//Numero de viajes
							
							var capa2 = document.getElementById("recibos");		//Cogemos el div de envios
							var intercambio2 = document.createElement("div");	//Le añadimos un div para el intercambios
							intercambio2.id = "timer_comercio_ofrezco_"+n;		//Le damos un ID
							capa2.appendChild(intercambio2);					//Mostramos el div
							
							timer_comercio_ofrezco(i);

							function timer_comercio_ofrezco(num) //Timer de intercambios
							{
								n = tiempoB.length-1;

								while (n >= 0) //Mostramos todos los intercambios
								{
									if (tiempoB[n]==0) //Si ha llegado el intercambio
									{
										location.reload();
									}
									<?php
									if ($reg['recurso_busca']=='vuelta')//Si vuelve el mercado
									{?>
										document.getElementById("timer_comercio_ofrezco_"+n).innerHTML="<i>"+fecha(tiempoB[n])+"</i>";
									<?php
									}
									else //Si va el mercader
									{
									?>
										document.getElementById("timer_comercio_ofrezco_"+n).innerHTML=fecha(tiempoB[n]);
									<?php
									}
									?>
									tiempoB[n]--; //Quitamos un segundo
									n -= 1;	//Pasamos al siguiente intercambio
								}
							}
							
							setInterval('timer_comercio_ofrezco('+i+')', 1000);//Hacemos que cada segundo se actualice el timer
							</script>
						<?php
						}
					}
				$i++;
			}
		}
	}

	public function comerciantesDisponibles() //Numero de comerciantes disponibles
	{
		$comerciantes=Datos::nivelEdificio('mercado',$this->id_ciudad); //Numero de comerciantes por nivel del edificio
		$comerciantes_por_recurso=0;

		//Buscamos nuestras ofertas
		$sql="select cantidad_ofrece from ofertas where id_ciudad = $this->id_ciudad";
		$res=$this->mysqli->query($sql);

		while ($reg=$res->fetch_array()) //Recorremos nuestras ofertas
		{
			//Numero de comerciantes que se estan usando en ofertas
			$comerciantes_por_recurso+=$reg['cantidad_ofrece']/500;
			$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
		}
		//Buscamos los intercambios en marcha
					
		$sql="select * from intercambios where (id_ciudad_ofrece = $this->id_ciudad or (id_ciudad_busca = $this->id_ciudad and (recurso_ofrece!='todo' or (recurso_ofrece='todo' and recurso_busca!='vuelta'))))";
		$resp=$this->mysqli->query($sql);

		while ($reg=$resp->fetch_array())
		{
			if ($reg['id_ciudad_ofrece']==$this->id_ciudad) //Si somos quienes llevamos los recursos
			{
				if ($reg['recurso_ofrece']=='todo')
				{
					$recursos=explode('-',$reg['cantidad_ofrece']);
					$comerciantes_por_recurso+=($comerciantes_por_recurso+$recursos[0]+$recursos[1]+$recursos[2]+$recursos[3])/500;
					$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
				}
				else
				{
					$comerciantes_por_recurso+=($comerciantes_por_recurso+$reg['cantidad_ofrece'])/500;
					$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
				}
			}

			else if ($reg['id_ciudad_busca']==$this->id_ciudad) //Si somos quien lo recibimos
			{
				$comerciantes_por_recurso+=($comerciantes_por_recurso+$reg['cantidad_busca'])/500;
				$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
				
			}
	
		}

		$comerciantes_disponibles = $comerciantes - $comerciantes_por_recurso; //Comerciantes que no estan haciendo nada
		return $comerciantes_disponibles;
	}
}
?>