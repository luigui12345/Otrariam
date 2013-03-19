<?php
Class Alianza
{
	private $id_ciudad;
	private $mysqli;
	private $usuario;
	private $id_usuario;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->id_ciudad=$_SESSION['ju_ciudad'];
		$this->usuario=$_SESSION["ju_nom"];
		$this->id_usuario=Datos::id($this->usuario);
		if (isset($_GET['i']))
		{
			$this->id_alianza=$_GET['i'];
		}
		else if (isset($_POST['i']))
		{
			$this->id_alianza=$_POST['i'];
		}
		else
		{
			$sql="select id_alianza from miembros_alianzas where id_usuario=$this->id_usuario and estado=1";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows > 0)
			{
				$reg=$res->fetch_array();
				$this->id_alianza=$reg['id_alianza'];
			}
			else
			{
				header("location:index.php");
			}
		}
	}

	public function mostrarAlianza()
	{
		$misCargos=array();
		$sql="select * from cargos_alianzas where id_alianza=$this->id_alianza and id_usuario=$this->id_usuario limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		for ($i=1;$i<11;$i++)
		{
			$misCargos[]=$reg["cargo$i"];
		}

		if (!isset($_GET['a']))
		{
			//$accion='index';
			$this->mostrarIndexAlianza();
		}
		else
		{
			if ($_GET['a']==0)
			{
				//$accion='index';
				$this->mostrarIndexAlianza();
			}
			else if ($_GET['a']==1 && $misCargos[0]==1)
			{
				//$accion='invitar';
				$this->mostrarInvitarAlianza();
			}
			else if ($_GET['a']==2 && $misCargos[1]==1)
			{
				//$accion='expulsar';
				$this->mostrarExpulsarAlianza();
			}
			else if ($_GET['a']==3 && $misCargos[2]==1)
			{
				//$accion='cargos';
				$this->mostrarCargosAlianza();
			}
			else if ($_GET['a']==4 && $misCargos[3]==1)
			{
				//$accion='diplomacia';
				$this->mostrarDiplomaciaAlianza();
				
			}
			else
			{
				header("location:alianza.php?i=$this->id_alianza");
			}
		}

		if ($misCargos[0]==1)
		{
			?>

			<?php
		}

	}

	public function mostrarIndexAlianza()
	{
		$sql="select nombre,fecha from alianzas where id_alianza=$this->id_alianza limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		?>

		<h2 id="nombre_alianza"><?php echo $reg['nombre']; ?></h2>
		<p>Fecha de la fundación: <b><?php echo $reg['fecha']; ?></b></p>

		<?php
		$sql="select id_usuario from miembros_alianzas where id_alianza=$this->id_alianza and estado=1";
		$res=$this->mysqli->query($sql);
		?>
		<h3>Usuarios</h3>

		<table class="tabla_ranking" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td>Usuario</td>
				<td>Puntuación</td>
			</tr>
			</thead>

			<tbody>

		<?php
		while($red=$res->fetch_array())
		{
			?>
			<tr>
				<td><a href='perfil.php?usuario=<?php echo Datos::usuario($red['id_usuario']); ?>'><?php echo Datos::usuario($red['id_usuario']); ?></a></td>
				<td>-</td>
			</tr>
			<?php
		}
		?>
			</tbody>
		</table>
		<?php
	}

	public function mostrarInvitarAlianza()
	{
		?>
		<form name="form_invitar" method="post" action="procesa_invitar.php">
		Invitar al usuario: <input type="text" name="usuario"/>
		<input type="hidden" name="i" value="<?php echo $this->id_alianza;?>" class="input_enviar"/>
		<input type="submit" value="Invitar" class="boton"/>
		</form>
		<br />
		<?php
		$sql="select id_usuario from miembros_alianzas where id_alianza=$this->id_alianza and estado=0";
		$res=$this->mysqli->query($sql);
		while($reg=$res->fetch_array())
		{
			echo Datos::usuario($reg['id_usuario']). " <a href='procesa_alianza.php?a=3&i=".$_GET['i']."&usuario=".Datos::usuario($reg['id_usuario'])."'>Eliminar</a><br />";
		}

	}

	public function mostrarExpulsarAlianza()
	{

		$sql="select id_usuario from miembros_alianzas where id_alianza=$this->id_alianza and estado=1 and id_usuario!=$this->id_usuario";
		$res=$this->mysqli->query($sql);

		?>

		<table class="tabla_ranking" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td>Usuario</td>
				<td>Puntuación</td>
				<td>¿Expulsar?</td>
			</tr>
			</thead>

			<tbody>

		<?php
		while($reg=$res->fetch_array())
		{
			?>

			<tr>
				<td><a href='perfil.php?usuario=<?php echo Datos::usuario($reg['id_usuario']); ?>'><?php echo Datos::usuario($reg['id_usuario']); ?></a></td>
				<td>-</td>
				<td><a href='procesa_alianza.php?a=5&i=<?php echo $this->id_alianza."&usuario=".Datos::usuario($reg['id_usuario']); ?>'>Expulsar</a></td>
			</tr>

			<br/>

			<?php
		}
		?>
			</tbody>
		</table>
		<?php
	}

	public function mostrarCargosAlianza()
	{
		$sql="select * from cargos_alianzas where id_alianza=$this->id_alianza";
		$res=$this->mysqli->query($sql);
		?>

		<table class="tabla_ranking" border="0" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td title="Miembro de la alianza">Miembros</td>
				<td title="Capacidad para invitar a otros miembros">Invitar</td>
				<td title="Capacidad para expulsar a otros miembros">Expulsar</td>
				<td title="Capacidad para dar poderes y cargos">Cargos</td>
				<td title="Capacidad para declarar guerras, alianzas y PNA´s a otras alianzas">Diplomacia</td>
				<td title="Título honorífico de cada miembro">Título</td>
				<td title=""> </td>
				<td title=""> </td>
			</tr>
			</thead>

			<tbody>
			
			<?php
			while($reg=$res->fetch_array())
			{
				?>
				<tr>
					<form name="form_cargos" method="post" action="procesa_alianza.php?a=6">
					<td><?php echo Datos::usuario($reg['id_usuario']);?></td>
					<?php
					for ($i=1;$i<6;$i++)
					{
						?>
						<td>
						<?php
						/*if ($reg["cargo$i"]==1)
						{
							?>
							<div style="border-radius:10px; margin-left:15px;text-align:center;width:10px;
							height:10px;background:green;" onclick="this.style.background='red';this.style.border='0.5px solid';">
							</div>
							<?php
						}
						else
						{
							?>
							<div style="border-radius:10px; margin-left:15px;text-align:center;
							width:10px;height:10px;background-color:red;" onclick="this.style.background='green';this.style.border='0.5px solid';">
							</div>
							<?php
						}*/
						if ($reg["cargo$i"]==1)
						{
							?>
							<input type="checkbox" name="cargo<?php echo $i;?>" checked/>
							<?php
						}
						else
						{
							?>
							<input type="checkbox" name="cargo<?php echo $i;?>"/>
							<?php
						}
						?>
						</td>
						<?php
					}
					?>
					<td><input type="text" name="cargo" value="<?php echo $reg['nombre'];?>" class="input_enviar auto"/></td>
					<input type="hidden" name="usuario" value="<?php echo $reg['id_usuario'];?>" class="input_enviar auto" />
					<td><input type="submit" value="Modificar" class="boton"/></td>
					</form>
				</tr>
				<?php
			}
		?>
			</tbody>
		</table>
		<?php
	}

	public function mostrarDiplomaciaAlianza()
	{
		?>
		<div>
			<?php
			$this->mostrarDeclararGuerra();
			?>
		</div>
		<hr /><br/><br/>
		<div>
			<?php
			$this->mostrarDeclararAlianza();
			?>
		</div>
		<hr /><br/><br/>
		<div>
			<?php
			$this->mostrarDeclararPNA();
			?>
		</div>
		<hr /><br/>
		<?php
	}

	public function mostrarDeclararAlianza()
	{
		?>
		<form name="form_guerra" method="post" action="procesa_alianza.php?a=7">
			<b>Pedir una Alianza</b><br /> <input type="text" name="alianza" class="input_enviar" required />
			<input type="submit" value="Pedir Alianza" class="boton" />
			<input type="hidden" name="accion" value="alianza" />
			</form>
			<?php
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='alianza' and (id_alianza_declara=$this->id_alianza or id_alianza_acepta=$this->id_alianza) and estado=1";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "Estas aliado con:<br />";
				while ($reg=$res->fetch_array())
				{
					if ($reg['id_alianza_declara']==$this->id_alianza)
					{
						echo "<a href='alianza.php?i=".$reg['id_alianza_acepta']."'>".Datos::nombreAlianza($reg['id_alianza_acepta'])."</a>";
					}
					else
					{
						echo "<a href='alianza.php?i=".$reg['id_alianza_declara']."'>".Datos::nombreAlianza($reg['id_alianza_declara'])."</a>";
					}
					echo "<br />";
				}
			}
			else
			{
				?>
				<i>Esta Alianza no tiene otras alianzas.</i>
				<?php
			}
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='alianza' and id_alianza_acepta=$this->id_alianza and estado=0";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "<br />Estas alianzas quieren aliarse contigo:<br />";
				while ($reg=$res->fetch_array())
				{
					echo Datos::nombreAlianza($reg['id_alianza_declara']);
					echo " <a href='procesa_alianza.php?a=8&i=".$reg['id_alianza_declara']."&oi=".$reg['id_alianza_acepta']."&t=alianza&r=1'>Aceptar</a>";
					echo " <a href='procesa_alianza.php?a=8&i=".$reg['id_alianza_declara']."&oi=".$reg['id_alianza_acepta']."&t=alianza&r=0'>Rechazar</a>";
				}
			}
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='alianza' and id_alianza_declara=$this->id_alianza and estado=0";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "<br />Has pedido una Alianza a estas alianzas:<br />";
				while ($reg=$res->fetch_array())
				{
					echo Datos::nombreAlianza($reg['id_alianza_acepta']);
					echo " <a href='procesa_alianza.php?a=8&oi=".$reg['id_alianza_acepta']."&i=".$reg['id_alianza_declara']."&t=alianza&r=0'>Cancelar</a>";
				}
			}
	}	

	public function mostrarDeclararPNA()
	{
		?>
		<form name="form_guerra" method="post" action="procesa_alianza.php?a=7">
			<b><b>Pedir un pacto de</b> no agresion (PNA)</b><br /> 
			<input type="text" name="alianza" class="input_enviar" required />
			<input type="submit" value="Pedir PNA"  class="boton"/>
			<input type="hidden" name="accion" value="pna" />
			</form>
			<?php
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='pna' and (id_alianza_declara=$this->id_alianza or id_alianza_acepta=$this->id_alianza) and estado=1";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "Tienes una PNA con:<br />";
				while ($reg=$res->fetch_array())
				{
					if ($reg['id_alianza_declara']==$this->id_alianza)
					{
						echo "<a href='alianza.php?i=".$reg['id_alianza_acepta']."'>".Datos::nombreAlianza($reg['id_alianza_acepta'])."</a>";
					}
					else
					{
						echo "<a href='alianza.php?i=".$reg['id_alianza_declara']."'>".Datos::nombreAlianza($reg['id_alianza_declara'])."</a>";
					}
					echo "<br />";
				}
			}
			else
			{
				?>
				<i>Esta Alianza no tiene Pactos de No Agresión.</i>
				<?php
			}
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='pna' and id_alianza_acepta=$this->id_alianza and estado=0";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "<br />Estas alianzas quieren tener un PNA contigo:<br />";
				while ($reg=$res->fetch_array())
				{
					echo Datos::nombreAlianza($reg['id_alianza_declara']);
					echo " <a href='procesa_alianza.php?a=8&i=".$reg['id_alianza_declara']."&oi=".$reg['id_alianza_acepta']."&t=pna&r=1'>Aceptar</a>";
					echo " <a href='procesa_alianza.php?a=8&i=".$reg['id_alianza_declara']."&oi=".$reg['id_alianza_acepta']."&t=pna&r=0'>Rechazar</a>";
				}
			}
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='pna' and id_alianza_declara=$this->id_alianza and estado=0";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "<br />Has pedido una PNA a estas alianzas:<br />";
				while ($reg=$res->fetch_array())
				{
					echo Datos::nombreAlianza($reg['id_alianza_acepta']);
					echo " <a href='procesa_alianza.php?a=8&oi=".$reg['id_alianza_acepta']."&i=".$reg['id_alianza_declara']."&t=pna&r=0'>Cancelar</a>";
				}
			}
	}	

	public function mostrarDeclararGuerra()
	{
		?>
		<form name="form_guerra" method="post" action="procesa_alianza.php?a=7">
			<b>Declara la guerra una Alianza</b><br /> 
			<input type="text" name="alianza" class="input_enviar" required />
			<input type="submit" value="Declarar Guerra" class="boton" />
			<input type="hidden" name="accion" value="guerra" />
			</form>
			<?php
			$sql="select id_alianza_declara,id_alianza_acepta from diplomacia_alianzas where tipo='guerra' and (id_alianza_declara=$this->id_alianza or id_alianza_acepta=$this->id_alianza) and estado=1";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "Estas en guerra con:<br />";
				while ($reg=$res->fetch_array())
				{
					if ($reg['id_alianza_declara']==$this->id_alianza)
					{
						echo "<a href='alianza.php?i=".$reg['id_alianza_acepta']."'>".Datos::nombreAlianza($reg['id_alianza_acepta'])."</a>";
					}
					else
					{
						echo "<a href='alianza.php?i=".$reg['id_alianza_declara']."'>".Datos::nombreAlianza($reg['id_alianza_declara'])."</a>";
					}
					echo "<br />";
				}
			}
			else
			{
				?>
				<i>Esta Alianza no está en guerra.</i>
				<?php
			}	
	}	

	public function aceptarTratado()
	{
		if ($_GET['t']=='pna')
		{
			if ($_GET['r']==1)
			{
				$sql="update diplomacia_alianzas set estado=1 where id_alianza_declara=".$_GET['i']." and id_alianza_acepta=".$_GET['oi']." and tipo='pna'";
				$res=$this->mysqli->query($sql);
				$sql="delete from diplomacia_alianzas where tipo='guerra' and (id_alianza_declara=".$_GET['i']." or 
				id_alianza_acepta=".$_GET['i'].") and (id_alianza_declara=".$_GET['oi']." or
				id_alianza_acepta=".$_GET['oi'].")";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?a=4");
			}
			else
			{
				$sql="delete from diplomacia_alianzas where estado=0 and id_alianza_declara=".$_GET['i']." and id_alianza_acepta=".$_GET['oi']." and tipo='pna'";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?a=4");
			}
		}
		else if ($_GET['t']=='alianza')
		{
			if ($_GET['r']==1)
			{
				$sql="update diplomacia_alianzas set estado=1 where id_alianza_declara=".$_GET['i']." and id_alianza_acepta=".$_GET['oi']." and tipo='alianza'";
				$res=$this->mysqli->query($sql);
				$sql="delete from diplomacia_alianzas where tipo='guerra' and (id_alianza_declara=".$_GET['i']." or 
				id_alianza_acepta=".$_GET['i'].") and (id_alianza_declara=".$_GET['oi']." or
				id_alianza_acepta=".$_GET['oi'].")";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?a=4");
			}
			else
			{
				$sql="delete from diplomacia_alianzas where estado=0 and id_alianza_declara=".$_GET['i']." and id_alianza_acepta=".$_GET['oi']." and tipo='alianza'";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?a=4");
			}
		}
	}

	public function declararDiplomacia()
	{
		if ($_POST['accion']=='guerra')
		{
			$sql="insert into diplomacia_alianzas values (null,'guerra',$this->id_alianza,".Datos::idAlianza($_POST['alianza']).",now(),1)";
			$res=$this->mysqli->query($sql);
			$sql="delete from diplomacia_alianzas where (tipo='pna' or tipo='alianza') and (id_alianza_declara=$this->id_alianza or 
			id_alianza_acepta=$this->id_alianza) and (id_alianza_declara=".Datos::idAlianza($_POST['alianza'])." or
			id_alianza_acepta=".Datos::idAlianza($_POST['alianza']).")";
			$res=$this->mysqli->query($sql);
			header("location:alianza.php?i=$this->id_alianza&a=4");
		}
		if ($_POST['accion']=='alianza')
		{
			$sql="select COUNT(*) from diplomacia_alianzas where (id_alianza_declara=$this->id_alianza or 
			id_alianza_acepta=$this->id_alianza) and (id_alianza_declara=".Datos::idAlianza($_POST['alianza'])." or
			id_alianza_acepta=".Datos::idAlianza($_POST['alianza']).") and tipo='alianza' limit 1";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();
			if ($reg[0]>0)
			{
				header("location:alianza.php?i=$this->id_alianza&a=4&m=1");
				exit;
			}
			$sql="insert into diplomacia_alianzas values (null,'alianza',$this->id_alianza,".Datos::idAlianza($_POST['alianza']).",now(),0)";
			$res=$this->mysqli->query($sql);
			header("location:alianza.php?i=$this->id_alianza&a=4");
		}
		if ($_POST['accion']=='pna')
		{
			$sql="select COUNT(*) from diplomacia_alianzas where (id_alianza_declara=$this->id_alianza or 
			id_alianza_acepta=$this->id_alianza) and (id_alianza_declara=".Datos::idAlianza($_POST['alianza'])." or
			id_alianza_acepta=".Datos::idAlianza($_POST['alianza']).") and tipo='pna' limit 1";
			$reg=$res->fetch_array();
			if ($reg[0]>0)
			{
				header("location:alianza.php?i=$this->id_alianza&a=4&m=1");
				exit;
			}
			$sql="insert into diplomacia_alianzas values (null,'pna',$this->id_alianza,".Datos::idAlianza($_POST['alianza']).",now(),0)";
			$res=$this->mysqli->query($sql);
			header("location:alianza.php?i=$this->id_alianza&a=4");
		}
	}

	public function darCargo()
	{
		$id_usuario=$_POST['usuario'];
		$cargos=array();
		for ($i=1;$i<11;$i++)
		{
			if (isset($_POST["cargo$i"]))
			{
				$cargos[]=1;
			}
			else
			{
				$cargos[]=0;
			}
		}
		$sql="update cargos_alianzas set cargo1=$cargos[0],cargo2=$cargos[1],cargo3=$cargos[2],
			cargo4=$cargos[3],cargo5=$cargos[4],cargo6=$cargos[5],cargo7=$cargos[6],
			cargo8=$cargos[7],cargo9=$cargos[8],cargo10=$cargos[9], nombre='".$_POST['cargo']."' where id_usuario=$id_usuario;";
		$res=$this->mysqli->query($sql);
		header("location:alianza.php?i=$this->id_alianza&a=3");
	}

	public function expulsarMiembro()
	{
		$id_usuario=Datos::id($_GET['usuario']);
		$sql="delete from miembros_alianzas where id_alianza=$this->id_alianza and id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		$sql="delete from cargos_alianzas where id_alianza=$this->id_alianza and id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		header("location:edificio.php?s=".Datos::slotPorEdificio('embajada'));
	}

	public function invitar()
	{
		$id_usuario=Datos::id($_POST['usuario']);
		$sql="select COUNT(*) from miembros_alianzas where id_usuario=$id_usuario and estado=1 limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		if ($reg[0]==0)
		{
			$sql="insert into miembros_alianzas values (null,$id_usuario,$this->id_alianza,0,0)";
			$res=$this->mysqli->query($sql);
			header("location:alianza.php?i=$this->id_alianza&a=1");
		}
		else
		{
			header("location:alianza.php?i=$this->id_alianza&a=1");
		}
	}

	public function eliminarInvitacion()
	{
		$id_usuario=Datos::id($_GET['usuario']);
		$sql="delete from miembros_alianzas where id_usuario=$id_usuario";
		$res=$this->mysqli->query($sql);
		header("location:alianza.php?i=$this->id_alianza&a=1");
	}

	public function aceptarInvitacion()
	{
		$nMiembrosDisponibles=Datos::produccionEdificio('embajada',$this->id_ciudad);
		$sql="select * from miembros_alianzas where id_alianza=$this->id_alianza and estado=1";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows < $nMiembrosDisponibles)
		{
			$sql="update miembros_alianzas set estado = 1 where id_usuario=$this->id_usuario";
			$res=$this->mysqli->query($sql);
			$sql="insert into cargos_alianzas values (null,0,0,0,0,0,0,0,0,0,0,'',$this->id_usuario,$this->id_alianza)";
			$res=$this->mysqli->query($sql);
			$sql="delete from miembros_alianzas where id_usuario=$this->id_usuario and id_alianza != $this->id_alianza";
			$res=$this->mysqli->query($sql);
			header("location:edificio.php?s=".Datos::slotPorEdificio('embajada'));
		}
		else
		{
			header("location:edificio.php?s=".Datos::slotPorEdificio('embajada')."&m=1");
		}
	}

	public function rechazarInvitacion()
	{
		$sql="delete from miembros_alianzas where id_usuario=$this->id_usuario and estado=0";
		$res=$this->mysqli->query($sql);
		header("location:edificio.php?s=".Datos::slotPorEdificio('embajada'));
	}

	public function fundarAlianza()
	{
		$nombre=$_POST['nombre'];
		if (Datos::recursosSuficientes($this->id_ciudad,'500-500-500-500')==1)
		{
			$sql="update mapa set madera=madera-500,barro=barro-500,hierro=hierro-500,cereal=cereal-500
			where id_casilla=$this->id_ciudad";
			$res=$this->mysqli->query($sql);
			$sql="insert into alianzas values (null,'$nombre','En progreso...',now(),$this->id_ciudad)";
			$res=$this->mysqli->query($sql);
			$sql="select id_alianza from alianzas where id_ciudad=$this->id_ciudad";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();
			$sql="insert into miembros_alianzas values (null,$this->id_usuario,".$reg['id_alianza'].",now(),1)";
			$res=$this->mysqli->query($sql);
			$sql="insert into cargos_alianzas values (null,1,1,1,1,1,1,1,1,1,1,'Jefe',$this->id_usuario,".$reg['id_alianza'].")";
			$res=$this->mysqli->query($sql);
			header("location:edificio.php?s=".Datos::slotPorEdificio('embajada'));
		}
		else
		{
			header("location:index.php");
		}
	}
}
?>