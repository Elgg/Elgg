<?php

?>
<div id="elgg-content" class="clearfix">
	<div id="elgg-sidebar" class="frontpage">
		<?php 
			if (isset($vars['area2'])) echo $vars['area2']; 
			if (isset($vars['area3'])) echo $vars['area3'];	
		?>
	</div>
	
	<div id="elgg-page-contents" class="frontpage clearfix">
		<?php 
			if (isset($vars['area1'])) echo $vars['area1'];
		?>
	</div>
</div>
