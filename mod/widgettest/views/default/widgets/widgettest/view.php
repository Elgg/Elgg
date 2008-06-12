<h1>I am a test widget!</h1>
<p>
	<?php

		$description = $vars['entity']->description;
		if (!empty($description)) { 
			echo $vars['entity']->description;
		} else {
			echo "Click 'edit' to change my message.";
		}
	
	?>
</p>