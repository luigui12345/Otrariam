<?php
class MotorTropas
{
	private $id_ciudad;
	private $mysqli;
	private $t_actual;
	private $recursos;
	private $tropas;
	private $firephp;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->id_ciudad=$_SESSION['ju_ciudad'];
		$this->t_actual=strtotime(date('Y-m-d H:i:s'));
		$this->tropas=new Tropas();
		$this->firephp = FirePHP::getInstance(true);
	}

	public function procesar_movimiento_tropas($id=null,$procesar=null,$time=null)
	{
		if (!isset($id))
		{
			$id=$this->id_ciudad;
		}
		if (isset($time))
		{
			$tempT=$this->t_actual;
			$this->t_actual=$time;
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
			$velocidad=5000;
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
					/*echo "<br />Hora a la que se mando ".$reg['fecha']."<br />
					Tiempo restante ".$tiempo_restante." <br />Tiempo tardara 
					".$tiempo_tardara." <br />
					Hora actual ".$this->t_actual."<br />
					Hora llegara ".($reg['fecha']+$tiempo_tardara);*/
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
		if (isset($time))
		{
			$this->t_actual=$tempT;
		}
	}

	public function accion_tropas($objetivo,$id,$procesar=null)
	{
		$this->recursos=new Aldea();
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
					/*echo "Hora a la que se mando ".$reg['fecha']."<br />
					Tiempo tardara ".$tiempo_tardara."<br />
					Hora actual ".$this->t_actual."<br />
					Id Ciudad ".$this->id_ciudad."<br />
					Hora llegara ".($reg['fecha']+$tiempo_tardara)."<br />";*/
					$this->recursos->comprobar_recursos('no','si',$this->id_ciudad,$this->t_actual);

					$this->id_ciudad=$temp;
					$this->t_actual=$temp1;
				}
				else
				{
					
				}
				/**********************************************************/

			//$tropas_mias=$this->tropas_ciudad($this->id_ciudad);
			$tropasClase=new Tropas();
			$tropas_suyas=$tropasClase->tropas_ciudad($reg['id_ciudad_atacada']);

			/***************************************************************************/
			$d_tropa1=$this->tropas->datos_tropa('tropa1');
			$d_tropa2=$this->tropas->datos_tropa('tropa2');
			$d_tropa3=$this->tropas->datos_tropa('tropa3');
			$d_tropa4=$this->tropas->datos_tropa('tropa4');
			$d_tropa5=$this->tropas->datos_tropa('tropa5');
			$d_tropa6=$this->tropas->datos_tropa('tropa6');
			$d_tropa7=$this->tropas->datos_tropa('tropa7');
			$d_tropa8=$this->tropas->datos_tropa('tropa8');
			$d_tropa9=$this->tropas->datos_tropa('tropa9');
			$d_tropa10=$this->tropas->datos_tropa('tropa10');

			/***************************************************************************/
			$ataque_tropa1=$d_tropa1[0]*$reg['tropa1'];
			$ataque_tropa2=$d_tropa2[0]*$reg['tropa2'];
			$ataque_tropa3=$d_tropa3[0]*$reg['tropa3'];
			$ataque_tropa4=$d_tropa4[0]*$reg['tropa4'];
			$ataque_tropa5=$d_tropa5[0]*$reg['tropa5'];
			$ataque_tropa6=$d_tropa6[0]*$reg['tropa6'];
			$ataque_tropa7=$d_tropa7[0]*$reg['tropa7'];
			$ataque_tropa8=$d_tropa8[0]*$reg['tropa8'];
			$ataque_tropa9=$d_tropa9[0]*$reg['tropa9'];
			$ataque_tropa10=$d_tropa10[0]*$reg['tropa10'];

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
						if($defensa_inf >= $defensa_cab)
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
					$temp_tropa=$this->tropas->datos_tropa('tropa'.($i+1));
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
				$tropasDTEliminadas=array();
				for ($i=0;$i<10;$i++)
				{
					$tropasdDTEliminadas[$i] = $tropas_suyas[$i]-$tropas_restantes[$i];
				}
				for ($i=1;$i<10;$i++)
				{
					$tropasd_eliminadas = $tropasd_eliminadas."-".($tropas_suyas[$i]-$tropas_restantes[$i]);
				}

				$sql="delete from ataques where id_ataque=$id";
				$res=$this->mysqli->query($sql);
				$sql="insert into reportes_tropas values (null,'atacar','0-0-0-0',".$reg['id_ciudad_atacante'].",".$reg['id_ciudad_atacada'].",'$tropas','$tropas','$tropasd','$tropasd_eliminadas',".($reg['fecha']+$tiempo_tardara).")";
				$res=$this->mysqli->query($sql);
				$this->firephp->log($sql,'consulta reportes');
				$relacion=$this->tropas->relacionTropasRefuerzos($reg['id_ciudad_atacada']);
				$relacionTropasCiudad=array(0,0,0,0,0,0,0,0,0,0);
				for ($i=0;$i<count($relacion);$i++)
				{
					$this->firephp->log($relacion[0][1][0],"relacion legionarios ".$i);
					for ($j=0;$j<10;$j++)
					{
						$relacionTropasCiudad[$j]+=$relacion[$i][1][$j];
					}
				}

				for ($i=0;$i<10;$i++)
				{
					$relacionTropasCiudad[$i]=100-$relacionTropasCiudad[$i];
				}

				$sql="update tropas set 
				 tropa1=tropa1-".round($tropasdDTEliminadas[0]/100*$relacionTropasCiudad[0])."
				,tropa2=tropa2-".round($tropasdDTEliminadas[1]/100*$relacionTropasCiudad[1])."
				,tropa3=tropa3-".round($tropasdDTEliminadas[2]/100*$relacionTropasCiudad[2])."
				,tropa4=tropa4-".round($tropasdDTEliminadas[3]/100*$relacionTropasCiudad[3])."
				,tropa5=tropa5-".round($tropasdDTEliminadas[4]/100*$relacionTropasCiudad[4])."
				,tropa6=tropa6-".round($tropasdDTEliminadas[5]/100*$relacionTropasCiudad[5])."
				,tropa7=tropa7-".round($tropasdDTEliminadas[6]/100*$relacionTropasCiudad[6])."
				,tropa8=tropa8-".round($tropasdDTEliminadas[7]/100*$relacionTropasCiudad[7])."
				,tropa9=tropa9-".round($tropasdDTEliminadas[8]/100*$relacionTropasCiudad[8])."
				,tropa10=tropa10-".round($tropasdDTEliminadas[9]/100*$relacionTropasCiudad[9])." where id_ciudad = ".$reg['id_ciudad_atacada'];
				$res=$this->mysqli->query($sql);

				for ($i=0;$i<count($relacion);$i++)
				{
					$sql="update tropas_refuerzos set"
					." tropa1=tropa1-".round($tropasdDTEliminadas[0]/100*$relacion[$i][1][0]).""
					.", tropa2=tropa2-".round($tropasdDTEliminadas[1]/100*$relacion[$i][1][1]).""
					.", tropa3=tropa3-".round($tropasdDTEliminadas[2]/100*$relacion[$i][1][2]).""
					.", tropa4=tropa4-".round($tropasdDTEliminadas[3]/100*$relacion[$i][1][3]).""
					.", tropa5=tropa5-".round($tropasdDTEliminadas[4]/100*$relacion[$i][1][4]).""
					.", tropa6=tropa6-".round($tropasdDTEliminadas[5]/100*$relacion[$i][1][5]).""
					.", tropa7=tropa7-".round($tropasdDTEliminadas[6]/100*$relacion[$i][1][6]).""
					.", tropa8=tropa8-".round($tropasdDTEliminadas[7]/100*$relacion[$i][1][7]).""
					.", tropa9=tropa9-".round($tropasdDTEliminadas[8]/100*$relacion[$i][1][8]).""
					.", tropa10=tropa10-".round($tropasdDTEliminadas[9]/100*$relacion[$i][1][9]).""
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

			$velocidad=Datos::velocidadEjercito($reg['id_vuelta'],'volver');
			$tiempo_tardara=$casillas_distancia/$velocidad*3600;
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
}
?>