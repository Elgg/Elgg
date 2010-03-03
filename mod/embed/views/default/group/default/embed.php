<?php

	if ($vars['entity'] instanceof ElggGroup) {
		
		echo '<a href="'. $vars['entity']->getURL() .'">' . $vars['entity']->title . '</a>';
		
	}

?>