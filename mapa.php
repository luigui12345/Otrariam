<?php
require_once("class/class.php");
require_once("class/mapa.php");
$ald=new Aldea();
$ald->comprobar_recursos('no');
$map=new Mapa();
$ciudad=$_SESSION['ju_ciudad'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mapa</title>


<!-- Estilos CSS -->
<link rel="stylesheet" type="text/css" href="estilos/aldea.css" />
<link rel="stylesheet" type="text/css" href="estilos/mapa.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
-->

<script type="text/javascript">
    var x = <?php echo $_GET['x'];?>;
    var y = <?php echo $_GET['y'];?>;
    if (x>8)
    {
        x = 8;
    }
    if (x < 3)
    {
        x = 3;
    }
    if (y > 8)
    {
        y = 8;
    }
    if (y < 3)
    {
        y=3;
    }

    function objetoAjax()
    {
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
           xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
    }

    function mover(lado)
    {
        divLoad = document.getElementById('mapa');

        //divLoad.style.display="none";
        ajax=objetoAjax(); 
        //alert(x);
        if (lado == "derecha")
        {
            if (x < 8)
            {
                x++;
                ajax.open("GET", "mapa.php?ciudad=<?php echo $ciudad;?>&ajax=1&x="+x+"&y="+y+"");
            }
        }
        if (lado == "izquierda")
        {
            if (x > 3)
            {
                x--;
                ajax.open("GET", "mapa.php?ciudad=<?php echo $ciudad; ?>&ajax=1&x="+x+"&y="+y+"");
            }
        }
        if (lado == "arriba")
        {
            if (y > 3)
            {
                y--;
                ajax.open("GET", "mapa.php?ciudad=<?php echo $ciudad; ?>&ajax=1&x="+x+"&y="+y+"");
            }
        }
        if (lado == "abajo")
        {
            if (y < 8)
            {
                y++;
                ajax.open("GET", "mapa.php?ciudad=<?php echo $ciudad; ?>&ajax=1&x="+x+"&y="+y+"");
            }
        }

        ajax.onreadystatechange=function()
        {
                 
            if (ajax.readyState==4)                     //Cuando la peticion ajax termine
            {
                divLoad.innerHTML = ajax.responseText   //Mostrar resultados en este div
                //divLoad.style.display="block";
            }
        }
        //Como hacemos uso del metodo GET
        //Colocamos null ya que enviamos el valor por la url ?id=ide
        ajax.send(null);

    }
</script>

</head>
<body id="body">
<?php
if(!isset($_GET['ajax']))
{

?>



<div id="wrap">
<div id="wrap_center">


<div id="top">
    
    <div id="logo">
        
    </div>

    <div id="menu">
        <?php include("include/menu.php"); ?>
    </div>

</div>


<div id="bottom">

    <div id="left">
        <?php include("include/left.php");?>
    </div>

    <div id="right">

        <div id="recursos">
            <?php include("include/recursos.php"); ?>
        </div>

        <div id="wrap_aldea">
                    
            <div id="mapa">
                <?php $map->mapa();?>
            </div>

            <div id="info_mapa">
                <div id="botones_mapa"><?php $map->botones_mapa();?></div>
            </div>

        </div><!--/#wrap_aldea-->

    </div><!--/#right-->


</div><!--/#bottom-->


</div>
</div><!--/#wrap-->



<?php
}
else
{
    $map->mapa();
    $map->botones_mapa();
}
?>


</body>
<?php
$ald->comprobar_recursos('no');
?>
</html>
