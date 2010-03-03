<?php

	echo elgg_view('input/tags',array('value' => $vars['categories'],
									  'internalname' => 'categories'));

?>
	<input type="submit" value="<?php echo elgg_echo('save'); ?>" />