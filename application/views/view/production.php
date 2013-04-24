<?php
foreach($production as $a => $b) {
	echo '<p class="resource_production">
            <img src="'. base_url("design/skin/resources/$a.png").'">'.$a.': <strong>'.$b.'/hora</strong>
         </p>';
}
?>