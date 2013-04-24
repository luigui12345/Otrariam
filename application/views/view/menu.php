<div class="circulo">
    <div class="circulo2 menu1" title="Aldea">
	    <a href="<?php echo base_url('game/village');?>"><img src="<?php echo base_url('design/skin/menu/village.png');?>"></a>
    </div>
</div>
<div class="circulo">
    <div class="circulo2 menu2" title="Ejército">
	    <a href="<?php echo base_url('game/militar');?>"><img src="<?php echo base_url('design/skin/menu/campamento.png');?>"></a>
    </div>
</div>
<div class="circulo">
    <div class="circulo2 menu3" title="Mapa">
	    <?php echo $map_link; ?>
    </div>
</div>
<div class="circulo">
    <div class="circulo2 menu4" title="Reportes">
	    <a href="reportes"><img src="<?php echo base_url('design/skin/menu/vigia.png');?>"></a>
	    <?php
	        //$reportesNoLeidos=Datos::reportesNoLeidos($_SESSION['ju_ciudad']);
	        //if ($reportesNoLeidos>0) {
		?>
		    <div id="detalle_menu" style="border-radius:2px;color:blue;font-weight:bold;margin-top:-30px;float:left;margin-left:70px;height:20px;background:grey;opacity:0.8;padding:0px 5px 0px 5px;">
		<?php //echo $reportesNoLeidos;?></div><?php
	//}
	?>  
    </div>
</div>