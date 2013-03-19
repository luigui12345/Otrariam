<?php
class Mensajeria
{
	private $id_usuario;
	private $mysqli;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->usuario=$_SESSION["ju_nom"];
		$this->id_usuario=Datos::id($this->usuario);
	}

	public function mostrar_mensajes() //Muestra los mensajes
	{
		//Muestra los mensajes que no hayamos eliminado
		$sql="select * from mensajes where id_destinatario=$this->id_usuario and eliminado_destinatario='no'";
		$res=$this->mysqli->query($sql);

		?>
		<table border="0" cellspacing="0" cellpadding="0" class="tabla_reportes">

			<thead>
			<tr>
			<td>Mensaje</td>
			<td>Usuario</td>
			<td>Borrar</i></td>
			</tr>
			</thead>

			<tbody>

		<?php
		while($reg=$res->fetch_array()) //Los muestra todos 
		{
			?>

			<tr>
			<td><a href='mensaje.php?mensaje=<?php echo $reg['id_mensaje']; ?>'><?php echo $reg['asunto']; ?></a></td>
			<td><a href='perfil.php?usuario=<?php echo Datos::usuario($reg['id_emisor']); ?>'><?php echo Datos::usuario($reg['id_emisor']); ?></a></td>
			<td><a href='eliminar_mensaje.php?mensaje=<?php echo $reg['id_mensaje']; ?>' title="¿Borrar?"><i class='icon-trash'></i></a></td>
			</tr>

			<?php
		}
		?>

			<a href="redactar_mensaje.php" class='enviar_mensaje'><i class="icon-envelope"></i>Enviar un mensaje</a>

			</tbody>
		</table>

		<?php

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
		?>

		Correspondencia con: <?php echo Datos::usuario($reg['id_emisor']);?>

		<div class="leer_mensaje"><?php echo str_replace("\n", "<br />", $reg['mensaje']); ?></div>
		
		<a href="responder.php?mensaje=<?php echo $reg["id_mensaje"]; ?>" class="enviar_mensaje"><i class="icon-envelope"></i>Responder</a>
		
		<?php
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

			<form name="responder_mensaje" method="post" action="procesa_mensaje.php" class="form_enviar">

			Correspondencia con: <?php echo Datos::usuario($reg['id_emisor']); ?>

			<input type="text" name="asunto" value="<?php echo $reg['asunto'];?>" required class="input_enviar"/><br/>
			<textarea name="mensaje"  class="textarea_enviar"></textarea><br />

			<input type="hidden" value="responder" name="accion"/>
			<input type="hidden" value="<?php echo $_GET['mensaje'];?>"name="id_mensaje"/>
			<input type="submit" value="Responder" class="boton" />

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

		$sql="select COUNT(*)from mensajes where id_mensaje = $id_mensaje limit 1";
		$res=$this->mysqli->query($sql);
		$reg=$res->fetch_array();
		if ($reg[0] == 0)
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
	}
}
?>