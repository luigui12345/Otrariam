<div id="wrap">
    <div id="wrap_center">
        <div id="top">
            <div id="logo"></div>
            <div id="menu">
                <?php include 'menu.php'; ?>
            </div>
        </div>
        <div id="bottom">
            <div id="left">
                <?php include 'left.php';?>
            </div>
           <div id="right">
                <div id="resources">
                    <?php include 'resources.php'; ?>
                </div>
               <div id="wrap_aldea">
                    <div id="aldea">
                        <img src="<?php echo base_url('design/skin/village/village.png');?>" class="aldea">
                        <div id="show_levels" title="Ver niveles"><i class="icon-plus"></i></div>
                        <?php
                            echo $buildings;
                        ?>
                        <div id="time"></div>
                    </div><!--/#aldea-->
                    <div id="info_aldea">
                        <?php include 'production.php'; ?>
                    </div>
                </div><!--/#wrap_aldea-->
            </div><!--/#right-->
        </div><!--/#bottom-->
    </div>
</div><!--/#wrap-->