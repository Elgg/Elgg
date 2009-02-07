<?php

	$statement = $vars['statement'];
	
	$performed_by = $statement->getSubject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("profile:river:iconupdate"),$url);
	$string .= "<div class=\"river_content\">" . elgg_view("profile/icon",array('entity' => $performed_by, 'size' => 'small', 'override' => 'true')) . "</div>";		
	
?>

<?php echo $string; ?>