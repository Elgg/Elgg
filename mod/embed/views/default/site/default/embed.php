<?php
	if ($vars['entity'] instanceof ElggSite) {	
		echo '<a href="'. $vars['entity']->getURL() .'">' . $vars['entity']->name . '</a>';	
	}
?>