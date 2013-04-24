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
        			<div id="wrap_centro">
					    <?php
						    echo $html;
						?>
					</div><!--wrap_centro-->
			
	                <div id="info_aldea">
                        <?php include 'production.php'; ?>
                    </div>
                </div><!--/#wrap_aldea-->
            </div><!--/#right-->
        </div><!--/#bottom-->
    </div>
</div><!--/#wrap-->
</html>