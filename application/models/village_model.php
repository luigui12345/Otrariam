<?php
class Village_Model extends CI_Model
{
    public $x;
	public $y;
	public $id_town;
	public $id_user;
	public $production = array();
	public $current_time;
	private $construira_almacen=0; //Hora a la que se construira el almacen
	public $last_update;
	public $capacity;
	public $p_building;
	public $ps_building;
	
    function __construct()
    {
        parent::__construct();
        $query = $this->db->get_where('map', array('id' => $this->session->userdata('id_town')));	
        $row = $query->row();		
		$this->x = $row->x;
		$this->y = $row->y;
		$this->id_town  = $row->id;
		$this->id_user  = $row->id_user;
		$this->current_time = strtotime(date('Y-m-d H:i:s'));
    }
	
	public function production($building) {
	    
		$sql = "select production from buildings where id_town = '".$this->id_town."' and building = '$building' limit 1";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0) {
		    $reg = $res->row();
		        if ($building == 'farm')
		            $production = $reg->production - $this->consumptionTroops();
         		else
		     		$production = $reg->production;
		} else 
		    $production = 0;
		
		return $production;
	}
	
	public function consumptionTroops() {
		
		$sql = "select * from troops where id_town = '".$this->id_town."' limit 1";
		$res = $this->db->query($sql);
		$rem = $res->row_array();
		$nTropas = 0;
		for ($i=1;$i<9;$i++)
		{
			$sq = "select consumption from data_troops where troop = 'troop".$i."'";
			$rees = $this->db->query($sq)->row_array();
			$cTropa = $rem['troop'.$i] * $rees['consumption'];
			$nTropas = $nTropas + $cTropa;
		}
		$sql = "select * from troops_reinforcements where id_town_reinforced = ".$this->id_town;
		$res = $this->db->query($sql);
		while($rem = $res->row_array())
		{
			for ($i=1;$i<11;$i++)
			{
				$sql = "select consumption from data_troops where troop = 'troop$i'";
				$res = $this->db->query($sql);
				$rel = $res->row_array();
				$cTropa = $rem['tropa'.$i] * $rel['consumption'];
				$nTropas = $nTropas + $cTropa;
			}
		}
		$sql = "select * from attack where id_town_attacker = $this->id_town";
		$res = $this->db->query($sql);
		while($rem=$res->row())
		{
			for ($i=1;$i<11;$i++)
			{
				$sql = "select consumption from data_troops where troop = 'troop$i'";
				$res = $this->db->query($sql);
				$rel = $res->row_array();
				$cTropa = $rem['troop'.$i] * $rel['consumption'];
				$nTropas = $nTropas + $cTropa;
			}
		}
		$sql = "select * from return_attack where id_town_attacker = $this->id_town";
		$res = $this->db->query($sql);
		while($rem = $res->row_array())
		{
			for ($i=1;$i<11;$i++)
			{
				$sql = "select consumption from data_troops where troop = 'troop$i'";
				$res = $db->query($sql);
				$rel = $res->row();
				$cTropa = $rem['troop'.$i] * $rel->consumption;
				$nTropas = $nTropas + $cTropa;
			}
		}
		return $nTropas;
	}
	
	public function show_buildings() {
		
		$j    = 1;
		$slot = 0;
		$buildingsSlot = array();
		$sql = $this->db->query("select * from buildings where id_town = '".$this->id_town."' order by slot");
		foreach($sql->result() as $reg)
		{
			$buildingsSlot[] = array($reg->building, $reg->slot);
		}

		$nBuildingsSlot = count($buildingsSlot);
		
		$text = '';
		for ($i=0; $i<$nBuildingsSlot; $i++)
		{
			for ($j=0; $j<$nBuildingsSlot;$j++)
			{
				if ($buildingsSlot[$j][1] == $i+1)
			        $slot = $buildingsSlot[$j][0];
			}
			$a = $i + 1;
			if ($slot === 0)
				$text .= '<a href="slot/'.$a.'"><img src="'.base_url('design/skin/village/slot.png').'" id="solar'.$a.'" class="slot" title="Construir building"></a>';
			else
				$text .= $this->datos_buildings($slot);
			
			$slot = 0;
		}
		return $text;
	}
	
	public function getBuildingConstructible()
	{
		$sql = "select building from buildings where id_town = $this->id_town and level = 0";
		$res = $this->db->query($sql);
		$html = '';
		foreach($res->result() as $row)
		{
			$html .= '<div class="nombre_edificio"><strong>'.$row->building.'</strong></div>

				<div class="edificio_descripcion">
				    El Foro es el centro de la ciudad, y agiliza los tiempos de construcción. Al tener el foro a este nivel los edificios tardarán un  <b>%</b> menos en construirse.
				</div>

				<img src="'.base_url('design/skin/buildings/'.$row->building.'.png').'" class="img_recurso" title="Foro">

				<div class="building_costs">
				<p>costruir</p>
				<div class="upgrade">'.$this->construction_costs($row->building, 1).'</div>
				</div>';
		}
		return $html;
	}
	
	public function datos_buildings($building)
	{
		$sql = "select * from buildings where id_town = ".$this->id_town." AND building = '".$building."' limit 1";
		$res = $this->db->query($sql);
		$reg = $res->row_array();

		$text = '<div id="solar'.$reg['slot'].'" class="solar">
				 <a href="'.$reg['building'].'/'.$reg['slot'].'">';
		
		$text .= '<img src="'.base_url('design/skin/buildings/'.$building.'.png').'" title="'.$building.'" class="img_solar">';
		$text .= '<div class="building_level">'.$reg['level'].'</div>
				</a>
				</div>';
		return $text;
	}
	
	public function construction_costs($building, $level)
	{
		$sql = "select wood, clay, iron, crop, time from construction_costs where building = '".$building."' and level = '$level+1' limit 1";
		$res = $this->db->query($sql);

		if($reg = $res->row_array()) //Muestra los recursos necesarios
		{

			//Pasar el recurso Tiempo a un formato horario
			$segundos = $reg["time"];
			$horas = intval($segundos/3600);
			$restoSegundos = $segundos%3600;
			$recurso_tiempo = $horas.':'.date("i:s",mktime (0,0,$restoSegundos,0,0,0));

			//Mostramos los recursos
			$html = "<img src='".base_url('design/skin/resources/wood.png')."' class='recurso_coste' title='".lang('wood')."'> ".$reg["wood"]."
			 | <img src='".base_url('design/skin/resources/clay.png')."' class='recurso_coste' title='".lang('clay')."'> ".$reg["clay"]."
			 | <img src='".base_url('design/skin/resources/iron.png')."' class='recurso_coste' title='".lang('iron')."'> ".$reg["iron"]."
			 | <img src='".base_url('design/skin/resources/crop.png')."' class='recurso_coste' title='".lang('crop')."'> ".$reg["crop"]."
			 | <img src='".base_url('design/skin/resources/time.png')."' class='recurso_coste' title='Tiempo'> ".$recurso_tiempo;
			//Comprueba si tiene los recursos suficientes
			$sql="select * from map where id_user = $this->id_user and wood >=".$reg["wood"]." and clay >=".$reg["clay"]."
			and iron>=".$reg["iron"]." and crop>=".$reg["crop"]." limit 1";
			$res = $this->db->query($sql);
			$html .= "<br />";
			if ($res->num_rows() > 0) //Si tiene recursos sufientes
			{
				$sql = "select * from events where id_town = $this->id_town";
				$res = $this->db->query($sql);
				if ($res->num_rows() > 0)
				{
					echo "</br>Ya se está construyendo otro building.";
				}
				else if ($this->buildingDisponible($building,$this->id_town)==0)
				{
					echo "<br />No cumples los requisitos para construir este building.";
				}
				else if ($this->buildingDisponible($building,$this->id_town)==1)
				{
					$html .= "</br><a href='".base_url('game/build/'.$building.'/'.$this->uri->segment(3))."'>Construir</a>";
				}
				
			}
			else
			{
				$html = "</br>No tienes suficientes recursos.";
			}
		}
		else
		{
			$html = "</br>Este building no puede ampliarse más.";
		}
		return $html;
    }
	
	public function buildingDisponible($building, $id_town) {

     	$j=0; //Contador para show el numero de la tropa
		$building_no_disponible=0;

		$sql = "select * from construction_costs where building = '$building'";
		$res = $this->db->query($sql);
		$reg=$res->row_array();	//Buscamos que unidades podemos reclutar de infanteria
		$j++;
		if ($reg['requirements']=="")
		{
			return 1;
			exit;
		}
		$requisitos=explode('|',$reg['requirements']);
		$temp=count($requisitos);
		for($i=0;$i<$temp;$i++)
		{
			$requisitos2=explode('_',$requisitos[$i]);
			$sql="select * from buildings where building = '$requisitos2[0]' and level >= $requisitos2[1] and id_town = $this->id_town limit 1";
			
			$resp = $this->db->query($sql);
			
			if ($resp->num_rows() == 0)
			{
				$building_no_disponible=1;
			}
		}
		unset($temp);
		if ($building_no_disponible==0)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function ordenar_ampliar($building, $slot)
	{

		$sql = "select COUNT(*) from events where id_town = $this->id_town";
		$res = $this->db->query($sql);
		$reg = $res->row_array();
		
		// check the number of elements in queue
		
		if ($slot == 0 || $slot > 10) //Si el slot no es correcto
		{
			echo "no";//header("Location:index.php");
			exit;
		}

		//Cogemos los datos del ayuntamiento
		$sql = "select production from buildings where building = 'town_hall' and id_town = $this->id_town limit 1";
		$res = $this->db->query($sql);
		$red = $res->row_array();

		//Cogemos los datos del building a ampliar
		$sql = "select building, level from buildings where building = '$building' and id_town = $this->id_town limit 1";
		$res = $this->db->query($sql);
		$reg = $res->row_array();

		//Buscamos sus costes_construcciones de ampliacon
		$sql = "select wood, clay, iron, crop, time from construction_costs where building = '".$reg['building']."' and level = ".$reg['level']."+1 limit 1";
		$res = $this->db->query($sql);
		$ret = $res->row_array();

		/*if (Datos::recursosSuficientes($this->id_town,$ret["wood"]."-".$ret["clay"]."-".$ret["iron"]."-".$ret["crop"])==0)
		{
			header("Location:index.php");
			exit;
		}*/

		$sql = "update map set wood = wood-".$ret["wood"].", clay = clay-".$ret["clay"].", iron = iron-".$ret["iron"].", crop = crop-".$ret["crop"]." where id_user = $this->id_user";
		$res = $this->db->query($sql);

		$bono_ayuntamiento = $red['production'] * $ret['time']/100; //Descuento de tiempo por el level del ayuntamiento

		$sql = "insert into events values (null,'$building', $this->current_time - $bono_ayuntamiento,$slot,$this->id_town)";
		$res = $this->db->query($sql);

		redirect("game/village");
	}
	
	public function calcular_recursos($building, $show) {
		
		$sql = "select production, resource, building, level FROM buildings WHERE id_town = $this->id_town AND building = '$building' limit 1";
		$res = $this->db->query($sql);
		$reg = $res->row_array();
		
		$sql = "select time from events where id_town = $this->id_town and building = '$building' limit 1";
		$res = $this->db->query($sql);

		if ($res->num_rows() >0) //Si hay una construccion
		{
			$ret = $res->row_array();

			//Miramos los costes_construcciones del building
			$sql = "select time from construction_costs where building = '".$reg["building"]."' and level = ".$reg["level"]."+1 limit 1";
			$res = $this->db->query($sql);
			$rel = $res->row_array();

			$t_construira = $rel["time"]; //Tiempo que dura la construccion
			$t_orden=$ret["time"]; //Hora a la que se ordeno construir
			$t_diferencia = $this->current_time-$t_orden;	//El tiempo que ha pasado desde que se ordeno construir
			$t_cuando = $t_construira+$t_orden; //Hora la que se construira

			//Miramos los costes_construcciones del building a construir
			$sql = "select time from construction_costs where building = '".$reg["building"]."' and level = ".$reg["level"]."+1 limit 1";
			$res = $this->db->query($sql);
			$rug = $res->row_array();
		
			if ($t_diferencia-$t_construira>0) //Si ha pasado la cola de espera del building
			{
				$t1=$t_cuando-$this->last_update; 			//Tiempo que ha pasado desde la hora a la que se actualizara y la ultima vez que actualizaste
				if ($building == 'farm')
				{
					$nTropas=$this->consumptionTroops($this->id_town);
					$p_building1=($reg['production']-$nTropas)/3600*$t1; 	//Lo que ha producido el building antes de la construccion
				}
				else
				{
					$p_building1=$reg['production']/3600*$t1; 	//Lo que ha producido el building antes de la construccion
				}
				$this->ampliar($building); 					//Ampliamos el building
				//Comprobamos los datos del building ampliado
				$sql = "select production FROM buildings WHERE id_town = $this->id_town AND building = '$building' limit 1";
				$res = $this->db->query($sql);
				$reg = $res->row_array();

				$t2= $this->current_time-$t_cuando; 			//Tiempo que ha pasado desde ahora hasta que se construyo
				if ($building == 'farm')
				{
					$nTropas=$this->consumptionTroops($this->id_town);
					$p_building2=($reg['production']-$nTropas)/3600*$t2; 	//Lo que ha producido el building antes de la construccion
				}
				else
				{
					$p_building2=$reg['production']/3600*$t2; 	//Lo que ha producido el building antes de la construccion
				}

				$this->p_building = $p_building1+$p_building2; 	//Lo que se ha producido en total
				if ($building == 'farm')
				{
					$nTropas=$this->consumptionTroops($this->id_town);
					$this->ps_building=($reg['production']-$nTropas)/3600; 	//Lo que producimos por segundo
				}
				else
				{
					$this->ps_building=$reg['production']/3600; 	//Lo que producimos por segundo
				}
			}

			else //Si aun no se ha construido
			{
				if ($building == 'farm')
				{
					$nTropas=$this->consumptionTroops($this->id_town);
					$this->p_building=($reg['production']-$nTropas)/3600*$this->t_transcurrido; //Lo producido
					$this->ps_building=($reg['production']-$nTropas)/3600; //Lo que producimos por segundo
				}
				else
				{
					$this->p_building=$reg['production']/3600*$this->t_transcurrido; //Lo producido
					$this->ps_building=$reg['production']/3600; //Lo que producimos por segundo
				}
				$tiempos=-($t_diferencia-$rug["time"]);	//Tiempo restante para la ampliacion
				$tiempos = (string)$tiempos;				//Lo hacemos cadena para poder trabajarlo

				if ($show == "si")//Si queremos que se muestre el timer
				{
				$script = '<div id="tiempo_ampliacion">
					<script type="text/javascript" language="javascript">
					var tiempos = '.$tiempos.';

					function time() //Para show el tiempo
					{
						if (tiempos==0) //Si ha pasado el tiempo actualizamos
						{
							location.reload();
						}
						else 
						{
							//Restamos un segundo y lo mostramos
							tiempos--;
							document.getElementById("time").innerHTML = "<i class=\'icon-time\' title=\'Tiempo restante\'></i> Tiempo restante para la ampliación "+fecha(tiempos);
						}
					}
					time();
					setInterval("time()",1000); //Cada segundo se actualizara el timer
					</script>
					</div>';
					$this->layout->bind(array('queue' => $script));
				}
			}
		
		}
		else //Sino no se esta construyendo el building
		{
			$this->almacen(); //Declaramos las variables de almacen
			if ($this->construira_almacen!=0) //Si se esta construyendo el almacen
			{
				if ($building == 'farm')
				{	
					$nTropas = $this->consumptionTroops($this->id_town);
					$p_1=($reg['production']-$nTropas)/3600*$this->tiempo_almacen; //Lo que se produce antes de que se contruya el almacen
				}
				else
				{
					$p_1=$reg['production']/3600*$this->tiempo_almacen; //Lo que se produce antes de que se contruya el almacen
				}

				if ($building == 'woodcutter' || $building == 'iron_mine' || $building == 'Barrera' || $building == 'farm') //Si es un building de produccion el seleccionado
				{
					$sql="select wood,clay,iron,crop from map where id = $this->id_town limit 1";
					$res=$this->db->query($sql);
					$rez=$res->row_array();

					if ($p_1+$rez[$reg['resource']]>$this->capacity) //Si al sumar la produccion se supera la capacity
					{
						$p_1=$this->capacity-$rez[$reg['resource']];	//Producimos lo que hace falta para llenar el almacen
					}
					
					if ($building == 'farm')
					{
						$nTropas=consumptionTroops($this->id_town);
						$t2=$this->current_time-$this->construira_almacen; //Tiempo que ha pasado desde que se amplio el almacen hasta ahora
						$p_2=($reg['production']-$nTropas)/3600*$t2;	//Produccion desde que se amplio el almacen

						$this->p_building=$p_1+$p_2; //Lo producido
						$this->ps_building=($reg['production']-$nTropas)/3600; //Lo que producimos por segundo
					}
					else
					{
						$t2=$this->current_time-$this->construira_almacen; //Tiempo que ha pasado desde que se amplio el almacen hasta ahora
						$p_2=$reg['production']/3600*$t2;	//Produccion desde que se amplio el almacen

						$this->p_building=$p_1+$p_2; //Lo producido
						$this->ps_building=$reg['production']/3600; //Lo que producimos por segundo
					}
				}
			}
			else //Sino se esta construyendo el almacen
			{
				$sql = "select production FROM buildings WHERE id_town = $this->id_town AND building='$building' limit 1";
				$res=$this->db->query($sql);
				$reg=$res->row_array();
				
				if ($building == 'farm') //Si el building es la granja le restamos el consumo de las tropas
				{
					$nTropas=$this->consumptionTroops($this->id_town);
					$this->p_building=($reg['production']-$nTropas)/3600*$this->t_transcurrido; //Lo producido
					$this->ps_building=($reg['production']-$nTropas)/3600; //Lo que producimos por segundo
				}
				else
				{
					$this->p_building=$reg['production']/3600*$this->t_transcurrido; //Lo producido
					$this->ps_building=$reg['production']/3600; //Lo que producimos por segundo
				}
			}		
		}
		
	}
	public function check_resources($show, $procesar_tropas=null, $id_town=null, $time=null) //Es el motor del juego
	{
		if (isset($id_town)) //Si queremos que se calcule otra ciudad
		{
			$tempI = $this->id_town;
		}
		if (isset($time))
		{
			$tempT = $this->current_time;
		}
		//Datos de nuestra ciudad
		$sql = "select * from map where id = $this->id_town limit 1";
		$res = $this->db->query($sql);
		$reg = $res->row_array();

		$this->last_update = $reg["last_update"];
		$this->t_transcurrido = $this->current_time - $this->last_update; //Tiempo que ha pasado desde la ultima vez que actualizamos

		//Calculamos los recursos de cada building
		//*****************************************************************************************
		$this->calcular_recursos("farm",$show);
		$p_farm=$this->p_building;
		$ps_farm=$this->ps_building;

		//*****************************************************************************************
		$this->calcular_recursos("woodcutter",$show);
		$p_woodcutter=$this->p_building;
		$ps_woodcutter=$this->ps_building;

		//*****************************************************************************************
		$this->calcular_recursos("Barrera",$show);
		$p_clay_pit=$this->p_building;
		$ps_clay_pit=$this->ps_building;

		//*****************************************************************************************
		$this->calcular_recursos("iron_mine",$show);
		$p_iron_mine=$this->p_building;
		$ps_iron_mine=$this->ps_building;

		//*****************************************************************************************
		$this->calcular_recursos("town_hall",$show);
		//*****************************************************************************************
		$this->calcular_recursos("warehouse",$show);
		//*****************************************************************************************
		$this->calcular_recursos("market",$show);
		//*****************************************************************************************
		$this->calcular_recursos("cuartel",$show);
		//*****************************************************************************************
		$this->calcular_recursos("establo",$show);
		//*****************************************************************************************
		$this->calcular_recursos("embassy",$show);
		//*****************************************************************************************
		$this->calcular_recursos("escondite",$show);
		//*****************************************************************************************
		$this->calcular_recursos("taller",$show);
		//*****************************************************************************************
		$this->calcular_reclutamiento(); //Reclutamos las tropas

		/*Se producen los movimientos de tropas
		if (!isset($procesar_tropas))
		{
			$this->mTropas->procesar_movimiento_tropas(null,null,$this->current_time);
		}
		else
		{
			$this->mTropas->procesar_movimiento_tropas($this->id_town,'si',$this->current_time);
		}*/
		
		//Actualizamos los recursos
		$sql="update map set last_update = $this->current_time, crop = crop+$p_farm, wood = wood+$p_woodcutter, clay = clay+$p_clay_pit, iron = iron+$p_iron_mine where id = '$this->id_town'";
		$res=$this->db->query($sql);

		//Comprobamos de nuevo los datos de la ciudad
		$sql="select * from map where id = $this->id_town limit 1";
		$res=$this->db->query($sql);
		$reg=$res->row_array();

		$this->last_update = $reg['last_update']; //Nuevo last_update
		$this->tiempo_almacen = 0;				//Quitamos las variables del almacen pues ya se ha comprobado
		$this->construira_almacen = 0;

		//Datos del almacen
		$sql = "select production from buildings where id_town = $this->id_town and building = 'warehouse' limit 1";
		$res = $this->db->query($sql);
		$rem = $res->row_array();

		$this->capacity = $rem['production'];//Capacidad del almacen

		//Hacemos que no supere la cantidad al limite de la capacity
		if ($reg['wood']>$this->capacity)
		{
			$sql="update map set wood = $this->capacity where id = $this->id_town";
			$res=$this->db->query($sql);
		}
		if ($reg['clay']>$this->capacity)
		{
			$sql="update map set clay = $this->capacity where id = $this->id_town";
			$res=$this->db->query($sql);
		}
		if ($reg['iron']>$this->capacity)
		{
			$sql="update map set iron = $this->capacity where id = $this->id_town";
			$res=$this->db->query($sql);
		}
		if ($reg['crop']>$this->capacity)
		{
			$sql="update map set crop = $this->capacity where id = $this->id_town";
			$res=$this->db->query($sql);
		}

		//Comprobamos de nuevo los datos de la ciudad
		$sql="select * from map where id = $this->id_town limit 1";
		$res=$this->db->query($sql);
		$reg=$res->row_array();

		//Mostramos los recursos
		$script = '
		<script type="text/javascript" language="javascript">
		var wood = '.$reg["wood"].'; 	//Cantidad de madera
		var clay = '.$reg["clay"].';	//Cantidad de barro
		var iron = '.$reg["iron"].';	//Cantidad de hierro
		var crop = '.$reg["crop"].';	//Cantidad de cereal
		var ps_woodcutter = '.$ps_woodcutter.';	//Produccion por segundo de madera
		var ps_clay_pit = '.$ps_clay_pit.';	//Produccion por segundo de barro
		var ps_iron_mine = '.$ps_iron_mine.';		//Produccion por segundo de hierro
		var ps_farm = '.$ps_farm.';	//Produccion por segundo de cereal
		var capacity = '.$this->capacity.';	//Capacidad del almacen

		actualiza_recursos();

		function actualiza_recursos() //Esto actualizara los recursos de acuerdo a la produccion
		{
			document.getElementById("r1").innerHTML = Math.floor(wood);
			document.getElementById("r2").innerHTML = Math.floor(clay);
			document.getElementById("r3").innerHTML = Math.floor(iron);
			document.getElementById("r4").innerHTML = Math.floor(crop);

			//Aumenta los recursos de acuerdo a la produccion
			wood += ps_woodcutter;
			clay += ps_clay_pit;
			iron += ps_iron_mine;
			crop += ps_farm;

			//Si se supera la capacity del almacen
			if (wood>capacity)
			{
				wood = capacity;
			}
			if (clay>capacity)
			{
				clay = capacity;
			}
			if (iron>capacity)
			{
				iron = capacity;
			}
			if (crop>capacity)
			{
				crop = capacity;
			}
		}
		setInterval("actualiza_recursos()", 1000); //Se actualizaran los recursos una vez por segundo
		</script>';
		$this->layout->bind(array('script' => $script));
		//Comprobamos los intercambios comerciales
		/*$this->mercado->procesar_comercio();
		if (isset($id_town)) //Si queremos que se calcule otra ciudad
		{
			$this->id_town=$tempI;
		}
		if (isset($time))
		{

			$this->current_time=$tempT;
		}*/
	}
    
	public function almacen() //Da valor a las variables del almacen
	{
		//Cogemos los datos de nuestro almacen
		$sql = "select level from buildings where id_town = $this->id_town and building = 'warehouse' limit 1";
		$res = $this->db->query($sql);
		$reg = $res->row_array();

		//Miramos si se esta construyendo el almacen
		$sql = "select * from events where id_town = $this->id_town and building = 'warehouse' limit 1";
		$res = $this->db->query($sql);

		if ($res->num_rows() >0) //Si se esta construyendo el almacen
		{
			$ret=$res->row_array();

			//Cogemos los datos del proximo almacen
			$sql = "select * from construction_costs where building = 'warehouse' and level = ".$reg["level"]."+1 limit 1";
			$res = $this->db->query($sql);
			$rel = $res->row_array();

			$ta_construira = $rel["time"]; //Tiempo que dura la construccion
			$ta_orden=$ret["time"]; //Hora a la que se ordeno construir
			$ta_diferencia = $this->current_time-$ta_orden;	//El tiempo que ha pasado desde que se ordeno construir
			$ta_cuando = $ta_construira+$ta_orden; //Hora la que se construira

			if ($ta_diferencia-$ta_construira>0) //Si ha pasado la cola de espera del building
			{
				$this->construira_almacen=$ta_cuando; //Hora a la que se construira el almacen
				$this->tiempo_almacen=$this->construira_almacen-$this->last_update; //Tiempo que queda para que se amplie el almacen
			}
		}
	}
	
	public function calcular_reclutamiento() //Reclutamos las unidades cuyo tiempo de reclutamiento ha pasado
	{
		$sql = "select * from production_queue where id_town = $this->id_town";
		$resp = $this->db->query($sql);
		if ($resp->num_rows() >0) //Si se esta reclutando alguna
		{
			while($reg=$resp->row_array())
			{
				$tropas_restantes=$reg['n_tropas']-$reg['n_tropas_reclutadas']; //Tropas que quedan por reclutar

				$sql="select time from data_troops where troop = '".$reg['troop']."' limit 2";
				$res=$this->db->query($sql);
				$red=$res->row_array();

				$tp_tropa=$red['time']; 				//Tiempo que tarda en producirse una tropa
				$tt_tropa=$tp_tropa*$reg['n_tropas']; 	//Tiempo que tardan en producirse todas
				$t_terminara=$tt_tropa+$reg['date'];	 //Hora a la que terminara de reclutarse todo

				//Tiempo pasado desde que ordene reclutar hasta ahora
				$t_transcurrido=$this->current_time-($tp_tropa*$reg['n_tropas_reclutadas']+$reg['fecha']);
				$tropas_reclutan=floor($t_transcurrido/$tp_tropa); //Para no reclutar de mas redondeamos hacia abajo
				if ($tropas_reclutan>$reg['n_tropas']) //Si las tropas que se reclutan intentasen superar las ordenadas se iguala
				{
					$tropas_reclutan=$reg['n_tropas'];
				}
				//Restamos las tropas reclutadas
				$sql="update cola_produccion set n_tropas_reclutadas=n_tropas_reclutadas+$tropas_reclutan where troop = '".$reg['troop']."' and id_ciudad=$this->id_ciudad";
				$res=$this->db->query($sql);
				//Las añadimos a nuestra ciudad
				$sql="update troops set ".$reg['troop']." = ".$reg['troop']."+$tropas_reclutan where id_ciudad = $this->id_town";
				$res=$this->db->query($sql);
				//Eliminamos los reclutamientos completados
				$sql="delete from cola_produccion where n_tropas<=n_tropas_reclutadas";
				$res=$this->db->query($sql);
			}
			
		}
	}
	
	public function ampliar($building) //Amplia un building
	{

		$sql = "select slot from events where building = '$building' and id_town = $this->id_town limit 1";
		$res = $this->db->query($sql);
		$rem = $res->row_array();

		$slot=$rem['slot'];

		//Cogemos los datos del building a amplair
		$sql="select building, level, population from buildings where building = '$building' and id_town = $this->id_town limit 1";
		$res=$this->db->query($sql);
		$reg=$res->row_array();

		//Miramos los costes_construcciones de su ampliacon
		$sql="select production, population from construction_costs where building = '".$reg['building']."' and level = ".$reg['level']."+1 limit 1";
		$res=$this->db->query($sql);
		$ret=$res->row_array();

		//Amplia el building
		$sql="update buildings set slot=$slot,level = level+1, production = ".$ret['production'].", population = ".$ret['population']." where id_town = $this->id_town and building = '$building'";
		$res=$this->db->query($sql);
		$crecimiento_habitantes = $ret["population"]-$reg["population"];

		//Aumenta los habitantes
		$sql="update map set population = population+$crecimiento_habitantes where id = $this->id_town ";
		$res=$this->db->query($sql);

		//Se quita la construccion
		$sql="DELETE FROM events WHERE id_town = $this->id_town";
		$res=$this->db->query($sql);
	}
}

/* End of file village_model.php */
/* Location: ./system/application/models/village_model.php */