<?php
include("class/class.php");
include("class/mapa.php");
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

    /*$(document).ready(function()
        {
            $("#derecha").click(function()
            {
                if (x < 8)
                {
                    x++;
                    for(i=-2;i<3;i++)
                    {
                        //alert((x-3)+"|"+(y+i));
                        string="#"+(x-3)+"|"+(y+i)+"";
                         string2="#"+(x+2)+"|"+(y+i)+"";
                         //alert(string);
                        $(string).css("display","none");
                        $(string2).css("display","none");
                        //$("#z").css("display","none");
                    }
                }
            });
            $("#izquierda").click(function()
            {
                
            });
            $("#mapa_up").click(function()
            {
                
            });
            $("#mapa_bottom").click(function()
            {
                
            });
        });*/

   function mover(lado)
    {
        div = document.getElementById('mapa');
        if (lado == "derecha")
        {
            if (x < 8)
            {
                x++;
                for(i=-2;i<3;i++)
                {
                    document.getElementById((x-3)+"|"+(y+i)).style.display="none";
                    document.getElementById((x+2)+"|"+(y+i)).style.display="block";
                }
            }
        }

        if (lado == "izquierda")
        {
            if (x > 3)
            {
                x--;
                for(i=-2;i<3;i++)
                {
                    document.getElementById((x-2)+"|"+(y+i)).style.display="block";
                    document.getElementById((x+3)+"|"+(y+i)).style.display="none";
                }
            }
        }

         if (lado == "arriba")
        {
            if (y > 3)
            {
                y--;
                for(i=-2;i<3;i++)
                {
                    document.getElementById((x+i)+"|"+(y-2)).style.display="block";
                    document.getElementById((x+i)+"|"+(y+3)).style.display="none";
                }
            }
        }
        if (lado == "abajo")
        {
            if (y < 8)
            {
                y++;
                for(i=-2;i<3;i++)
                {
                    document.getElementById((x+i)+"|"+(y-3)).style.display="none";
                    document.getElementById((x+i)+"|"+(y+2)).style.display="block";
                }
            }
        }
    }

</script>

</head>
<body id="body">



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

</body>
<?php
$ald->comprobar_recursos('no');
?>
</html>
