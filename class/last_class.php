<?php
require_once('./FirePHPCore/FirePHP.class.php');
ob_start();
include ('init.php');
include ('seguridad.php');
include ('datos_auxiliares.php');
include ('mercado.php');


class Aldea
{
	private $edificios=array();
	private $edificio=array();
	private $x;				//La coordenada X del mapa
	private $y;				//La coordenada Y del mapa
	private $ps_edificio;	//Lo que produce un edificio por segundo
	private $p_edificio;	//Lo que produce un edificio en una actualizacion
	private $t_transcurrido;//Tiempo pasado desde que se mando construir el edificio hasta ahora
	private $t_actual;		//Hora actual
	private $id_ciudad;		//Id de la capital
	private $id_usuario;	//Id del usuario que ha iniciado sesion
	private $usuario;		//Usuario que ha iniciado sesion
	private $last_update;	//Ultima vez que actualizo
	private $capacidad;		//Capacidad del almacen
	private $mysqli;		//Para conectar a la base de datos
	private $construira_almacen=0; //Hora a la que se construira el almacen
	private $tiempo_almacen;	//Tiempo que le queda al almacen para construirse
	private $firephp;
	private $mercado;

	public function __construct($requerir=null) //Comprobamos que ha iniciado sesion
	{
		$this->firephp = FirePHP::getInstance(true);
		$this->firephp->log($_SESSION['ju_nom'],'Sesion');

		if (!isset($_SESSION["ju_nom"]))	//Sino esta logueado
		{
			header("Location:login.php");
		}

		if (isset($_SESSION["ju_nom"]))	//Si esta todo en orden
		{
			$this->mysqli=new mysqli(DB_HOST,SQL_USER,SQL_PASS,DB_NAME);
			$this->mysqli->set_charset(DB_CHARSET);
			$this->usuario=$_SESSION["ju_nom"];
			$this->id_usuario=Datos::id($this->usuario);
			$this->id_ciudad=$_SESSION['ju_ciudad'];
			$this->t_actual=strtotime(date('Y-m-d H:i:s'));
			$sql="select * from edificios_aldea where id_ciudad=$this->id_ciudad and edificio = 'Almacen'";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();
			$this->capacidad=$reg['produccion'];
			$this->ciudad=Datos::ciudad($this->id_ciudad);
			$sql="select * from mapa where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();
			$this->x=$reg['x'];
			$this->y=$reg['y'];
			$this->mercado=new Mercado();
			$this->comerciantes_disponibles=$this->mercado->comerciantesDisponibles();
		}
	}

	public function produccion_hora($edificio)	//Muestra la produccion por hora del edificio seleccionado
	{
		$edificio=utf8_encode($edificio);
		$sql="select * from edificios_aldea where id_ciudad = $this->id_ciudad and edificio = '$edificio' limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		if ($edificio == 'granja')
		{
			$nTropas=Datos::nTropas($this->id_ciudad);
			echo $reg['produccion']-$nTropas;
		}
		else
		{
			echo $reg['produccion'];
		}
	}
	
	public function datos_edificios($edificio) //Muestra el edificio en la aldea
	{
		$sql="select * from edificios_aldea where id_ciudad = $this->id_ciudad and edificio = '$edificio' limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

			if ($edificio == 'leñador') //Si es el leñador
			{
				?>
				
				<div class="wrap_recurso">

					<div class="nombre_recurso"><?php echo $edificio;?></div>
					<div class="img_recurso"><img src="img/elementos/edificios/bosque.png" title="<?php echo $edificio;?>"></div>
					
					<a href="edificio.php?edificio=<?php echo $edificio;?>"><div class="nivel_recurso">
					<div class="nivel_actual"><?php echo $reg["nivel"]; ?></div>
					<div class="img_aumentar" title="Aumentar de nivel"></div>
					</div></a>

				</div>
				<?php
			}
			
			else if ($edificio == 'barrera') //Si es la barrera
			{
				?>
				
				<div class="wrap_recurso">

					<div class="nombre_recurso"><?php echo $edificio;?></div>
					<div class="img_recurso"><img src="img/elementos/edificios/ladrillar.png" title="<?php echo $edificio;?>"></div>
					
					<a href="edificio.php?edificio=<?php echo $edificio;?>"><div class="nivel_recurso">
					<div class="nivel_actual"><?php echo $reg["nivel"]; ?></div>
					<div class="img_aumentar" title="Aumentar de nivel"></div>
					</div></a>

				</div>
				<?php
			}
			
			else
			{
				?>
				
				<div class="wrap_recurso">

					<div class="nombre_recurso"><?php echo $edificio;?></div>
					<div class="img_recurso"><img src="img/elementos/edificios/<?php echo $edificio;?>.png" title="<?php echo $edificio;?>"></div>
					
					<a href="edificio.php?edificio=<?php echo $edificio;?>"><div class="nivel_recurso">
					<div class="nivel_actual"><?php echo $reg["nivel"]; ?></div>
					<div class="img_aumentar" title="Aumentar de nivel"></div>
					</div></a>

				</div>
				<?php
			}
	}

	public function muestra_mercado() //Muestra el mercado
	{
		/*$comerciantes_por_recurso=0;

		//Buscamos nuestras ofertas
		$sql="select * from ofertas where id_ciudad = $this->id_ciudad";
		$res=$this->mysqli->query($sql);

		while ($reg=$res->fetch_array())
		{
			$comerciantes_por_recurso=$reg['cantidad_ofrece']/500;
			$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
		}
		//Buscamos los intercambios en marcha
		$sql="select * from intercambios where id_ciudad_ofrece = $this->id_ciudad or (id_ciudad_busca = $this->id_ciudad)";
		$resp=$this->mysqli->query($sql);
		
		while ($reg=$resp->fetch_array())
		{
			if ($reg['id_ciudad_ofrece']==$this->id_ciudad)
			{
				if ($reg['recurso_ofrece']=='todo')
				{
					$recursos=explode('-',$reg['cantidad_ofrece']);
					$comerciantes_por_recurso=($comerciantes_por_recurso+$recursos[0]+$recursos[1]+$recursos[2]+$recursos[3])/500;
					$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
				}
				else
				{
					$comerciantes_por_recurso=($comerciantes_por_recurso+$reg['cantidad_ofrece'])/500;
					$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
				}
			}

			else if ($reg['id_ciudad_busca']==$this->id_ciudad)
			{
				$comerciantes_por_recurso=($comerciantes_por_recurso+$reg['cantidad_busca'])/500;
				$comerciantes_por_recurso=ceil($comerciantes_por_recurso);
				
			}
	
		}
		$comerciantes_disponibles = $comerciantes - $comerciantes_por_recurso; //Comerciantes que no estan haciendo nada
		$this->comerciantes_disponibles=$comerciantes_disponibles;*/
		?>


		<?php
		echo "<br />";
		echo "Comerciantes disponibles: $this->comerciantes_disponibles";
		echo "<br />";
		echo "Recursos que puede transportar cada comerciante: 500";
		echo "<br />";

		if ($this->comerciantes_disponibles > 0) //Si hay comerciantes disponibles
		{
			?>

			<div style="float:left;">

			<div id="mercado2">
			<form name="form_ofrecer" method="post" action="procesa_comercio.php">
			<p>Crear oferta:</p>
			<br />
			Ofrezco: <input type="text" name="ofrezco"> de 
			<select name="recurso_ofrezco">
				<option value="madera">Madera</option>
				<option value="barro">Barro</option>
				<option value="hierro">Hierro</option>
				<option value="cereal">Cereal</option>
			</select>
			<br>
			Busco: <input type="text" name="busco"> de 
			<select name="recurso_busco">
				<option value="madera">Madera</option>
				<option value="barro">Barro</option>
				<option value="hierro">Hierro</option>
				<option value="cereal">Cereal</option>
			</select>
			<br /><br>
			
			<input type="submit" value="ofertar" name="accion">
			</form>

			</div><!--Mercado2-->


			<div id="mercado3">

			<form name="form_enviar" action="procesa_comercio.php" method="post">
				<p>Enviar recursos:</p>
				<br>
				Madera <input type="text" name="madera" style="width:50px" required/> Barro <input type="text" name="barro" style="width:50px" required/>
				 Hierro <input type="text" name="hierro" style="width:50px" required/> Cereal <input type="text" name="cereal" style="width:50px" required/>
				
				<br /><br>
				<?php
				if (isset($_GET['x']) and isset($_GET['y']) and is_numeric($_GET['x']) and is_numeric($_GET['y'])) //Si hemos seleccionado una ciudad para enviarle recursos
				{
					?>
					<script type="text/javascript" language="javascript">
					$("#mercado2").css("display", "none");
					$("#mercado3").css("display", "block");
					$("#mercado4").css("display", "none");
					$("#mercado1").css("display", "none");
					</script>
					Coordenadas de la ciudad: X <input type="text" name="x_ciudad" value="<?php echo $_GET['x'];?>" style="width:50px" required />
					 Y <input type="text" name="y_ciudad" value="<?php echo $_GET['y'];?>" style="width:50px" required/>
					<?php
				}
				else
				{
					?>
					Coordenadas de la ciudad: X <input type="text" name="x_ciudad" style="width:50px" required /> Y <input type="text" name="y_ciudad" style="width:50px" required/>
					<?php
				}
				?>
				
				<br /><br>
				<input type="hidden" name="accion" value="Enviar" />
				<input type="submit" value="enviar" name="accion">
			</form>

			</div>

			</div><!--Mercado3-->


			<div id="mercado4">
			<?php
			$this->mercado->mostrar_ofertas(); //Mostramos las ofertas
			?>
			</div><!--Mercado4-->

			<?php
		}
		
	}

	public function muestra_edificio($edificio) //Para mostrar los datos del edificio que se esta viendo
	{
		$edificio=safe_edificio($edificio);
		$sql="select * from edificios_aldea where id_ciudad = $this->id_ciudad and edificio = '$edificio' limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array(); 

		if ($edificio == "cuartel")
		{
			?>
			<div class="nombre_edificio">Cuartel</div>

			<div class="edificio_descripcion">
			El Cuartel sirve para producir tropas.
			</div>

			<div id="cuartel1">
				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/cuartel.png" title="Cuartel"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>
			<?php
			if ($reg['nivel']>0) //Si se ha construido el Cuartel
			{
				$this->muestra_cuartel();
			}
			else
			{
				?>
				</div>
				<?php
			}
		}

		else if ($edificio == "mercado") //Si el edificio es el mercado
		{
			if ($reg['nivel']>0) //Si se ha construido el mercado
			{
				?>
				<div class="nombre_edificio">Mercado</div>

				<div class="edificio_descripcion">
				El Mercado sirve para intercambiar recursos con otras aldeas.
				</div>

				<div id="mercado1">

				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/mercado.png" title="Mercado"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>

				</div><!--Mercado1-->


				<?php
				$this->muestra_mercado();
			}
			else //Sino se ha construido el mercado
			{
				?>
				<div class="nombre_edificio">Mercado</div>

				<div class="edificio_descripcion">
				El Mercado sirve para intercambiar recursos con otras aldeas.
				</div>

				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/mercado.png" title="Mercado"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>
				<?php
			}
		}

		else if ($edificio == 'leñador') //Si el edificio es el leñador
		{
			?>
			<div class="nombre_edificio">Leñador</div>

			<div class="edificio_descripcion">
			El leñador produce Madera, útil para la construcción de edificios.
			</div>

			<div class="wrap_recurso">

			<div class="img_recurso"><img src="img/elementos/edificios/bosque.png" title="Leñador"></div>
			
			<div class="nivel_recurso">
			<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
			</div>

			</div>

			<div class="edificio_costes">
			<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
			<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

			</div>
			<?php
		}
		else //Si es otro
		{
			switch ($edificio) //Cogemos el edificio
			{
				
				case "barrera":    
				?>

				<div class="nombre_edificio">Ladrillar</div>

				<div class="edificio_descripcion">
				El ladrillar te produce Ladrillos, que suponen la unidad básica de construcción de edificios.
				</div>

				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/ladrillar.png" title="Leñador"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"]; ?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>

				<?php
				break;


				case "mina":    
				?>

				<div class="nombre_edificio">Mina</div>

				<div class="edificio_descripcion">
				La mina te permite extraer Hierro, que puede ser empleada en la construcción del ejército.
				</div>

				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/mina.png" title="Leñador"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>

				<?php
				break;

				case "granja":    
				?>

				<div class="nombre_edificio">Granja</div>

				<div class="edificio_descripcion">
				La Granja produce Cereal con el que abastecer a tus poblaciones y ejércitos y seguir creciendo.
				</div>

				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/granja.png" title="Leñador"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>

				<?php
				break;

				case "almacen":    
				?>

				<div class="nombre_edificio">Almacén</div>

				<div class="edificio_descripcion">
				El Almacén aumenta la capacidad de tu aldea para guardar los recursos.
				</div>

				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/almacen.png" title="Almacén"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>

				<?php

				break;

				case "ayuntamiento":    
				?>

				<div class="nombre_edificio">Ayuntamiento</div>

				<div class="edificio_descripcion">
				Es el centro de la ciudad. Al tener el ayuntamiento a este nivel los edificios tardarán un  <b><?php echo $reg['produccion'];?>%</b> menos en construirse
				</div>


				<div class="wrap_recurso">

				<div class="img_recurso"><img src="img/elementos/edificios/ayuntamiento.png" title="Ayuntamiento"></div>
				
				<div class="nivel_recurso">
				<div class="nivel_actual"><?php echo $reg["nivel"];?></div>
				</div>

				</div>

				<div class="edificio_costes">
				<p>Subir a nivel <?php echo $reg["nivel"]+1; ?></p>
				<div class="subir_nivel"><?php $this->coste_ampliacion($reg["edificio"],$reg["nivel"]); ?></div>

				</div>

				<?php

				break;

				default:
				header("Location:index.php"); //Si no existe el edificio volvemos al index

				break;

			}

		}

	}
//********************************************************************************************************************
//***********************FUNCIONES AMPLIAR****************************************************************************
//********************************************************************************************************************
	public function coste_ampliacion($edificio,$nivel) //Muestra los costes de ampliacion del edificio que se está viendo
	{
		$edificio=safe_edificio($edificio);

		$sql="select * from costes_construcciones where edificio = '$edificio' and nivel = $nivel+1 limit 1";
		$res=$this->mysqli->query($sql);

		if($reg=$res->fetch_array()) //Muestra los recursos necesarios
		{

			//Pasar el recurso Tiempo a un formato horario
			$segundos = $reg["tiempo"];
			$horas = intval($segundos/3600);
			$restoSegundos = $segundos%3600;
			$recurso_tiempo = $horas.':'.date("i:s",mktime (0,0,$restoSegundos,0,0,0));

			//Mostramos los recursos
			echo "<img src='img/elementos/recursos/madera.png' class='recurso_coste' title='Madera'> ".$reg["madera"]."
			 | <img src='img/elementos/recursos/ladrillo.png' class='recurso_coste' title='Ladrillo'> ".$reg["barro"]."
			 | <img src='img/elementos/recursos/hierro.png' class='recurso_coste' title='Hierro'> ".$reg["hierro"]."
			 | <img src='img/elementos/recursos/cereal.png' class='recurso_coste' title='Cereal'> ".$reg["cereal"]."
			 | <img src='img/elementos/recursos/tiempo.png' class='recurso_coste' title='Tiempo'> ".$recurso_tiempo;
		}

		//Comprueba si tiene los recursos suficientes
		$sql="select * from mapa where id_usuario = $this->id_usuario and madera >=".$reg["madera"]." and barro >=".$reg["barro"]."
		and hierro>=".$reg["hierro"]." and cereal>=".$reg["cereal"]." limit 1";
		$res=$this->mysqli->query($sql);
		echo "<br />";
		if ($res->num_rows > 0) //Si tiene recursos sufientes
		{
			$sql="select * from eventos where id_ciudad = $this->id_ciudad";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows > 0) //Comprueba que no hay construcciones en curso
			{
				echo "</br>Ya se está construyendo.";
			}
			else
			{
				echo "</br><a href='construir.php?edificio=$edificio'>Construir</a>";
			}
			
		}
		else
		{
			echo "</br>No tienes suficientes recursos.";
		}

	}

	public function ordenar_ampliar($edificio) //Da la orden de ampliar y cobra los recursos
	{
		$edificio=safe_edificio($edificio);

		$sql="select * from eventos where id_ciudad = $this->id_ciudad";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows>0) //Comprueba que no hay construcciones en curso
		{
			header("Location:index.php");
			exit;
		}

		//Cogemos los datos del ayuntamiento
		$sql="select * from edificios_aldea where edificio='Ayuntamiento' and id_ciudad=$this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$red=$res->fetch_array();

		//Cogemos los datos del edificio a ampliar
		$sql="select * from edificios_aldea where edificio = '$edificio' and id_ciudad = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		//Buscamos sus costes_construcciones de ampliacon
		$sql="select * from costes_construcciones where edificio = '".$reg['edificio']."' and nivel = ".$reg['nivel']."+1 limit 1";
		$res=$this->mysqli->query($sql);
		$ret=$res->fetch_array();

		//Cobra los recursos del edificio
		$sql="update mapa set madera = madera-".$ret["madera"].", barro = barro-".$ret["barro"].",hierro = hierro-".$ret["hierro"].",cereal = cereal-".$ret["cereal"]." where id_usuario = $this->id_usuario";
		$res=$this->mysqli->query($sql);

		$bono_ayuntamiento=$red['produccion']*$ret['tiempo']/100; //Descuento de tiempo por el nivel del ayuntamiento

		$sql="insert into eventos values (null,'$edificio',$this->t_actual-$bono_ayuntamiento,$this->id_ciudad)"; //Pone la construccion
		$res=$this->mysqli->query($sql);

		header("Location:index.php");
	}

	public function ampliar($edificio) //Amplia un edificio
	{
		$edificio=safe_edificio($edificio);

		//Cogemos los datos del edificio a amplair
		$sql="select * from edificios_aldea where edificio = '$edificio' and id_ciudad = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		//Miramos los costes_construcciones de su ampliacon
		$sql="select * from costes_construcciones where edificio = '".$reg['edificio']."' and nivel = ".$reg['nivel']."+1 limit 1";
		$res=$this->mysqli->query($sql);
		$ret=$res->fetch_array();

		//Amplia el edificio
		$sql="update edificios_aldea set nivel = nivel+1, produccion = ".$ret['produccion'].", habitantes = ".$ret['habitantes']." where id_ciudad = $this->id_ciudad and edificio = '$edificio'";
		$res=$this->mysqli->query($sql);
		$crecimiento_habitantes = $ret["habitantes"]-$reg["habitantes"];

		//Aumenta los habitantes
		$sql="update mapa set habitantes = habitantes+$crecimiento_habitantes where id_casilla = $this->id_ciudad ";
		$res=$this->mysqli->query($sql);

		//Se quita la construccion
		$sql="DELETE FROM eventos WHERE id_ciudad = $this->id_ciudad";
		$res=$this->mysqli->query($sql);

	}
//********************************************************************************************************************
//*************************FUNCIONES MOTOR**************************************************************************
//********************************************************************************************************************
	public function almacen() //Da valor a las variables del almacen
	{
		//Cogemos los datos de nuestro almacen
		$sql="select * from edificios_aldea where id_ciudad = $this->id_ciudad and edificio = 'almacen' limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		//Miramos si se esta construyendo el almacen
		$sql="select * from eventos where id_ciudad = $this->id_ciudad and edificio = 'almacen' limit 1";
		$res=$this->mysqli->query($sql);

		if ($res->num_rows>0) //Si se esta construyendo el almacen
		{
			$ret=$res->fetch_array();

			//Cogemos los datos del proximo almacen
			$sql="select * from costes_construcciones where edificio = 'almacen' and nivel = ".$reg["nivel"]."+1 limit 1";
			$res=$this->mysqli->query($sql);
			$rel=$res->fetch_array();

			$ta_construira = $rel["tiempo"]; //Tiempo que dura la construccion
			$ta_orden=$ret["tiempo"]; //Hora a la que se ordeno construir
			$ta_diferencia = $this->t_actual-$ta_orden;	//El tiempo que ha pasado desde que se ordeno construir
			$ta_cuando = $ta_construira+$ta_orden; //Hora la que se construira

			if ($ta_diferencia-$ta_construira>0) //Si ha pasado la cola de espera del edificio
			{
				$this->construira_almacen=$ta_cuando; //Hora a la que se construira el almacen
				$this->tiempo_almacen=$this->construira_almacen-$this->last_update; //Tiempo que queda para que se amplie el almacen
			}
		}
	}

	public function calcular_reclutamiento()
	{
		$sql="select * from cola_produccion where id_ciudad=$this->id_ciudad";
		//echo $sql;
		$resp=$this->mysqli->query($sql);
		if ($resp->num_rows>0)
		{
			while($reg=$resp->fetch_array())
			{
				$tropas_restantes=$reg['n_tropas']-$reg['n_tropas_reclutadas'];
				$sql="select * from datos_tropas where tropa = '".$reg['tropa']."'";
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();
				$tp_tropa=$red['tiempo'];
				$tt_tropa=$tp_tropa*$reg['n_tropas'];
				$t_terminara=$tt_tropa+$reg['fecha'];
				$t_transcurrido=$this->t_actual-($tp_tropa*$reg['n_tropas_reclutadas']+$reg['fecha']);
				$tropas_reclutan=floor($t_transcurrido/$tp_tropa);
				if ($tropas_reclutan>$reg['n_tropas'])
				{
					$tropas_reclutan=$reg['n_tropas'];
				}
				$sql="update cola_produccion set n_tropas_reclutadas=n_tropas_reclutadas+$tropas_reclutan where tropa = '".$reg['tropa']."' and id_ciudad=$this->id_ciudad";
				$res=$this->mysqli->query($sql);
				$sql="update tropas set ".$reg['tropa']." = ".$reg['tropa']."+$tropas_reclutan where id_ciudad = $this->id_ciudad";
				$res=$this->mysqli->query($sql);

				$sql="delete from cola_produccion where n_tropas<=n_tropas_reclutadas";
				$res=$this->mysqli->query($sql);
			}
			
		}
	}

	public function calcular_recursos($edificio,$mostrar) //Calcula los recursos producidos
	{
		//*****************************************************************************************
		//Cogemos los datos del edificio seleccionado
		$sql="select * FROM edificios_aldea WHERE id_ciudad = $this->id_ciudad AND edificio='$edificio' limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		
		//Miramos si este se esta construyendo
		$sql="select * from eventos where id_ciudad = $this->id_ciudad and edificio = '$edificio'";
		$res=$this->mysqli->query($sql);

		if ($res->num_rows>0) //Si hay una construccion
		{
			$ret=$res->fetch_array();

			//Miramos los costes_construcciones del edificio
			$sql="select * from costes_construcciones where edificio = '".$reg["edificio"]."' and nivel = ".$reg["nivel"]."+1 limit 1";
			$res=$this->mysqli->query($sql);
			$rel=$res->fetch_array();

			$t_construira = $rel["tiempo"]; //Tiempo que dura la construccion
			$t_orden=$ret["tiempo"]; //Hora a la que se ordeno construir
			$t_diferencia = $this->t_actual-$t_orden;	//El tiempo que ha pasado desde que se ordeno construir
			$t_cuando = $t_construira+$t_orden; //Hora la que se construira

			//Miramos los costes_construcciones del edificio a construir
			$sql="select * from costes_construcciones where edificio = '".$reg["edificio"]."' and nivel = ".$reg["nivel"]."+1 limit 1";
			$res=$this->mysqli->query($sql);
			$rug=$res->fetch_array();
		
			if ($t_diferencia-$t_construira>0) //Si ha pasado la cola de espera del edificio
			{
				$t1=$t_cuando-$this->last_update; 			//Tiempo que ha pasado desde la hora a la que se actualizara y la ultima vez que actualizaste
				if ($edificio == 'Granja')
				{
					$nTropas=Datos::nTropas($this->id_ciudad);
					$p_edificio1=($reg['produccion']-$nTropas)/3600*$t1; 	//Lo que ha producido el edificio antes de la construccion
				}
				else
				{
					$p_edificio1=$reg['produccion']/3600*$t1; 	//Lo que ha producido el edificio antes de la construccion
				}
				$this->ampliar($edificio); 					//Ampliamos el edificio

				//Comprobamos los datos del edificio ampliado
				$sql="select * FROM edificios_aldea WHERE id_ciudad = $this->id_ciudad AND edificio='$edificio' limit 1";
				$res=$this->mysqli->query($sql);
				$reg=$res->fetch_array();

				$t2= $this->t_actual-$t_cuando; 			//Tiempo que ha pasado desde ahora hasta que se construyo
				if ($edificio == 'Granja')
				{
					$nTropas=Datos::nTropas($this->id_ciudad);
					$p_edificio2=($reg['produccion']-$nTropas)/3600*$t2; 	//Lo que ha producido el edificio antes de la construccion
				}
				else
				{
					$p_edificio2=$reg['produccion']/3600*$t2; 	//Lo que ha producido el edificio antes de la construccion
				}

				$this->p_edificio = $p_edificio1+$p_edificio2; 	//Lo que se ha producido en total
				if ($edificio == 'Granja')
				{
					$nTropas=Datos::nTropas($this->id_ciudad);
					$this->ps_edificio=($reg['produccion']-$nTropas)/3600; 	//Lo que producimos por segundo
				}
				else
				{
					$this->ps_edificio=$reg['produccion']/3600; 	//Lo que producimos por segundo
				}
			}

			else //Si aun no se ha construido
			{
				if ($edificio == 'Granja')
				{
					$nTropas=Datos::nTropas($this->id_ciudad);
					$this->p_edificio=($reg['produccion']-$nTropas)/3600*$this->t_transcurrido; //Lo producido
					$this->ps_edificio=($reg['produccion']-$nTropas)/3600; //Lo que producimos por segundo
				}
				else
				{
					$this->p_edificio=$reg['produccion']/3600*$this->t_transcurrido; //Lo producido
					$this->ps_edificio=$reg['produccion']/3600; //Lo que producimos por segundo
				}
				$tiempos=-($t_diferencia-$rug["tiempo"]);	//Tiempo restante para la ampliacion
				$tiempos = (string)$tiempos;				//Lo hacemos cadena para poder trabajarlo

				if ($mostrar == "si")//Si queremos que se muestre el timer
				{
				?>
					<div id="tiempo_ampliacion">
					<script type="text/javascript" language="javascript">
					var tiempos = <?php echo $tiempos?>;


					function tiempo() //Para mostrar el tiempo
					{
						if (tiempos==0) //Si ha pasado el tiempo actualizamos
						{
							location.reload();
						}
						else 
						{
							//Restamos un segundo y lo mostramos
							tiempos--;
							document.getElementById("tiempo").innerHTML="<img src='img/elementos/recursos/tiempo.png' class='tiempo_coste' title='Tiempo restante'>Tiempo restante para la ampliacion "+fecha(tiempos);
						}
					}
					tiempo();
					setInterval('tiempo()',1000); //Cada segundo se actualizara el timer
					</script>
					</div>
					<?php
				}
			}
		
		}
		else //Sino no se esta construyendo el edificio
		{
			$this->almacen(); //Declaramos las variables de almacen
			if ($this->construira_almacen!=0) //Si se esta construyendo el almaen
			{
				if ($edificio == 'Granja')
				{	
					$nTropas=Datos::nTropas($this->id_ciudad);
					$p_1=($reg['produccion']-$nTropas)/3600*$this->tiempo_almacen; //Lo que se produce antes de que se contruya el almacen
				}
				else
				{
					$p_1=$reg['produccion']/3600*$this->tiempo_almacen; //Lo que se produce antes de que se contruya el almacen
				}
				

				$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
				$res=$this->mysqli->query($sql);
				$rez=$res->fetch_array();

				if ($edificio == 'Leñador' || $edificio == 'Mina' || $edificio == 'Barrera' || $edificio == 'Granja') //Si es un edificio de produccion el seleccionado
				{
					if ($p_1+$rez[$reg['recurso']]>$this->capacidad) //Si al sumar la produccion se supera la capacidad
					{
						$p_1=$this->capacidad-$rez[$reg['recurso']];	//Producimos lo que hace falta para llenar el almacen
					}
					
					if ($edificio == 'Granja')
					{
						$nTropas=Datos::nTropas($this->id_ciudad);
						$t2=$this->t_actual-$this->construira_almacen; //Tiempo que ha pasado desde que se amplio el almacen hasta ahora
						$p_2=($reg['produccion']-$nTropas)/3600*$t2;	//Produccion desde que se amplio el almacen

						$this->p_edificio=$p_1+$p_2; //Lo producido
						$this->ps_edificio=($reg['produccion']-$nTropas)/3600; //Lo que producimos por segundo
					}
					else
					{
						$t2=$this->t_actual-$this->construira_almacen; //Tiempo que ha pasado desde que se amplio el almacen hasta ahora
						$p_2=$reg['produccion']/3600*$t2;	//Produccion desde que se amplio el almacen

						$this->p_edificio=$p_1+$p_2; //Lo producido
						$this->ps_edificio=$reg['produccion']/3600; //Lo que producimos por segundo
					}
				}
			}
			else //Sino se esta construyendo el almacen
			{
				$sql = "select * FROM edificios_aldea WHERE id_ciudad = $this->id_ciudad AND edificio='$edificio' limit 1";
				$res=$this->mysqli->query($sql);
				$reg=$res->fetch_array();
				
				if ($edificio == 'Granja')
				{
					$nTropas=Datos::nTropas($this->id_ciudad);
					$this->p_edificio=($reg['produccion']-$nTropas)/3600*$this->t_transcurrido; //Lo producido
					$this->ps_edificio=($reg['produccion']-$nTropas)/3600; //Lo que producimos por segundo
				}
				else
				{
					$this->p_edificio=$reg['produccion']/3600*$this->t_transcurrido; //Lo producido
					$this->ps_edificio=$reg['produccion']/3600; //Lo que producimos por segundo
				}
			}		
		}
		
	}



	public function comprobar_recursos($mostrar,$procesar_tropas=null) //Es el motor del juego
	{
		//Datos de nuestra ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$last_update = $reg["last_update"]; //La ultima vez que actualizamos
		$this->last_update=$last_update;
		$this->t_transcurrido = $this->t_actual-$last_update; //Tiempo que ha pasado desde la ultima vez que actualizamos

		//Calculamos los recursos de cada edificio
		//*****************************************************************************************
		$this->calcular_recursos("Granja",$mostrar);
		$p_granja=$this->p_edificio;
		$ps_granja=$this->ps_edificio;

		//*****************************************************************************************
		$this->calcular_recursos("leñador",$mostrar);
		$p_leñador=$this->p_edificio;
		$ps_leñador=$this->ps_edificio;

		//*****************************************************************************************
		$this->calcular_recursos("Barrera",$mostrar);
		$p_barrera=$this->p_edificio;
		$ps_barrera=$this->ps_edificio;

		//*****************************************************************************************
		$this->calcular_recursos("Mina",$mostrar);
		$p_mina=$this->p_edificio;
		$ps_mina=$this->ps_edificio;

		//*****************************************************************************************
		$this->calcular_recursos("Ayuntamiento",$mostrar);
		//*****************************************************************************************
		$this->calcular_recursos("Almacen",$mostrar);
		//*****************************************************************************************
		$this->calcular_recursos("Mercado",$mostrar);
		//*****************************************************************************************
		$this->calcular_recursos("Cuartel",$mostrar);
		//*****************************************************************************************
		$this->calcular_reclutamiento();
		if (!isset($procesar_tropas))
		{
			$this->procesar_movimiento_tropas();
		}
		else
		{
			$this->procesar_movimiento_tropas($this->id_ciudad,'si');
		}

		//Comprobamos de nuevo los datos de la ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$re1=$res->fetch_array();

		//Actualizamos los recursos
		$sql="update mapa set last_update = $this->t_actual, cereal = cereal+$p_granja, madera = madera+$p_leñador, barro = barro+$p_barrera, hierro = hierro+$p_mina where id_casilla = '$this->id_ciudad'";
		$res=$this->mysqli->query($sql);

		//Comprobamos de nuevo los datos de la ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$this->last_update=$reg['last_update']; //Nuevo last_update
		$this->tiempo_almacen=0;				//Quitamos las variables del almacen pues ya se ha comprobado
		$this->construira_almacen=0;

		//Datos del almacen
		$sql="select * from edificios_aldea where id_ciudad=$this->id_ciudad and edificio = 'Almacen' limit 1";
		$res=$this->mysqli->query($sql);
		$rem=$res->fetch_array();

		$this->capacidad=$rem['produccion'];//Capacidad del almacen

		//Hacemos que no supere la cantidad al limite de la capacidad
		if ($reg['madera']>$this->capacidad)
		{
			$sql="update mapa set madera = $this->capacidad where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);
		}
		if ($reg['barro']>$this->capacidad)
		{
			$sql="update mapa set barro = $this->capacidad where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);
		}
		if ($reg['hierro']>$this->capacidad)
		{
			$sql="update mapa set hierro = $this->capacidad where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);
		}
		if ($reg['cereal']>$this->capacidad)
		{
			$sql="update mapa set cereal = $this->capacidad where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);
		}

		//Comprobamos de nuevo los datos de la ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		//Mostramos los recursos
		?>
		<script type="text/javascript" language="javascript">
		//Pasamos las variables PHP a Js
		var madera = <?php echo $reg['madera'];?>; 	//Cantidad de madera
		var barro = <?php echo $reg['barro'];?>;	//Cantidad de barro
		var hierro = <?php echo $reg['hierro'];?>;	//Cantidad de hierro
		var cereal = <?php echo $reg['cereal'];?>;	//Cantidad de cereal
		var ps_leñador = <?php echo $ps_leñador?>;	//Produccion por segundo de madera
		var ps_barrera = <?php echo $ps_barrera?>;	//Produccion por segundo de barro
		var ps_mina = <?php echo $ps_mina?>;		//Produccion por segundo de hierro
		var ps_granja = <?php echo $ps_granja?>;	//Produccion por segundo de cereal
		var capacidad = <?php echo $this->capacidad?>;;	//Capacidad del almacen

		actualiza_recursos();

		function actualiza_recursos() //Esto actualizara los recursos de acuerdo a la produccion
		{
			//Lo muestra redondeado a la baja
			document.getElementById("r1").innerHTML=Math.floor(madera);
			document.getElementById("r2").innerHTML=Math.floor(barro);
			document.getElementById("r3").innerHTML=Math.floor(hierro);
			document.getElementById("r4").innerHTML=Math.floor(cereal);

			//Aumenta los recursos de acuerdo a la produccion
			madera += ps_leñador;
			barro += ps_barrera;
			hierro += ps_mina;
			cereal += ps_granja;

			//Si se supera la capacidad del almacen
			if (madera>capacidad)
			{
				madera = capacidad;
			}
			if (barro>capacidad)
			{
				barro = capacidad;
			}
			if (hierro>capacidad)
			{
				hierro = capacidad;
			}
			if (cereal>capacidad)
			{
				cereal = capacidad;
			}
		}
		setInterval('actualiza_recursos()', 1000); //Se actualizaran los recursos una vez por segundo
		</script>
		<?php
		//Comprobamos los intercambios comerciales
		$this->mercado->procesar_comercio();
	}

//********************************************************************************************************************
//**********************************FUNCIONES MAPA******************************************************************
//********************************************************************************************************************
	public function ir_mapa() //Crea un link para ir al mapa en tu posicion
	{
		$sql="select * from mapa where id_casilla=$this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		$x=$reg["x"]; 	//Coordenada X de mi ciudad
		$y=$reg["y"];	//Coordenada Y de mi ciudad
		
		//Links al mapa
		echo "<a href='mapa.php?x=$x&y=$y'> 
		<img src='img/elementos/menu/mapa.png'>
		</a>";
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
				$sql="select * from mapa where x = $this->x + $j and y = $this->y + $i"; //Comprueba si el terreno esta ocupado
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

				<div id="boton_mapa">

					<div id="arriba">
						<div id="mapa_up" onclick='mover("arriba")'></div>
					</div>

					<div id="centro">
						<div id="izquierda"><div id="mapa_left" onclick='mover("izquierda")'></div></div>
						<div id="derecha"><div id="mapa_right" onclick='mover("derecha")'></div></div>
					</div>

					<div id="abajo">
						<div id="mapa_bottom" onclick='mover("abajo")'></div>
					</div>

				</div>

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
			$sql="select * from mapa where x = $x and y = $y limit 1";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();

			//Detalles de la aldea
			echo "Eje X ".$reg['x']."<br />";
			echo "Eje Y ".$reg['y']."<br />";
			echo "Habitantes ".$reg['habitantes']."<br />";

			//Datos del propietario de la aldea
			$sql="select * from usuarios where id_usuario =".$reg['id_usuario'];
			$res=$this->mysqli->query($sql);
			$red=$res->fetch_array();


			echo "Propietario ".$red['nombre']."<br />";

			echo "<a href='edificio.php?edificio=mercado&x=".$reg['x']."&y=".$reg['y']."'>Enviar Recursos</a><br />";
			echo "<a href='redactar_mensaje.php?usuario=".$red['nombre']."''>Enviar Mensaje</a><br />";
			echo "<a href='mover_tropas.php?x=".$reg['x']."&y=".$reg['y']."'>Enviar Tropas</a>";
		}

	}

//********************************************************************************************************************
//****************************************FUNCIONES TROPAS********************************************************
//********************************************************************************************************************

	public function muestra_cuartel()
	{
		echo "<br />";
		echo "<br />";
		echo "<br />";
		$j=0;
		$tropa_no_disponible=0;
		$sql="select * from edificios_aldea where id_ciudad = $this->id_ciudad and edificio = 'cuartel' limit 1";
		$res=$this->mysqli->query($sql);
		$red=$res->fetch_array();

		$sql="select * from datos_tropas where parte_ejercito='infanteria'";
		$res=$this->mysqli->query($sql);
		while($reg=$res->fetch_array())
		{
			$j++;
			$requisitos=explode('|',$reg['requisitos']);
			for($i=0;$i<count($requisitos);$i++)
			{
				$requisitos2=explode('_',$requisitos[$i]);
				$sql="select * from edificios_aldea where edificio = '$requisitos2[0]' and nivel >= $requisitos2[1] and id_ciudad = $this->id_ciudad";
				
				$resp=$this->mysqli->query($sql);
				
				if ($resp->num_rows == 0)
				{
					$tropa_no_disponible=1;
				}
			}

			if ($tropa_no_disponible==0)
			{
				?>
				<form name="form_tropas" action="reclutar.php" method="post">
				<?php
				echo $reg['nombre']." cuesta madera:".$reg['madera']." barro:".$reg['barro']." hierro:".$reg['hierro']." cereal:".$reg['cereal']." tardara:".$reg['tiempo'];
				?>
				<input type="text" name="n_tropa<?php echo $j;?>" /> <br />
				
				<?php
			}
			$tropa_no_disponible=0;
		}
		?>
		<input type="submit">
				</form>
		<?php
		echo "<br />";
		$this->mostar_reclutamiento();
		?>
		</div>
		<div id="cuartel2">
		<?php
		$this->mostrar_movimientos_tropas();
		?>
		</div>
		<div id="cuartel3">
			Aqui iran los datos de las tropas
		</div>
		<script type="text/javascript" language="javascript">
		$("#cuartel1").css("display", "block");
		$("#cuartel2").css("display", "none");
		$("#cuartel3").css("display", "none");
		</script>
		<?php

	}

	public function mostrar_movimientos_tropas()
	{
		echo "<b>Ataques que envio:</b><br />";
		$sql="select * from ataques where id_ciudad_atacante=$this->id_ciudad and (objetivo='atacar' or objetivo ='atracar') order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<br />
			<table>
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

			</table>
			<br />
			<?php
		}
		echo "<hr/>";
		echo "<b>Ataques recibo:</b><br />";
		$sql="select * from ataques where id_ciudad_atacada=$this->id_ciudad and (objetivo='atacar' or objetivo ='atracar') order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> ataca a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<br />
			<table>
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

			</table>
			<br />
			<?php
		}
		echo "<hr/>";
		echo "<b>Refuerzos envio:</b><br />";
		$sql="select * from ataques where id_ciudad_atacante=$this->id_ciudad and objetivo='reforzar' order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> refuerza a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<br />
			<table>
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

			</table>
			<br />
			<?php
		}
		echo "<hr/>";
		echo "<b>Refuerzos recibo:</b><br />";
		$sql="select * from ataques where id_ciudad_atacada=$this->id_ciudad and objetivo='reforzar' order by fecha asc";
		$res=$this->mysqli->query($sql);
		while ($reg=$res->fetch_array())
		{
			echo "<i>".Datos::ciudad($reg['id_ciudad_atacante'])."</i> refuerza a <i>".Datos::ciudad($reg['id_ciudad_atacada'])."</i>";
			?>
			<br />
			<table>
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

			</table>
			<br />
			<?php
		}
	}

	public function ordenar_reclutar()
	{
		$no_disponible=0;
		$n_tropa=array();
		$n_tropa=array(0,0,0,0,0,0,0,0,0,0);
		if (isset($_POST['n_tropa1']))
		{
			$n_tropa[0]=$_POST['n_tropa1'];
		}

		if (isset($_POST['n_tropa2']))
		{
			$n_tropa[1]=$_POST['n_tropa2'];
		}

		if (isset($_POST['n_tropa3']))
		{
			$n_tropa[2]=$_POST['n_tropa3'];
		}

		if (isset($_POST['n_tropa4']))
		{
			$n_tropa[3]=$_POST['n_tropa4'];
		}

		if (isset($_POST['n_tropa5']))
		{
			$n_tropa[4]=$_POST['n_tropa5'];
		}

		if (isset($_POST['n_tropa6']))
		{
			$n_tropa[5]=$_POST['n_tropa6'];
		}

		if (isset($_POST['n_tropa7']))
		{
			$n_tropa[6]=$_POST['n_tropa7'];
		}

		if (isset($_POST['n_tropa8']))
		{
			$n_tropa[7]=$_POST['n_tropa8'];
		}

		if (isset($_POST['n_tropa9']))
		{
			$n_tropa[8]=$_POST['n_tropa9'];
		}

		if (isset($_POST['n_tropa10']))
		{
			$n_tropa[9]=$_POST['n_tropa10'];
		}

		for ($i=0;$i<count($n_tropa);$i++)
		{
			if (is_numeric($n_tropa[$i]) and $n_tropa[$i] > 0)
			{

				$sql="select * from datos_tropas where tropa = 'tropa".($i+1)."'";
				$res=$this->mysqli->query($sql);
				$reg=$res->fetch_array();

				$requisitos=explode('|',$reg['requisitos']);
				for($j=0;$j<count($requisitos);$j++)
				{
					$requisitos2=explode('_',$requisitos[$j]);
					$sql="select * from edificios_aldea where edificio = '$requisitos2[0]' and nivel >= $requisitos2[1] and id_ciudad = $this->id_ciudad";
					$res=$this->mysqli->query($sql);
					if ($res->num_rows==0)
					{
						$no_disponible=1;
					}
				}

				if ($no_disponible==0)
				{
					$costes=array($reg['madera']*$n_tropa[$i],$reg['barro']*$n_tropa[$i],$reg['hierro']*$n_tropa[$i],$reg['cereal']*$n_tropa[$i]);
					$sql="select * from mapa where id_casilla = $this->id_ciudad";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();

					if ($costes[0]>$red['madera'] || $costes[1]>$red['barro'] || $costes[2]>$red['hierro'] || $costes[3]>$red['cereal'])
					{
						header("Location:edificio.php?edificio=cuartel&m=1");
					}
					else
					{
						$sql="update mapa set madera = madera-$costes[0], barro=barro-$costes[1],hierro=hierro-$costes[2],cereal=cereal-$costes[3] where id_casilla = $this->id_ciudad";
						$res=$this->mysqli->query($sql);
						$sql="insert into cola_produccion values(null,'tropa".($i+1)."',$n_tropa[$i],0,$this->id_ciudad,$this->t_actual)";
						$res=$this->mysqli->query($sql);
						header("Location:edificio.php?edificio=cuartel&m=2");
					}
		
				}
				
			}
			$no_disponible=0;
		}
	}

	public function mostar_reclutamiento()
	{
		$sql="select * from cola_produccion where id_ciudad=$this->id_ciudad";
		$resp=$this->mysqli->query($sql);
		if ($resp->num_rows>0)
		{
			while($reg=$resp->fetch_array())
			{
				$sql="select * from datos_tropas where tropa = '".$reg['tropa']."'";
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();
				echo $reg['n_tropas']-$reg['n_tropas_reclutadas']." ".$reg['tropa']." acabara en ".-($this->t_actual-$red['tiempo']*$reg['n_tropas']-$reg['fecha'])."s";
				echo "<br />";
			}
		}

	}

	public function mostrar_tropa($tropa)
	{
		$sql="select * from tropas where id_ciudad=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		echo "(".$reg[$tropa].")";
	}

	public function mostrar_tropas()
	{
		$refuerzo=$this->tropas_ciudad($this->id_ciudad);

		echo "Legionarios: ".$refuerzo[0];
		echo "<br />";
		echo "Pretorianos: ".$refuerzo[1];
		echo "<br />";
		echo "Triarios: ".$refuerzo[2];
		echo "<br />";
		echo "Caballeria ligera: ".$refuerzo[3];
		echo "<br />";
		echo "Caballeria pesada: ".$refuerzo[4];
		echo "<br />";
		echo "Generales: ".$refuerzo[5];
		echo "<br />";
		echo "Arites: ".$refuerzo[6];
		echo "<br />";
		echo "Onagros: ".$refuerzo[7];
		echo "<br />";
		echo "Senadores: ".$refuerzo[8];
		echo "<br />";
		echo "Colonos: ".$refuerzo[9];
	}

	public function ordenar_movimiento_tropas($accion)
	{
		//Seguridad
		$sql="select * from tropas where id_ciudad=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		for ($i=1;$i<11;$i++)
		{
			if ($reg["tropa$i"]<$_POST["tropa_$i"])
			{
				header("Location:index.php");
			}
		}
		//////////////
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

	public function procesar_movimiento_tropas($id=null,$procesar=null)
	{
		if (!isset($id))
		{
			$id=$this->id_ciudad;
		}
		$movimientos=array();
		$velocidad=0;
		$sql="select * from ataques where id_ciudad_atacante = $id or id_ciudad_atacada = $id order by fecha asc";
		$resp=$this->mysqli->query($sql);
		while ($reg=$resp->fetch_array())
		{
			for ($y=1;$y<=10;$y++)
			{
				if ($reg['tropa'.$y]>0)
				{
					$sql="select * from datos_tropas where tropa = 'tropa$y'";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();
					
					if ($red['velocidad']<$velocidad || $velocidad == 0)
					{
						$velocidad=$red['velocidad'];
					}
				}
				else
				{

				}
			}
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacada'];
			$res=$this->mysqli->query($sql);
			$rel=$res->fetch_array();
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacante'];
			$res=$this->mysqli->query($sql);
			$red=$res->fetch_array();
			$distancia_x = $rel['x']-$red['x'];
			$distancia_y = $rel['y']-$red['y'];
			if ($distancia_x<0)
			{
				$distancia_x = -($distancia_x);
			}
			if ($distancia_y<0)
			{
				$distancia_y= -($distancia_y);
			}
			$casillas_distancia=$distancia_x+$distancia_y;
			$tiempo_tardara=$casillas_distancia/$velocidad*3600;
			$tiempo_restante=($reg['fecha']+$tiempo_tardara)-$this->t_actual;
			$movimientos[]=array($tiempo_restante,$reg['id_ataque'],'atacar');
		}

		$sql="select * from vuelta_ataques where id_ciudad_atacante = $id order by fecha asc";
		$resp=$this->mysqli->query($sql);
		while ($reg=$resp->fetch_array())
		{
			for ($i=1;$i<=10;$i++)
			{
				if ($reg['tropa'.$i]>0)
				{
					$sql="select * from datos_tropas where tropa = 'tropa$i'";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();
					
					if ($red['velocidad']<$velocidad || $velocidad == 0)
					{
						$velocidad=$red['velocidad'];
					}
				}
				else
				{

				}
			}
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacada'];
			$res=$this->mysqli->query($sql);
			$rel=$res->fetch_array();
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacante'];
			$res=$this->mysqli->query($sql);
			$red=$res->fetch_array();
			$distancia_x = $rel['x']-$red['x'];
			$distancia_y = $rel['y']-$red['y'];
			if ($distancia_x<0)
			{
				$distancia_x = -($distancia_x);
			}
			if ($distancia_y<0)
			{
				$distancia_y= -($distancia_y);
			}
			$casillas_distancia=$distancia_x+$distancia_y;
			$tiempo_tardara=$casillas_distancia/$velocidad*3600;
			$tiempo_restante=($reg['fecha']+$tiempo_tardara)-$this->t_actual;

			$movimientos[]=array($tiempo_restante,$reg['id_vuelta'],'volver');
		}
		sort($movimientos);
		$ultimo_tiempo_ae=false;
		$ultimo_tiempo_ar=false;
		$ultimo_tiempo_re=false;
		$ultimo_tiempo_rr=false;
		
		for ($j=0;$j<count($movimientos);$j++)
		{
			if ($movimientos[$j][2]=='atacar')
			{
				$sql="select * from ataques where id_ataque=".$movimientos[$j][1];
				$resp=$this->mysqli->query($sql);

			}
			else if ($movimientos[$j][2]=='volver')
			{
				$sql="select * from vuelta_ataques where id_vuelta=".$movimientos[$j][1];
				$resp=$this->mysqli->query($sql);

			}

			//*************************************************
			$reg=$resp->fetch_array();
			$velocidad=0;
			for ($i=1;$i<=10;$i++)
			{
				if ($reg['tropa'.$i]>0)
				{
					$sql="select * from datos_tropas where tropa = 'tropa$i'";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();
					
					if ($red['velocidad']<$velocidad || $velocidad == 0)
					{
						$velocidad=$red['velocidad'];
					}
				}
				else
				{
				}
			}
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacada'];
			$res=$this->mysqli->query($sql);
			$rel=$res->fetch_array();
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacante'];
			$res=$this->mysqli->query($sql);
			$red=$res->fetch_array();
			$distancia_x = $rel['x']-$red['x'];
			$distancia_y = $rel['y']-$red['y'];
			if ($distancia_x<0)
			{
				$distancia_x = -($distancia_x);
			}
			if ($distancia_y<0)
			{
				$distancia_y= -($distancia_y);
			}
			$casillas_distancia=$distancia_x+$distancia_y;
			$tiempo_tardara=$casillas_distancia/$velocidad*3600;
			$tiempo_restante=($reg['fecha']+$tiempo_tardara)-$this->t_actual;
			
			if (!isset($procesar))
			{
				if ($tiempo_restante>0)
				{
					?>
					<script type="text/javascript" language="javascript">
					
					tiempoT[tiempoT.length]=<?php echo $tiempo_restante;?>; //Tiempo que le queda al viaje
					nt = tiempoT.length; //Numero de viajes

					</script>
					<?php
					if ($movimientos[$j][2]=='atacar' and $reg['id_ciudad_atacante'] == $id and ($reg['objetivo']=='atacar' or $reg['objetivo']=='atracar'))
					{
						if ($tiempo_restante < $ultimo_tiempo_ae || $ultimo_tiempo_ae==false)
						{
							$ultimo_tiempo_ae=$tiempo_restante;
						}
					}
					else if ($movimientos[$j][2]=='atacar' and $reg['id_ciudad_atacada'] == $id and ($reg['objetivo']=='atacar' or $reg['objetivo']=='atracar'))
					{
						if ($tiempo_restante < $ultimo_tiempo_ar || $ultimo_tiempo_ar==false)
						{
							$ultimo_tiempo_ar=$tiempo_restante;
						}
					}

					else if ($movimientos[$j][2]=='atacar' and $reg['id_ciudad_atacante'] == $id and ($reg['objetivo']=='reforzar'))
					{
						if ($tiempo_restante < $ultimo_tiempo_re || $ultimo_tiempo_re==false)
						{
							$ultimo_tiempo_re=$tiempo_restante;
						}
					}

					else if ($movimientos[$j][2]=='volver' and $reg['id_ciudad_atacante'] == $id)
					{
						if ($tiempo_restante < $ultimo_tiempo_re || $ultimo_tiempo_re==false)
						{
							$ultimo_tiempo_rr=$tiempo_restante;
						}
					}

					else if ($movimientos[$j][2]=='atacar' and $reg['id_ciudad_atacada'] == $id and ($reg['objetivo']=='reforzar'))
					{
						if ($tiempo_restante < $ultimo_tiempo_rr || $ultimo_tiempo_rr==false)
						{
							$ultimo_tiempo_rr=$tiempo_restante;
						}
					}
				}
				else
				{
					if ($movimientos[$j][2]=='volver')
					{
						$this->procesar_vuelta_tropas($reg['id_vuelta'],$reg['fecha']+$tiempo_tardara);
					}
					if ($movimientos[$j][2]=='atacar')
					{
						$this->accion_tropas($reg['objetivo'],$reg['id_ataque']);
					}
				}
			}
			else
			{
				if ($tiempo_restante<=0)
				{
					if ($movimientos[$j][2]=='volver')
					{
						$this->procesar_vuelta_tropas($reg['id_vuelta'],$reg['fecha']+$tiempo_tardara);
					}
					if ($movimientos[$j][2]=='atacar')
					{
						$this->accion_tropas($reg['objetivo'],$reg['id_ataque'],'si');
					}
				}
				else
				{

				}
			}
		}
		?>
		<script>
		var ultimo_tiempo_ae=parseInt(<?php echo $ultimo_tiempo_ae;?>);
		var ultimo_tiempo_ar=parseInt(<?php echo $ultimo_tiempo_ar;?>);
		var ultimo_tiempo_re=parseInt(<?php echo $ultimo_tiempo_re;?>);
		var ultimo_tiempo_rr=parseInt(<?php echo $ultimo_tiempo_rr;?>);

		var div_tropas=document.getElementById('info_aldea');
		if (!isNaN(ultimo_tiempo_ae))
		{
			var div_tropa = document.createElement("div");	//Le añadimos un div para el intercambios
			div_tropa.id = "tropa_ae";		//Le damos un ID
			div_tropas.appendChild(div_tropa);
		}
		if (!isNaN(ultimo_tiempo_ar))
		{
			var div_tropa = document.createElement("div");	//Le añadimos un div para el intercambios
			div_tropa.id = "tropa_ar";		//Le damos un ID
			div_tropas.appendChild(div_tropa);
		}
		if (!isNaN(ultimo_tiempo_re))
		{
			var div_tropa = document.createElement("div");	//Le añadimos un div para el intercambios
			div_tropa.id = "tropa_re";		//Le damos un ID
			div_tropas.appendChild(div_tropa);
		}
		if (!isNaN(ultimo_tiempo_rr))
		{
			var div_tropa = document.createElement("div");	//Le añadimos un div para el intercambios
			div_tropa.id = "tropa_rr";		//Le damos un ID
			div_tropas.appendChild(div_tropa);
		}

			function tropas()
			{
				if (ultimo_tiempo_ae<=0)
				{
					location.reload();
				}
				if (ultimo_tiempo_ar<=0)
				{
					location.reload();
				}
				if (ultimo_tiempo_re<=0)
				{
					location.reload();
				}
				if (ultimo_tiempo_rr<=0)
				{
					location.reload();
				}
				if (!isNaN(ultimo_tiempo_ae))
				document.getElementById('tropa_ae').innerHTML="Ataque enviado "+fecha(ultimo_tiempo_ae);
				if (!isNaN(ultimo_tiempo_ar))
				document.getElementById('tropa_ar').innerHTML="Ataque recibido "+fecha(ultimo_tiempo_ar);
				if (!isNaN(ultimo_tiempo_re))
				document.getElementById('tropa_re').innerHTML="Envio refuerzos "+fecha(ultimo_tiempo_re);
				if (!isNaN(ultimo_tiempo_rr))
				document.getElementById('tropa_rr').innerHTML="Refuerzo recibido "+fecha(ultimo_tiempo_rr);
				ultimo_tiempo_ae--;
				ultimo_tiempo_ar--;
				ultimo_tiempo_re--;
				ultimo_tiempo_rr--;
			}
			tropas();
			setInterval("tropas()",1000);

		</script>
		<?php
	}

	public function accion_tropas($objetivo,$id,$procesar=null)
	{
		if ($objetivo == 'reforzar')
		{
			$sql="select * from ataques where id_ataque = $id";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();

			/**********************************************************/
			$velocidad=0;
			for ($i=1;$i<=10;$i++)
				{
					if ($reg['tropa'.$i]>0)
					{
						$sql="select * from datos_tropas where tropa = 'tropa$i'";
						$res=$this->mysqli->query($sql);
						$red=$res->fetch_array();
						
						if ($red['velocidad']<$velocidad || $velocidad == 0)
						{
							$velocidad=$red['velocidad'];
						}
					}
					else
					{

					}
				}

				$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);
				$rel=$res->fetch_array();
				$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacante'];
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();
				$distancia_x = $rel['x']-$red['x'];
				$distancia_y = $rel['y']-$red['y'];
				if ($distancia_x<0)
				{
					$distancia_x = -($distancia_x);
				}
				if ($distancia_y<0)
				{
					$distancia_y= -($distancia_y);
				}
				$casillas_distancia=$distancia_x+$distancia_y;
				$tiempo_tardara=$casillas_distancia/$velocidad*3600;
				$tiempo_restante=($reg['fecha']+$tiempo_tardara)-$this->t_actual;

			$sql="insert into tropas_refuerzos values (null,".$reg['id_ciudad_atacante'].",".$reg['id_ciudad_atacada'].","
			.$reg['tropa1'].",".$reg['tropa2'].",".$reg['tropa3'].",".$reg['tropa4'].",".$reg['tropa5'].","
			.$reg['tropa6'].",".$reg['tropa7'].",".$reg['tropa8'].",".$reg['tropa9'].",".$reg['tropa10'].")";
			$res=$this->mysqli->query($sql);

			$tropas=$reg['tropa1']."-".$reg['tropa2']."-".$reg['tropa3']."-".$reg['tropa4']."-".
			$reg['tropa5']."-".$reg['tropa6']."-".$reg['tropa7']."-".$reg['tropa8']."-".
			$reg['tropa9']."-".$reg['tropa10'];

			$sql="insert into reportes_tropas values (null,'reforzar','0-0-0-0',".$reg['id_ciudad_atacante'].",".$reg['id_ciudad_atacada'].",'$tropas','0','0','0',".($reg['fecha']+$tiempo_tardara).")";
			$res=$this->mysqli->query($sql);
			$sql="delete from ataques where id_ataque = $id";
			$res=$this->mysqli->query($sql);

		}
		else if ($objetivo == 'atacar')
		{
			$velocidad=0;
			$sql="select * from ataques where id_ataque = $id";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();

			/**********************************************************/
			for ($i=1;$i<=10;$i++)
				{
					if ($reg['tropa'.$i]>0)
					{
						$sql="select * from datos_tropas where tropa = 'tropa$i'";
						$res=$this->mysqli->query($sql);
						$red=$res->fetch_array();
						
						if ($red['velocidad']<$velocidad || $velocidad == 0)
						{
							$velocidad=$red['velocidad'];
						}
					}
					else
					{

					}
				}

				$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);
				$rel=$res->fetch_array();
				$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacante'];
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();
				$distancia_x = $rel['x']-$red['x'];
				$distancia_y = $rel['y']-$red['y'];
				if ($distancia_x<0)
				{
					$distancia_x = -($distancia_x);
				}
				if ($distancia_y<0)
				{
					$distancia_y= -($distancia_y);
				}
				$casillas_distancia=$distancia_x+$distancia_y;
				$tiempo_tardara=$casillas_distancia/$velocidad*3600;
				$tiempo_restante=($reg['fecha']+$tiempo_tardara)-$this->t_actual;
				//******************************************************************
				if (!isset($procesar))
				{
					$temp=$this->id_ciudad;
					$temp1=$this->t_actual;

					$this->t_actual=$reg['fecha']+$tiempo_tardara-1;
					$this->id_ciudad=$reg['id_ciudad_atacada'];
					$this->comprobar_recursos('no','si');

					$this->id_ciudad=$temp;
					$this->t_actual=$temp1;
				}
				else
				{
					
				}
				/**********************************************************/

			//$tropas_mias=$this->tropas_ciudad($this->id_ciudad);
			$tropas_suyas=$this->tropas_ciudad($reg['id_ciudad_atacada']);

			/***************************************************************************/
			$d_tropa1=$this->datos_tropa('tropa1');
			$d_tropa2=$this->datos_tropa('tropa2');
			$d_tropa3=$this->datos_tropa('tropa3');
			$d_tropa4=$this->datos_tropa('tropa4');
			$d_tropa5=$this->datos_tropa('tropa5');
			$d_tropa6=$this->datos_tropa('tropa6');
			$d_tropa7=$this->datos_tropa('tropa7');
			$d_tropa8=$this->datos_tropa('tropa8');
			$d_tropa9=$this->datos_tropa('tropa9');
			$d_tropa10=$this->datos_tropa('tropa10');

			/***************************************************************************/
			$ataque_tropa1=$d_tropa1[0]*$reg['tropa1'];
			$ataque_tropa2=$d_tropa1[0]*$reg['tropa2'];
			$ataque_tropa3=$d_tropa1[0]*$reg['tropa3'];
			$ataque_tropa4=$d_tropa1[0]*$reg['tropa4'];
			$ataque_tropa5=$d_tropa1[0]*$reg['tropa5'];
			$ataque_tropa6=$d_tropa1[0]*$reg['tropa6'];
			$ataque_tropa7=$d_tropa1[0]*$reg['tropa7'];
			$ataque_tropa8=$d_tropa1[0]*$reg['tropa8'];
			$ataque_tropa9=$d_tropa1[0]*$reg['tropa9'];
			$ataque_tropa10=$d_tropa1[0]*$reg['tropa10'];

			$ataque_inf=$ataque_tropa1+$ataque_tropa2+$ataque_tropa3+$ataque_tropa7+$ataque_tropa8
			+$ataque_tropa9+$ataque_tropa10;
			$ataque_cab=$ataque_tropa4+$ataque_tropa5+$ataque_tropa6;
			$ataque=$ataque_inf+$ataque_cab;
			/***************************************************************************/

			$defensai_tropa1=$d_tropa1[1]*$tropas_suyas[0];
			$defensai_tropa2=$d_tropa2[1]*$tropas_suyas[1];
			$defensai_tropa3=$d_tropa3[1]*$tropas_suyas[2];
			$defensai_tropa4=$d_tropa4[1]*$tropas_suyas[3];
			$defensai_tropa5=$d_tropa5[1]*$tropas_suyas[4];
			$defensai_tropa6=$d_tropa6[1]*$tropas_suyas[5];
			$defensai_tropa7=$d_tropa7[1]*$tropas_suyas[6];
			$defensai_tropa8=$d_tropa8[1]*$tropas_suyas[7];
			$defensai_tropa9=$d_tropa9[1]*$tropas_suyas[8];
			$defensai_tropa10=$d_tropa10[1]*$tropas_suyas[9];

			$defensa_inf=$defensai_tropa1+$defensai_tropa2+$defensai_tropa3+$defensai_tropa4+$defensai_tropa5
			+$defensai_tropa6+$defensai_tropa7+$defensai_tropa8+$defensai_tropa9+$defensai_tropa10;

			$defensac_tropa1=$d_tropa1[2]*$tropas_suyas[0];
			$defensac_tropa2=$d_tropa2[2]*$tropas_suyas[1];
			$defensac_tropa3=$d_tropa3[2]*$tropas_suyas[2];
			$defensac_tropa4=$d_tropa4[2]*$tropas_suyas[3];
			$defensac_tropa5=$d_tropa5[2]*$tropas_suyas[4];
			$defensac_tropa6=$d_tropa6[2]*$tropas_suyas[5];
			$defensac_tropa7=$d_tropa7[2]*$tropas_suyas[6];
			$defensac_tropa8=$d_tropa8[2]*$tropas_suyas[7];
			$defensac_tropa9=$d_tropa9[2]*$tropas_suyas[8];
			$defensac_tropa10=$d_tropa10[2]*$tropas_suyas[9];

			$defensa_cab=$defensac_tropa1+$defensac_tropa2+$defensac_tropa3+$defensac_tropa4
			+$defensac_tropa5+$defensac_tropa6+$defensac_tropa7+$defensac_tropa8+$defensac_tropa9
			+$defensac_tropa10;

			$defensa_total=$defensa_inf+$defensa_cab;

			$porcentaje_inf_atacante=$ataque_inf/$ataque*100;
			$porcentaje_cab_atacante=$ataque_cab/$ataque*100;


			$defensa=($defensa_inf/100*$porcentaje_inf_atacante)+($defensa_cab/100*$porcentaje_cab_atacante);

			$tropas_restantes=array();

			if ($ataque > $defensa) //Si gana el atacante
			{
				$relacion=$ataque/($ataque-$defensa);

				for ($i=1;$i<11;$i++)
				{
					if (($reg['tropa'.$i]/$relacion)-(intval($reg['tropa'.$i]/$relacion)) > 0.79)
					{
						$tropas_restantes[]=ceil($reg['tropa'.$i]/$relacion);
					}

					else if (($reg['tropa'.$i]/$relacion)-(intval($reg['tropa'.$i]/$relacion)) < 0.21)
					{
						$tropas_restantes[]=floor($reg['tropa'.$i]/$relacion);
					}

					else
					{
						if($porcentaje_inf_defensora >= $porcentaje_cab_defensora)
						{
							if ($i > 3 and $i < 7)
							{
								$tropas_restantes[]=floor($reg['tropa'.$i]/$relacion);
							}
							else
							{
								$tropas_restantes[]=ceil($reg['tropa'.$i]/$relacion);
							}
						}
						else
						{
							if ($i > 3 and $i < 7)
							{
								$tropas_restantes[]=ceil($reg['tropa'.$i]/$relacion);
							}
							else
							{
								$tropas_restantes[]=floor($reg['tropa'.$i]/$relacion);
							}
						}
					}
				}

				/*$sql="select * from ataques where id_ciudad_atacante=$this->id_ciudad";
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();*/

				$tropas=$reg['tropa1'];
				for ($i=2;$i<11;$i++)
				{
					$tropas = $tropas."-".$reg['tropa'.$i];
				}

				$tropas_eliminadas=$reg['tropa1']-$tropas_restantes[0];
				for ($i=1;$i<10;$i++)
				{
					$tropas_eliminadas = $tropas_eliminadas."-".($reg["tropa".($i+1)]-$tropas_restantes[$i]);
				}

				$tropasd=$tropas_suyas[0];
				for ($i=1;$i<10;$i++)
				{
					$tropasd = $tropasd."-".$tropas_suyas[$i];
				}
				
				$capacidad_saqueo=0;

				for ($i=0;$i<count($tropas_restantes);$i++)
				{
					$temp_tropa=$this->datos_tropa('tropa'.($i+1));
					$capacidad_saqueo = $capacidad_saqueo+$tropas_restantes[$i]*$temp_tropa[3];
				}

				//***************SAQUEO****************************************/
				$sql="select * from mapa where id_casilla = ".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();

				$recursos_totales=$red['madera']+$red['barro']+$red['hierro']+$red['cereal'];

				if ($capacidad_saqueo>$recursos_totales)
				{
					$capacidad_saqueo=$recursos_totales;
				}

				$relacion_capacidad=$recursos_totales/$capacidad_saqueo;
				$madera_saquea=floor($red['madera']/$relacion_capacidad);
				$barro_saquea=floor($red['barro']/$relacion_capacidad);
				$hierro_saquea=floor($red['hierro']/$relacion_capacidad);
				$cereal_saquea=floor($red['cereal']/$relacion_capacidad);

				$botin=$madera_saquea."-".$barro_saquea."-".$hierro_saquea."-".$cereal_saquea;

				$sql="update mapa set madera=madera-$madera_saquea, barro=barro-$barro_saquea,
				hierro = hierro-$hierro_saquea, cereal = cereal-$cereal_saquea where id_casilla =".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);
				//**************************************************************/

				$sql="insert into reportes_tropas values (null,'atacar','$botin',".$reg['id_ciudad_atacante'].",".$reg['id_ciudad_atacada'].",'$tropas','$tropas_eliminadas','$tropasd','$tropasd',".($reg['fecha']+$tiempo_tardara).")";
				$res=$this->mysqli->query($sql);

				$sql="insert into vuelta_ataques values (null,'atacar','$botin',".$reg['id_ciudad_atacante'].",".$reg['id_ciudad_atacada'].",".$tropas_restantes[0]."
				,".$tropas_restantes[1].",".$tropas_restantes[2].",".$tropas_restantes[3]."
				,".($tropas_restantes[4]).",".($tropas_restantes[5]).",".($tropas_restantes[6])."
				,".($tropas_restantes[7]).",".($tropas_restantes[8]).",".($tropas_restantes[9]).",".($reg['fecha']+$tiempo_tardara).")";
				$res=$this->mysqli->query($sql);

				$sql="delete from tropas_refuerzos where id_ciudad_reforzada = ".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);

				$sql="update tropas set tropa1=0,tropa2=0,tropa3=0,tropa4=0,tropa5=0,tropa6=0,tropa7=0,
				tropa8=0,tropa9=0,tropa10=0 where id_ciudad = ".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);

				$sql="delete from ataques where id_ataque=$id";
				$res=$this->mysqli->query($sql);
				
				/*echo "<br />Datos:<br /> Tropas que han sobrevivido: $tropas_restantes[0] <br />
				Tropas que habia antes:".$reg['tropa1']."<br />
				Ataque Infanteria: $ataque_inf <br />
				Ataque Caballeria: $ataque_cab <br />
				Ataque: $ataque <br />
				Defensa Infanteria: $defensa_inf <br /> 
				Defensa Cab Tropa1: $defensac_tropa1 <br />
				Defensa Caballeria: $defensa_cab <br />
				Defensa Total: $defensa_total <br />
				Defensa: $defensa<br />
				Botin: $botin<br />
				Madera: ".$red['madera']."<br />
				Relacion capacidad: $relacion_capacidad<br />
				Recursos totales: $recursos_totales <br />
				capacidad_saqueo: $capacidad_saqueo";*/

			}

			else //Si gana el defensor
			{
				$relacion=$defensa/($defensa-$ataque);
				for ($i=1;$i<11;$i++)
				{
					if (($tropas_suyas[$i-1]/$relacion)-(intval($tropas_suyas[$i-1]/$relacion)) > 0.79)
					{
						$tropas_restantes[]=ceil($tropas_suyas[$i-1]/$relacion);
					}

					else if (($tropas_suyas[$i-1]/$relacion)-(intval($tropas_suyas[$i-1]/$relacion)) < 0.21)
					{
						$tropas_restantes[]=floor($tropas_suyas[$i-1]/$relacion);
					}

					else
					{
						if($porcentaje_inf_atacante >= $porcentaje_cab_atacante)
						{
							if ($i > 3 and $i < 7)
							{
								$tropas_restantes[]=floor($tropas_suyas[$i-1]/$relacion);
							}
							else
							{
								$tropas_restantes[]=ceil($tropas_suyas[$i-1]/$relacion);
							}
						}
						else
						{
							if ($i > 3 and $i < 7)
							{
								$tropas_restantes[]=ceil($tropas_suyas[$i-1]/$relacion);
							}
							else
							{
								$tropas_restantes[]=floor($tropas_suyas[$i-1]/$relacion);
							}
						}
					}
				}

				$sql="select * from tropas where id_ciudad=".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);
				$red=$res->fetch_array();

				$tropas=$reg['tropa1'];
				for ($i=2;$i<11;$i++)
				{
					$tropas = $tropas."-".$reg['tropa'.$i];
				}

				$tropasd=$tropas_suyas[0];
				for ($i=1;$i<10;$i++)
				{
					$tropasd = $tropasd."-".$tropas_suyas[$i];
				}

				$tropasd_eliminadas=$tropas_suyas[0]-$tropas_restantes[0];
				for ($i=1;$i<10;$i++)
				{
					$tropasd_eliminadas = $tropasd_eliminadas."-".($tropas_suyas[$i]-$tropas_restantes[$i]);
				}

				$sql="delete from ataques where id_ataque=$id";
				$res=$this->mysqli->query($sql);
				$sql="insert into reportes_tropas values (null,'atacar','0-0-0-0',".$reg['id_ciudad_atacante'].",".$reg['id_ciudad_atacada'].",'$tropas','$tropas','$tropasd','$tropasd_eliminadas',".($reg['fecha']+$tiempo_tardara).")";
				$res=$this->mysqli->query($sql);
				$this->firephp->log($sql,'consulta reportes');
				$relacion=$this->relacionTropasRefuerzos($reg['id_ciudad_atacada']);
				$relacionTropasCiudad=array(0,0,0,0,0,0,0,0,0,0);
				for ($i=0;$i<count($relacion);$i++)
				{
					$this->firephp->log($relacion[0][1][0],"relacion legionarios ".$i);
					for ($j=0;$j<10;$j++)
					{
						$relacionTropasCiudad[$i]+=$relacion[$i][1][$j];
					}
				}
				for ($i=0;$i<10;$i++)
				{
					$relacionTropasCiudad[$i]=100-$relacionTropasCiudad[$i];
				}

				$sql="update tropas set tropa1=tropa1-".($tropas_restantes[0]/100*$relacionTropasCiudad[0]).",tropa2=tropa2-$tropas_restantes[1],
				tropa3=tropa3-".($tropas_restantes[2]/100*$relacionTropasCiudad[2]).",tropa4=tropa4-$tropas_restantes[3],tropa5=tropa5-$tropas_restantes[4],
				tropa6=tropa6-$tropas_restantes[5],tropa7=tropa7-$tropas_restantes[6],tropa8=tropa8-$tropas_restantes[7],
				tropa9=tropa9-$tropas_restantes[8],tropa10=tropa10-$tropas_restantes[9] where id_ciudad = ".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);

				for ($i=0;$i<count($relacion);$i++)
				{
					$sql="update tropas_refuerzos set"
					." tropa1=tropa1-".round($tropas_restantes[0]/100*$relacion[$i][1][0]).""
					.", tropa2=tropa2-".round($tropas_restantes[1]/100*$relacion[$i][1][1]).""
					.", tropa3=tropa3-".round($tropas_restantes[2]/100*$relacion[$i][1][2]).""
					.", tropa4=tropa4-".round($tropas_restantes[3]/100*$relacion[$i][1][3]).""
					.", tropa5=tropa5-".round($tropas_restantes[4]/100*$relacion[$i][1][4]).""
					.", tropa6=tropa6-".round($tropas_restantes[5]/100*$relacion[$i][1][5]).""
					.", tropa7=tropa7-".round($tropas_restantes[6]/100*$relacion[$i][1][6]).""
					.", tropa8=tropa8-".round($tropas_restantes[7]/100*$relacion[$i][1][7]).""
					.", tropa9=tropa9-".round($tropas_restantes[8]/100*$relacion[$i][1][8]).""
					.", tropa10=tropa10-".round($tropas_restantes[9]/100*$relacion[$i][1][9]).""
					." where id_refuerzos = ".$relacion[$i][0];
					$res=$this->mysqli->query($sql);
				}
			}

			/***************************************************************************/

		}
		else if ($objetivo == 'atracar')
		{

		}
	}

	public function procesar_vuelta_tropas($id,$t_limite = null)
	{
		$sql="select * from vuelta_ataques where id_vuelta=$id";
		$resp=$this->mysqli->query($sql);
		if ($reg=$resp->fetch_array())
		{
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacante'];
			$res=$this->mysqli->query($sql);
			$rel=$res->fetch_array();
			$sql="select * from mapa where id_casilla =".$reg['id_ciudad_atacada'];
			$res=$this->mysqli->query($sql);
			$red=$res->fetch_array();

			$distancia_x = $rel['x']-$red['x'];
			$distancia_y = $rel['y']-$red['y'];
			if ($distancia_x<0)
			{
				$distancia_x = -($distancia_x);
			}
			if ($distancia_y<0)
			{
				$distancia_y= -($distancia_y);
			}
			$casillas_distancia=$distancia_x+$distancia_y;
			$tiempo_tardara=$casillas_distancia*300;
			$tiempo_restante=($reg['fecha']+$tiempo_tardara)-$this->t_actual;

			if ($tiempo_restante < 0)
			{
				$sql="update tropas set tropa1=tropa1+".$reg['tropa1'].",tropa2=tropa2+".$reg['tropa2'].",
				tropa3=tropa3+".$reg['tropa3'].",tropa4=tropa4+".$reg['tropa4'].",tropa5=tropa5+".$reg['tropa5'].",
				tropa6=tropa6+".$reg['tropa6'].",tropa7=tropa7+".$reg['tropa7'].",tropa8=tropa8+".$reg['tropa8'].",
				tropa9=tropa9+".$reg['tropa9'].",tropa10=tropa10+".$reg['tropa10']." where id_ciudad = ".$reg['id_ciudad_atacante'];
				$res=$this->mysqli->query($sql);

				$recursos_saqueados=explode('-',$reg['botin']);

				$sql="update mapa set madera=madera+$recursos_saqueados[0],barro=barro+$recursos_saqueados[1],
				hierro=hierro+$recursos_saqueados[2],cereal=cereal+$recursos_saqueados[3] where id_casilla = ".$reg['id_ciudad_atacante'];
				$res=$this->mysqli->query($sql);
				$sql="delete from vuelta_ataques where id_vuelta = $id";
				$res=$this->mysqli->query($sql);
			}
		}

	}

	public function tropas_ciudad($id)
	{
		$sql="select * from tropas where id_ciudad=$id";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$refuerzo=array($reg['tropa1'],$reg['tropa2'],$reg['tropa3'],$reg['tropa4'],$reg['tropa5'],$reg['tropa6'],
		$reg['tropa7'],$reg['tropa8'],$reg['tropa9'],$reg['tropa10']);

		$sql="select * from tropas_refuerzos where id_ciudad_reforzada=$id";
		$res=$this->mysqli->query($sql);
		while($red=$res->fetch_array())
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

	public function relacionTropasRefuerzos($id)
	{
		$sql="select * from tropas where id_ciudad=$id";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$refuerzo=array($reg['tropa1'],$reg['tropa2'],$reg['tropa3'],$reg['tropa4'],$reg['tropa5'],$reg['tropa6'],
		$reg['tropa7'],$reg['tropa8'],$reg['tropa9'],$reg['tropa10']);

		$sql="select * from tropas_refuerzos where id_ciudad_reforzada=$id";
		$res=$this->mysqli->query($sql);
		while($red=$res->fetch_array())
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
				if ($red['tropa'.$i+1]==0 || $refuerzo[$i]==0)
				{
					$relacionTropas[$i]=0;
				}
				else
				{
					$relacionTropas[$i]=$red["tropa".($i+1)]/$refuerzo[$i]*100;
				}
			}
			$relacionRefuerzos[]=array($red['id_refuerzos'],$relacionTropas);
		}

		return $relacionRefuerzos;
	}

	public function datos_tropa($tropa)
	{
		$sql="select * from datos_tropas where tropa = '$tropa'";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$datos=array($reg['ataque'],$reg['defensa'],$reg['defensa_caballeria'],$reg['capacidad']);

		return $datos;
	}

	public function mostrar_reportes()
	{
		$sql="select * from reportes_tropas where id_ciudad_atacada=$this->id_ciudad or id_ciudad_atacante=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		while($reg=$res->fetch_array())
		{
			?>
			<?php echo $reg['objetivo'];?> | <a href="reportes.php?s=1&r=<?php echo $reg['id_reporte'];?>"><?php echo Datos::ciudad($reg['id_ciudad_atacante'])." ataca a ".Datos::ciudad($reg['id_ciudad_atacada'])."</a> | ".date("m/d/Y h:i:s",$reg['fecha'])."<br />";?>
			<?php
		}
	}

	public function mostrar_reporte()
	{
		$sql="select * from reportes_tropas where id_reporte=".$_GET['r'];
		$res=$this->mysqli->query($sql);
		if($reg=$res->fetch_array())
		{
			$tropas=explode('-',$reg['tropas_atacante']);
			$tropasp=explode('-',$reg['tropasp_atacante']);
			$tropasd=explode('-',$reg['tropas_atacadas']);
			$tropasdp=explode('-',$reg['tropasp_atacadas']);
			$recursos=explode('-',$reg['botin']);
			if ($reg['id_ciudad_atacante']==$this->id_ciudad && $reg['objetivo']=='atacar' || $reg['id_ciudad_atacada']==$this->id_ciudad && $reg['objetivo']=='atacar')
			{
				?>
				<table>
					<tr>
						<td></td>
						<td><?php echo Datos::tropa('tropa1');?></td>
						<td><?php echo Datos::tropa('tropa2');?></td>
						<td><?php echo Datos::tropa('tropa3');?></td>
						<td><?php echo Datos::tropa('tropa4');?></td>
						<td><?php echo Datos::tropa('tropa5');?></td>
						<td><?php echo Datos::tropa('tropa6');?></td>
						<td><?php echo Datos::tropa('tropa7');?></td>
						<td><?php echo Datos::tropa('tropa8');?></td>
						<td><?php echo Datos::tropa('tropa9');?></td>
						<td><?php echo Datos::tropa('tropa10');?></td>
					</tr>
					<tr>
						<td><b>Tropas enviadas atacante</b></td>
						<td><?php echo $tropas[0];?></td>
						<td><?php echo $tropas[1];?></td>
						<td><?php echo $tropas[2];?></td>
						<td><?php echo $tropas[3];?></td>
						<td><?php echo $tropas[4];?></td>
						<td><?php echo $tropas[5];?></td>
						<td><?php echo $tropas[6];?></td>
						<td><?php echo $tropas[7];?></td>
						<td><?php echo $tropas[8];?></td>
						<td><?php echo $tropas[9];?></td>
					</tr>
					<tr>
						<td><b>Tropas perdidas atacante</b></td>
						<td><?php echo $tropasp[0];?></td>
						<td><?php echo $tropasp[1];?></td>
						<td><?php echo $tropasp[2];?></td>
						<td><?php echo $tropasp[3];?></td>
						<td><?php echo $tropasp[4];?></td>
						<td><?php echo $tropasp[5];?></td>
						<td><?php echo $tropasp[6];?></td>
						<td><?php echo $tropasp[7];?></td>
						<td><?php echo $tropasp[8];?></td>
						<td><?php echo $tropasp[9];?></td>
					</tr>
					<tr>
						<td><b>Tropas perdidas defensor</b></td>
						<td><?php echo $tropasd[0];?></td>
						<td><?php echo $tropasd[1];?></td>
						<td><?php echo $tropasd[2];?></td>
						<td><?php echo $tropasd[3];?></td>
						<td><?php echo $tropasd[4];?></td>
						<td><?php echo $tropasd[5];?></td>
						<td><?php echo $tropasd[6];?></td>
						<td><?php echo $tropasd[7];?></td>
						<td><?php echo $tropasd[8];?></td>
						<td><?php echo $tropasd[9];?></td>
					</tr>
					<tr>
						<td><b>Tropas perdidas defensor</b></td>
						<td><?php echo $tropasdp[0];?></td>
						<td><?php echo $tropasdp[1];?></td>
						<td><?php echo $tropasdp[2];?></td>
						<td><?php echo $tropasdp[3];?></td>
						<td><?php echo $tropasdp[4];?></td>
						<td><?php echo $tropasdp[5];?></td>
						<td><?php echo $tropasdp[6];?></td>
						<td><?php echo $tropasdp[7];?></td>
						<td><?php echo $tropasdp[8];?></td>
						<td><?php echo $tropasdp[9];?></td>
					</tr>
				</table>
				<?php echo "Madera ".$recursos[0]." Barro ".$recursos[1]." Hierro ".$recursos[2]." Cereal ".$recursos[3]." | ".date("m/d/Y h:i:s",$reg['fecha']);?>
				<?php
			}
		}
	}

//********************************************************************************************************************
//****************************************FUNCIONES COMERCIAR********************************************************
//********************************************************************************************************************
	/*public function enviar() //Envia recursos
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

		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		if ($reg['madera']<$_POST['madera'])
		{
			header("Location:mercado.php?m=5");
			exit;
		}
		if ($reg['barro']<$_POST['barro'])
		{
			header("Location:mercado.php?m=5");
			exit;
		}
		if ($reg['hierro']<$_POST['hierro'])
		{
			header("Location:mercado.php?m=5");
			exit;
		}
		if ($reg['cereal']<$_POST['cereal'])
		{
			header("Location:mercado.php?m=5");
			exit;
		}

		//Datos de la ciudad a la que se envia
		$sql="select * from mapa where x = $x_ciudad and y = $y_ciudad limit 1";
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
			
			header("Location:edificio.php?edificio=mercado&m=3");
		}
		else
		{
			header("Location:edificio.php?edificio=mercado&m=4");
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
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		
		if ($reg[$r_ofrezco] < $ofrezco) //Si no tengo los recursos que oferto
		{
			header("Location:edificio.php?edificio=mercado&m=2");
		}

		else //Si los tengo
		{
			//Me quito los recursos que ofrezco
			$sql="update mapa set $r_ofrezco = $r_ofrezco-$ofrezco where id_casilla = $this->id_ciudad";
			$res=$this->mysqli->query($sql);

			//Creo la oferta
			$sql="insert into ofertas values (null,'$r_ofrezco',$ofrezco,'$r_busco',$busco,$this->id_ciudad)";
			$res=$this->mysqli->query($sql);

			header("Location:edificio.php?edificio=mercado&m=1");
		}
	}

	public function mostrar_ofertas() //Muestra las ofertas del mercado
	{
		//Datos de mi ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$ret=$res->fetch_array();

		//Busco las ofertas que no son de mi ciudad
		$sql="select * from ofertas where id_ciudad != $this->id_ciudad";
		$res=$this->mysqli->query($sql);

		while ($reg=$res->fetch_array()) //Mostramos todas la ofertas
		{
			$n_ciudad=Datos::ciudad($reg['id_ciudad']); //Muestra el nombre de la ciudad
			?>
			Ciudad: <?php echo $n_ciudad;?> | 
			Ofrece: <?php echo $reg['cantidad_ofrece'];?> de <?php echo $reg['recurso_ofrece'];?> | 
			Busca: <?php echo $reg['cantidad_busca'];?> de <?php echo $reg['recurso_busca'];

			if ($reg['cantidad_busca'] > $ret[$reg['recurso_busca']] || $reg['cantidad_busca']/500 > $this->comerciantes_disponibles) //Si no puedes pagar la oferta
			{
				echo " <i>No tienes sufientes recursos</i>";
			}
			else //Si puedes pagarla
			{
				?>
				<a href='comerciar.php?id_oferta=<?php echo $reg['id_oferta'];?>'>Aceptar</a>
				<?php
			}
			?>
			<br />
			<?php
		}
	}

	public function aceptar_oferta() //Aceptar la oferta
	{
		//Datos de mi ciudad
		$sql="select * from mapa where id_casilla = $this->id_ciudad limit 1";
		$res=$this->mysqli->query($sql);
		$ret=$res->fetch_array();

		//Datos de la oferta aceptada
		$sql="select * from ofertas where id_oferta = ".$_GET['id_oferta']." limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		$sql="select * from edificios_aldea where edificio = 'mercado' and id_ciudad=$this->id_ciudad";
		$res=$this->mysqli->query($sql);
		$rem=$res->fetch_array();

		$this->muestra_mercado($rem['nivel']);

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

		header("Location:edificio.php?edificio=mercado&m=3");
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
				if ($reg['id_ciudad_ofrece']==$this->id_ciudad) //Si nosotroso somos la ciudad que ofrecemos
				{
					//Cogemos los datos de mi ciudad y la que me compra
					$sql="select * from mapa where id_casilla = $this->id_ciudad";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();
					$sql="select * from mapa where id_casilla =".$reg['id_ciudad_busca']."";
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
							$sql="delete from intercambios where id_ciudad_ofrece = $this->id_ciudad or id_ciudad_busca = $this->id_ciudad";
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
								$sql="update intercambios set recurso_busca='vuelta', id_ciudad_busca = ".$reg['id_ciudad_busca'].",fecha=".$t_llega." where id_ciudad_ofrece = $this->id_ciudad";
								$res=$this->mysqli->query($sql);
							}
							else
							{
								//Me cargo a mi y a quien le llega los recursos
								$sql="update mapa set ".$reg['recurso_busca']." = ".$reg['recurso_busca']."+".$reg['cantidad_busca']." where id_casilla = $this->id_ciudad";
								$res=$this->mysqli->query($sql);
								$sql="update mapa set ".$reg['recurso_ofrece']." = ".$reg['recurso_ofrece']."+".$reg['cantidad_ofrece']." where id_casilla =".$reg['id_ciudad_busca'];
								$res=$this->mysqli->query($sql);

								$t_llega=$reg['fecha']+$tiempo_transporte; //Hora a la que debia llegar el comerciante
								//Hacemos que vuelva el comerciante
								$sql="update intercambios set recurso_busca='vuelta', recurso_ofrece='vuelta',id_ciudad_ofrece = ".$reg['id_ciudad_busca'].",fecha=".$t_llega." where id_ciudad_ofrece = $this->id_ciudad";
								$res=$this->mysqli->query($sql);
							}
			
						}

					}
					else //Si aun no ha llegado
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
				}

	//*************************************************************************

				if ($reg['id_ciudad_ofrece']!=$this->id_ciudad) //Si yo soy quien compra
				{
					//Cogemos los datos de mi ciudad y la que me compra
					$sql="select * from mapa where id_casilla = $this->id_ciudad";
					$res=$this->mysqli->query($sql);
					$red=$res->fetch_array();
					$sql="select * from mapa where id_casilla =".$reg['id_ciudad_ofrece']."";
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
						if ($reg['recurso_busca']=='vuelta' && $this->t_actual>=$reg['fecha']+$tiempo_transporte) //Si ha vuelto el mercado
						{
							$sql="delete from intercambios where id_ciudad_ofrece = $this->id_ciudad or id_ciudad_busca = $this->id_ciudad";
							$res=$this->mysqli->query($sql);
						}
						else //Si ha llegado el mercader
						{
							//Comprobamos si es un envio o un intercambio
							$sql="select * from intercambios where (id_ciudad_ofrece = $this->id_ciudad or id_ciudad_busca = $this->id_ciudad) and recurso_busca='enviar'";
							$res=$this->mysqli->query($sql);
							if ($res->num_rows>0)//Si es un envio
							{
								$rem=$res->fetch_array();
								$recursos = explode('-',$rem['cantidad_ofrece']);//Cogemos las cuatro clases de recursos

								//Cargo a quien le llega los recursos
								$sql="update mapa set madera = madera+".$recursos[0].", barro = barro+".$recursos[1].",hierro = hierro+".$recursos[2].", cereal = cereal+".$recursos[3]." where id_casilla=".$rem['id_ciudad_busca'];
								//$res=$this->mysqli->query($sql);

								$t_llega=$reg['fecha']+$tiempo_transporte; //Hora a la que debia llegar el comerciante
								//Hacemos que vuelva el comerciante
								$sql="update intercambios set recurso_busca='vuelta', id_ciudad_busca = ".$reg['id_ciudad_busca'].",fecha=".$t_llega." where id_ciudad_ofrece = $this->id_ciudad";
								//$res=$this->mysqli->query($sql);
							}
							else
							{
								//Me cargo a mi y a quien le llega los recursos
								$sql="update mapa set ".$reg['recurso_busca']." = ".$reg['recurso_busca']."+".$reg['cantidad_busca']." where id_casilla = ".$reg['id_ciudad_busca'];
								$res=$this->mysqli->query($sql);

								$sql="update mapa set ".$reg['recurso_ofrece']." = ".$reg['recurso_ofrece']."+".$reg['cantidad_ofrece']." where id_casilla =$this->id_ciudad";
								$res=$this->mysqli->query($sql);

								$t_llega=$reg['fecha']+$tiempo_transporte; //Hora a la que debia llegar el comerciante
								//Hacemos que vuelva el comerciante
								$sql="update intercambios set recurso_busca='vuelta', recurso_ofrece='vuelta', id_ciudad_busca = ".$reg['id_ciudad_busca'].",fecha=".$t_llega." where id_ciudad_busca = $this->id_ciudad";
								$res=$this->mysqli->query($sql);
							}
						}

					}

					else //Si aun no ha llegado
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
	}*/

//********************************************************************************************************************
//****************************************FUNCIONES MENSAJERIA********************************************************
//********************************************************************************************************************

	/*public function mostrar_mensajes() //Muestra los mensajes
	{

		//Muestra los mensajes que no hayamos eliminado
		$sql="select * from mensajes where id_destinatario=$this->id_usuario and eliminado_destinatario='no'";
		$res=$this->mysqli->query($sql);

		while($reg=$res->fetch_array()) //Los muestra todos 
		{
			echo Datos::usuario($reg['id_emisor'])." | ".$reg['asunto'];
			echo "<a href='mensaje.php?mensaje=".$reg['id_mensaje']."'>Leer</a> ";
			echo "<a href='eliminar_mensaje.php?mensaje=".$reg['id_mensaje']."'>Eliminar</a> <br/>";
		}

		echo "<br /><br />";
	}
	

	public function mostrar_mensaje()
	{
		$id_mensaje=safen($_GET['mensaje']);
		//Muestra los mensajes que no hayamos eliminado
		$sql="select * from mensajes where id_mensaje=$id_mensaje and eliminado_destinatario='no'";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows ==0)
		{
			header("Location:mensajeria.php");
			exit;
		}
		$reg=$res->fetch_array();

		echo Datos::usuario($reg['id_emisor']);
		echo "<br />";
		echo str_replace("\n", "<br />", $reg['mensaje']);
		echo "<br />";
		echo "<a href='responder.php?mensaje=".$reg['id_mensaje']."'>Responder</a> ";
	}

	public function formulario_responder_mensaje() //Muestra un formulario para responder un mensaje
	{
		//Muestra el mensaje seleccionado
		$sql="select * from mensajes where id_mensaje=".$_GET['mensaje']." and (id_destinatario=$this->id_usuario or id_emisor=$this->id_usuario) limit 1";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows == 0)
		{
			header("Location:mensajeria.php");
			exit;
		}
		$reg=$res->fetch_array();

		?>
		<form name="responder_mensaje" method="post" action="procesa_mensaje.php">

			<?php echo Datos::usuario($reg['id_emisor']);?><br/>
			Asunto <input type="text" name="asunto" value="<?php echo $reg['asunto'];?>" required/><br/>
			Mensaje 
			<textarea name="mensaje"></textarea><br />

			<input type="hidden" value="responder" name="accion"/>
			<input type="hidden" value="<?php echo $_GET['mensaje'];?>"name="id_mensaje"/>
			<input type="submit" value="Responder" />

		</form>
		<?php
	}

	public function enviar_mensaje() //Envia un mensaje
	{
		$id_destinatario=safen(Datos::id($_POST['destinatario']));	//ID del usuario al que va dirigido el mensaje
		$sql="select * from usuarios where id_usuario=$id_destinatario limit 1";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows == 0)
		{
			header("Location:mensajeria.php");
			exit;
		}

		$asunto=safeh($_POST['asunto']);	//Asunto del mensaje
		$mensaje="$this->usuario escribi&oacute;:<br />".safeh($_POST['mensaje']); //Contenido del mensaje

		//Enviamos el mensaje
		$sql="insert into mensajes values (null,$this->id_usuario,$id_destinatario,'$asunto','$mensaje','no','no','no','no',now(),0)";
		$res=$this->mysqli->query($sql);

		header("Location:mensajeria.php?m=1");
	}

	public function responder_mensaje() //Responde el mensaje
	{
		$id_mensaje=safen($_POST['id_mensaje']); //ID del mensaje a responder

		$sql="select * from mensajes where id_mensaje = $id_mensaje"; //Buscamos los datos del mensaje a responder
		$res=$this->mysqli->query($sql);
		if ($res->num_rows == 0)
		{
			header("Location:mensajeria.php");
			exit;
		}
		$reg=$res->fetch_array();

		$asunto=safeh($_POST['asunto']);	//Asunto del mensaje
		$mensaje="$this->usuario escribi&oacute;:<br/>".safeh($_POST['mensaje'])."<br/><hr />".safeh($reg['mensaje']); //El contenido de la respuesta mas el mensaje anterior

		//Lo respondemos
		$sql="insert into mensajes values (null,$this->id_usuario,".$reg['id_emisor'].",'$asunto','$mensaje','no','no','no','no',now(),$id_mensaje)";
		$res=$this->mysqli->query($sql);
		//echo $sql;
		header("Location:mensajeria.php?m=2");
	}

	public function eliminar_mensaje() //Elimina mensaje
	{
		$id_mensaje=safen($_GET['mensaje']); //ID mensaje a eliminar

		$sql="select * from mensajes where id_mensaje = $id_mensaje";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows == 0)
		{
			header("Location:mensajeria.php");
			exit;
		}

		$sql="update mensajes set eliminado_destinatario ='si' where id_mensaje=$id_mensaje"; //Hacemos que el usuario deje de verlo
		$res=$this->mysqli->query($sql);

		//Si los dos usuarios los han eliminado
		$sql="select * from mensajes where id_mensaje=$id_mensaje and eliminado_destinatario='si' and eliminado_emisor='si' limit 1";
		$res=$this->mysqli->query($sql);
		
		if($res->num_rows>0) //Si es asi
		{	
			//Eliminamos el mensaje
			$sql="delete from mensajes where id_mensaje=$id_mensaje and eliminado_destinatario='si' and eliminado_emisor='si'";
			$res=$this->mysqli->query($sql);
		}

		header("Location:mensajeria.php?m=3");
	}*/
//********************************************************************************************************************
//****************************************FUNCIONES RANKING***********************************************************
//********************************************************************************************************************

	public function mostrar_ranking()
	{
		if (isset($_GET['p']) and is_numeric($_GET['p']))
		{
			$pagina=ceil($_GET['p']);
		}
		else
		{
			$pagina=1;
		}
		$n_registros=5;
		$p1=$pagina*($n_registros)-$n_registros;
		$p2=$pagina*($n_registros-1);
		if ($p1==0)
		{
			$p1=0;
			$p2=$n_registros;
		}

		$puntos=0;
		$sql="select * from usuarios";
		$res=$this->mysqli->query($sql);
		$n_paginas=ceil($res->num_rows/$n_registros);

		$sql="select * from usuarios limit $p1,$p2";
		$res=$this->mysqli->query($sql);

		while($reg=$res->fetch_array())
		{
			$sql="select * from mapa where id_usuario = ".$reg['id_usuario'];
			$resp=$this->mysqli->query($sql);
			while($red=$resp->fetch_array())
			{
				$puntos=$puntos+$red['habitantes'];
			}
			?>
			Usuario  <a href='perfil.php?usuario=<?php echo $reg['nombre'];?>'><b><?php echo $reg['nombre']?></b></a> Puntos <b><?php echo $puntos;?></b>
			<?php
			echo "<br/><br/>";
			$puntos=0;
		}

		if ($pagina==1)
		{
			?>
			<<&nbsp;<
			<?php
		}
		else
		{
			?>
			<a href="ranking.php?p=1"><<</a>
			<a href="ranking.php?p=<?php echo $pagina-1;?>"><</a>&nbsp;
			<?php
		}
		?>
		 | 
		<?php
		if ($pagina==$n_paginas)
		{
			?>
			>&nbsp;>>
			<?php
		}
		else
		{
			?>
			<a href="ranking.php?p=<?php echo $pagina+1;?>">></a>&nbsp;
			<a href="ranking.php?p=<?php echo $n_paginas;?>">>></a>
			<?php
		}
	}
//********************************************************************************************************************
//****************************************FUNCIONES CUENTA************************************************************
//********************************************************************************************************************

	/*public function mostrar_pefil()
	{
		$puntos=0;
		if (isset($_GET['usuario']))
		{
			$usuario=$_GET['usuario'];
			$id_usuario=Datos::id($usuario);
		}
		else
		{
			$usuario=$this->usuario;
			$id_usuario=$this->id_usuario;
		}

		$sql="select * from usuarios where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		?>
		Nombre: <?php echo $reg['nombre'];?>
		<br />
		<div id="puntos"></div>
		<h3>Perfil</h3>
		<?php
		if (!isset($_GET['s']))
		{
			echo $reg['perfil'];
		}
		else
		{
			?>
			<form name="form_perfil" action="procesa_cuenta.php" method="post">
			<textarea name="perfil"><?php echo $reg['perfil'];?></textarea>
			<?php
		}
		echo "<hr />";

		$sql="select * from mapa where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		echo "<h3>Ciudades</h3>";
		while($red=$res->fetch_array())
		{
			echo "Nombre Aldea: <a href='aldea.php?x=".$red["x"]."&y=".$red["y"]."'><b>".$red['nombre']."</b></a>  |  Habitantes: <b>".$red['habitantes']."</b>";
			echo "<br/>";
			$puntos=$puntos+$red['habitantes'];
		}
		echo "<hr/>";
		?>
		<script type="text/javascript">
		$("#puntos").html("Puntos: "+<?php echo $puntos;?>);
		</script>
		<?php
		if ($usuario == $this->usuario)
		{
			if (!isset($_GET['s']))
			{
				?><a href="perfil.php?s=1">Editar pefil</a><?php
			}
			else
			{
				?>
				<input type="submit" value="Enviar" />
				<input type="hidden" value="1" name="s" />
				</form>
				<?php
			}
		}
		else
		{
			?>
			<a href='redactar_mensaje.php?usuario=<?php echo $reg['nombre'];?>'>Enviar Mensaje</a>
			<?php
		}

	}

	public function cambiar_perfil()
	{
		$sql="update usuarios set perfil = '".$_POST['perfil']."' where id_usuario = $this->id_usuario";
		$res=$this->mysqli->query($sql);
		header("Location:perfil.php");
	}

	public function cambiar_password()
	{

	}

	public function cambiar_correo()
	{

	}

	public function cambiar_nombre()
	{

	}

	public function cambiar_nombre_aldea()
	{

	}

	public function eliminar_cuenta()
	{

	}*/

}


?>
