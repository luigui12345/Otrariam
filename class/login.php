<?php
include('./FirePHPCore/FirePHP.class.php');
include("init.php");
include('seguridad.php');
include('datos_auxiliares.php');
include('mysqli.php');

class Login
{
	private $edificios=array();
	private $recursos=array();
	private $mysqli;
	private $firephp;

	public function __construct()
	{
		$this->mysqli=DB::Get();
		$this->firephp = FirePHP::getInstance(true);
	}
	public function login()
	{
		if (isset($_POST["nombre"]) && isset($_POST["password"])) //Si se ha enviado el formulario
		{
			$nombre = safe($_POST["nombre"]);
			$password = safe($_POST["password"]);

			$sql="select nombre from usuarios where nombre = '$nombre' and password = '$password' limit 1"; //Comprobamos el usuario y la contraseña
			$res=$this->mysqli->query($sql);

			if ($res->num_rows >0)
			{
				$reg=$res->fetch_array();
				$_SESSION["ju_nom"] = $reg["nombre"];	//Creamos la sesion
				$id_usuario=Datos::id($nombre);

				$sql="select id_casilla from mapa where id_usuario = $id_usuario and capital = 'si' limit 1";
				$res=$this->mysqli->query($sql);
				$reg=$res->fetch_array();

				$_SESSION['ju_ciudad']=$reg['id_casilla'];
				$this->firephp->log($_SESSION['ju_nom'],'Usuario');
				$this->firephp->log($_SESSION['ju_ciudad'],'id_ciudad');
				header("Location:index.php"); //Le llevamos a su ciudad
			}
			else
			{
				header("Location:login.php?m=2");
				exit;
			}

			exit;
		}
		header("Location:login.php?m=1");
	}

	public function registro()
	{
		if (isset($_POST["nombre"]) && isset($_POST["password"]) && isset($_POST["correo"])) //Comprobamos que se ha enviado el formulario
		{
			$nombre = safe($_POST["nombre"]);
			$password = safe($_POST["password"]);
			$correo = safe($_POST["correo"]);
			$tiempo = strtotime(date('Y-m-d H:i:s'));

			//Comprobamos que esta disponible el nombre y el correo
			$sql="select COUNT(*) from usuarios where nombre = '$nombre' or correo = '$correo' limit 1";
			$res=$this->mysqli->query($sql);
			$reg=$res->fetch_array();
			if ($reg[0] >0)
			{
				header("Location:login.php?m=4");
				exit;
			}

			//*******************************************************/

			$sql="insert into usuarios values (null,'$nombre','$password','$correo','',now())";
			$res=$this->mysqli->query($sql);
			$id_usuario=Datos::id($nombre);
			//********************
			$x = rand(1,10);
			$y = rand(1,10);
			$sql="select * from mapa where x = $x and y = $y and id_usuario !=0"; //Comprobamos que no esta ocupada la casilla de la ciudad
			$res=$this->mysqli->query($sql);

			while ($res->num_rows >0) //Mientras este ocupada la casilla seguimos comprobando
			{
				$x = rand(1,10);
				$y = rand(1,10);
				$sql="select * from mapa where x = $x and y = $y and id_usuario !=0";
				$res=$this->mysqli->query($sql);
			}

			//Creamos la ciudad
			$sql="update mapa set nombre = 'Pueblo de $nombre',tipo = 'Pueblo', id_usuario = $id_usuario, habitantes = 6, madera = 500,barro=500,hierro=500,cereal=500,capital = 'si', last_update = $tiempo  where x = $x and y = $y";
			$res=$this->mysqli->query($sql);
			//************************
			$id_ciudad = Datos::id_ciudad($id_usuario);
			
			//Le ponemos los edificios básicos
			$sql="insert into edificios_aldea values 
			(null,'ayuntamiento',0,'ninguno',0,2,0,$id_ciudad),
			(null,'granja',0,'cereal',5,1,0,$id_ciudad),
			(null,'leñador',0,'madera',5,1,0,$id_ciudad),
			(null,'barrera',0,'barro',5,1,0,$id_ciudad),
			(null,'mina',0,'hierro',5,1,0,$id_ciudad),
			(null,'almacen',0,'capacidad',800,2,0,$id_ciudad),
			(null,'mercado',0,'comercio',0,4,0,$id_ciudad),
			(null,'cuartel',0,'tropas',0,4,0,$id_ciudad),
			(null,'establo',0,'tropas',0,4,0,$id_ciudad),
			(null,'embajada',0,'miembros',0,1,0,$id_ciudad)";
			$res=$this->mysqli->query($sql);

			$sql="insert into tropas values (null,0,0,0,0,0,0,0,0,0,0,$id_ciudad)";
			$res=$this->mysqli->query($sql);

			header("Location:login.php?m=2");
			exit;
		}
		header("Location:registro.php?m=1");
	}
}
?>