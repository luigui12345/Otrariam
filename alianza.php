<?php
require_once("class/class.php");
require_once("class/alianza.php");
$ali=new Alianza();
?>
<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=0">Index</a><br />
<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=1">Invitar</a><br />
<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=2">Expulsar</a><br />
<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=3">Cargos</a><br />
<a href="alianza.php?i=<?php echo $ali->id_alianza;?>&a=4">Diplomacia</a><br />
<?php
$ali->mostrarAlianza();
?>