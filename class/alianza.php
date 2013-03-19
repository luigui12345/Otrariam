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
			$sql="select * from miembros_alianzas where id_usuario=$this->id_usuario and estado=1";
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
		$sql="select * from cargos_alianzas where id_alianza=$this->id_alianza and id_usuario=$this->id_usuario";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		for ($i=1;$i<11;$i++)
		{
			$misCargos[]=$reg["cargo$i"];
		}

		if (!isset($_GET['a']))
		{
			$accion='index';
			$this->mostrarIndexAlianza();
		}
		else
		{
			if ($_GET['a']==0)
			{
				$accion='index';
				$this->mostrarIndexAlianza();
			}
			else if ($_GET['a']==1 && $misCargos[0]==1)
			{
				$accion='invitar';
				$this->mostrarInvitarAlianza();
			}
			else if ($_GET['a']==2 && $misCargos[1]==1)
			{
				$accion='expulsar';
				$this->mostrarExpulsarAlianza();
			}
			else if ($_GET['a']==3 && $misCargos[2]==1)
			{
				$accion='cargos';
				$this->mostrarCargosAlianza();
			}
			else if ($_GET['a']==4 && $misCargos[3]==1)
			{
				$accion='diplomacia';
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
		$sql="select * from alianzas where id_alianza=$this->id_alianza";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();

		echo "Alianza: ".$reg['nombre']."<br />
		se fundo el ".$reg['fecha']."<br />";
		$sql="select * from miembros_alianzas where id_alianza=$this->id_alianza and estado=1";
		$res=$this->mysqli->query($sql);
		echo "Usuarios:<br /> ";
		while($red=$res->fetch_array())
		{
			echo "<a href='perfil.php?usuario=".Datos::usuario($red['id_usuario'])."'>".Datos::usuario($red['id_usuario'])."</a><br />";
		}
	}

	public function mostrarInvitarAlianza()
	{
		?>
		<form name="form_invitar" method="post" action="procesa_invitar.php">
		Invitar al usuario: <input type="text" name="usuario"/>
		<input type="hidden" name="i" value="<?php echo $this->id_alianza;?>" />
		<input type="submit" value="Invitar" />
		</form>
		<br />
		<?php
		$sql="select * from miembros_alianzas where id_alianza=$this->id_alianza and estado=0";
		$res=$this->mysqli->query($sql);
		while($reg=$res->fetch_array())
		{
			echo Datos::usuario($reg['id_usuario']). " <a href='procesa_alianza.php?a=3&i=".$_GET['i']."&usuario=".Datos::usuario($reg['id_usuario'])."'>Eliminar</a><br />";
		}

	}

	public function mostrarExpulsarAlianza()
	{

		$sql="select * from miembros_alianzas where id_alianza=$this->id_alianza and estado=1 and id_usuario!=$this->id_usuario";
		$res=$this->mysqli->query($sql);
		while($reg=$res->fetch_array())
		{
			echo Datos::usuario($reg['id_usuario']). " <a href='procesa_alianza.php?a=5&i=".$this->id_alianza."&usuario=".Datos::usuario($reg['id_usuario'])."'>Expulsar</a><br />";
		}
	}

	public function mostrarCargosAlianza()
	{
		$sql="select * from cargos_alianzas where id_alianza=$this->id_alianza";
		$res=$this->mysqli->query($sql);
		?>
		<table>
			<tr>
				<td> Miembros </td>
				<td> Invitar </td>
				<td> Expulsar</td>
				<td> Cargos </td>
				<td> Diplomacia </td>
				<td> Permiso 5</td>
				<td> Permiso 6</td>
				<td> Permiso 7</td>
				<td> Permiso 8</td>
				<td> Permiso 9</td>
				<td> Permiso 10</td>
				<td> Titulo </td>
				<td> </td>
			</tr>
			<?php
			while($reg=$res->fetch_array())
			{
				?>
				<tr>
					<form name="form_cargos" method="post" action="procesa_alianza.php?a=6">
					<td><?php echo Datos::usuario($reg['id_usuario']);?></td>
					<?php
					for ($i=1;$i<11;$i++)
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
					<td><input type="text" name="cargo" value="<?php echo $reg['nombre'];?>"/></td>
					<input type="hidden" name="usuario" value="<?php echo $reg['id_usuario'];?>" />
					<td><input type="submit" value="cambiar" /></td>
					</form>
				</tr>
				<?php
			}
		?>
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
		<hr />
		<div>
			<?php
			$this->mostrarDeclararAlianza();
			?>
		</div>
		<hr />
		<div>
			<?php
			$this->mostrarDeclararPNA();
			?>
		</div>
		<?php
	}

	public function mostrarDeclararAlianza()
	{
		?>
		<form name="form_guerra" method="post" action="procesa_alianza.php?a=7">
			Pedir una Alianza a<br /> <input type="text" name="alianza" required />
			<input type="submit" value="Declarar" />
			<input type="hidden" name="accion" value="alianza" />
			</form>
			<?php
			$sql="select * from diplomacia_alianzas where tipo='alianza' and (id_alianza_declara=$this->id_alianza or id_alianza_acepta=$this->id_alianza) and estado=1";
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
				Esta Alianza no esta aliada con nadie.
				<?php
			}
			$sql="select * from diplomacia_alianzas where tipo='alianza' and id_alianza_acepta=$this->id_alianza and estado=0";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "<br />Estas alianzas quieren Aliarse contigo:<br />";
				while ($reg=$res->fetch_array())
				{
					echo Datos::nombreAlianza($reg['id_alianza_declara']);
					echo " <a href='procesa_alianza.php?a=8&oi=".$reg['id_alianza_declara']."&i=".$this->id_alianza."&t=alianza&r=1'>Aceptar</a>";
					echo " <a href='procesa_alianza.php?a=8&oi=".$reg['id_alianza_declara']."&i=".$this->id_alianza."&t=alianza&r=0'>Rechazar</a>";
				}
			}
	}	

	public function mostrarDeclararPNA()
	{
		?>
		<form name="form_guerra" method="post" action="procesa_alianza.php?a=7">
			Pedir un pacto de no agresion (PNA)<br /> <input type="text" name="alianza" required />
			<input type="submit" value="Declarar" />
			<input type="hidden" name="accion" value="pna" />
			</form>
			<?php
			$sql="select * from diplomacia_alianzas where tipo='pna' and (id_alianza_declara=$this->id_alianza or id_alianza_acepta=$this->id_alianza) and estado=1";
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
				Esta Alianza no ha declarado un PNA con nadie.
				<?php
			}
			$sql="select * from diplomacia_alianzas where tipo='pna' and id_alianza_acepta=$this->id_alianza and estado=0";
			$res=$this->mysqli->query($sql);
			if ($res->num_rows>0)
			{
				echo "<br />Estas alianzas quieren tener un PNA contigo:<br />";
				while ($reg=$res->fetch_array())
				{
					echo Datos::nombreAlianza($reg['id_alianza_declara']);
					echo " <a href='procesa_alianza.php?a=8&oi=".$reg['id_alianza_declara']."&i=".$this->id_alianza."&t=pna&r=1'>Aceptar</a>";
					echo " <a href='procesa_alianza.php?a=8&oi=".$reg['id_alianza_declara']."&i=".$this->id_alianza."&t=pna&r=0'>Rechazar</a>";
				}
			}
	}	

	public function mostrarDeclararGuerra()
	{
		?>
		<form name="form_guerra" method="post" action="procesa_alianza.php?a=7">
			Declara la guerra una Alianza<br /> <input type="text" name="alianza" required />
			<input type="submit" value="Declarar" />
			<input type="hidden" name="accion" value="guerra" />
			</form>
			<?php
			$sql="select * from diplomacia_alianzas where tipo='guerra' and (id_alianza_declara=$this->id_alianza or id_alianza_acepta=$this->id_alianza) and estado=1";
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
				Esta Alianza no esta en guerra con nadie.
				<?php
			}	
	}	

	public function aceptarTratado()
	{
		if ($_GET['t']=='pna')
		{
			if ($_GET['r']==1)
			{
				$sql="update diplomacia_alianzas set estado=1 where id_alianza_declara=".$_GET['oi']." and id_alianza_acepta=$this->id_alianza and tipo='pna'";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?i=$this->id_alianza&a=4");
			}
			else
			{
				$sql="delete from diplomacia_alianzas where estado=0 and id_alianza_declara=".$_GET['oi']." and id_alianza_acepta=$this->id_alianza and tipo='pna'";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?i=$this->id_alianza&a=4");
			}
		}
		else if ($_GET['t']=='alianza')
		{
			if ($_GET['r']==1)
			{
				$sql="update diplomacia_alianzas set estado=1 where id_alianza_declara=".$_GET['oi']." and id_alianza_acepta=$this->id_alianza and tipo='alianza'";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?i=$this->id_alianza&a=4");
			}
			else
			{
				$sql="delete from diplomacia_alianzas where estado=0 and id_alianza_declara=".$_GET['oi']." and id_alianza_acepta=$this->id_alianza and tipo='alianza'";
				$res=$this->mysqli->query($sql);
				header("location:alianza.php?i=$this->id_alianza&a=4");
			}
		}
	}

	public function declararDiplomacia()
	{
		if ($_POST['accion']=='guerra')
		{
			$sql="insert into diplomacia_alianzas values (null,'guerra',$this->id_alianza,".Datos::idAlianza($_POST['alianza']).",now(),1)";
			$res=$this->mysqli->query($sql);
			header("location:alianza.php?i=$this->id_alianza&a=4");
		}
		if ($_POST['accion']=='alianza')
		{
			$sql="insert into diplomacia_alianzas values (null,'alianza',$this->id_alianza,".Datos::idAlianza($_POST['alianza']).",now(),0)";
			$res=$this->mysqli->query($sql);
			$sql="delete from diplomacia_alianzas where tipo='guerra' and id_alianza_declara=$this->id_alianza and id_alianza_acepta=".Datos::idAlianza($_POST['alianza']);
			$res=$this->mysqli->query($sql);
			header("location:alianza.php?i=$this->id_alianza&a=4");
		}
		if ($_POST['accion']=='pna')
		{
			$sql="insert into diplomacia_alianzas values (null,'pna',$this->id_alianza,".Datos::idAlianza($_POST['alianza']).",now(),0)";
			$res=$this->mysqli->query($sql);
			$sql="select * from diplomacia_alianzas where tipo='guerra' and id_alianza_declara=$this->id_alianza or id_alianza_acepta=".Datos::idAlianza($_POST['alianza']);
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
		$sql="select * from miembros_alianzas where id_usuario=$id_usuario and estado=1";
		$res=$this->mysqli->query($sql);
		if ($res->num_rows == 0)
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
		$sql="select * from alianzas where id_alianza=$this->id_alianza";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
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
		if (Datos::recursosSuficientes($this->id_ciudad,'1000-1000-1000-1000')==1)
		{
			$sql="update mapa set madera=madera-1000,barro=barro-1000,hierro=hierro-1000,cereal=cereal-1000
			where id_casilla=$this->id_ciudad";
			$res=$this->mysqli->query($sql);
			$sql="insert into alianzas values (null,'$nombre','En progreso...',now(),$this->id_ciudad)";
			$res=$this->mysqli->query($sql);
			$sql="select * from alianzas where id_ciudad=$this->id_ciudad";
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