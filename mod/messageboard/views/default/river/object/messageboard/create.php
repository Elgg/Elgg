<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$performed_on = get_entity($vars['item']->object_guid);

    $url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
    $string = sprintf(elgg_echo("messageboard:river:added"),$url)  . " <a href=\"{$performed_on->getURL()}\">" . $performed_on->name . "'s</a> " . elgg_echo("messageboard:river:messageboard");
	    
	
    echo $string; 

?>