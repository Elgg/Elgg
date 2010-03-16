<?php
	if ($vars['entity'] instanceof ElggUser) {	
		echo '<a href="'. $vars['entity']->getURL() .'">' . $vars['entity']->name . '</a>';	
	}
?>