<?php

	if ($vars['entity'] instanceof ElggObject) {
		
		echo '<a href="'. $vars['entity']->getURL() .'">' . $vars['entity']->title . '</a>';
		
	}

?>