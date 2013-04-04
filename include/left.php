
<ul id="enlaces_left">
	<a href="perfil.php"><li><i class="icon-user"></i>Perfil</li></a>
	<a href="mensajeria.php"><li><i class="icon-envelope"></i>Mensajería
		<?php $mensajesNoLeidos=Datos::mensajesNoLeidos(Datos::id($_SESSION['ju_nom']));
		if ($mensajesNoLeidos>0)
		{
			echo " <b>($mensajesNoLeidos)</b>";
		}
		?></li></a>
	<a href="alianza.php"><li><i class="icon-group"></i>Alianza</li></a>
	<a href="ranking.php"><li><i class="icon-trophy"></i>Ranking</li></a>
	<a href="logout.php"><li><i class="icon-signout"></i>Cerrar sesión</li></a>
</ul>