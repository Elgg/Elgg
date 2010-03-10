<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = "<div class=\"river_content_title\">" . sprintf(elgg_echo("profile:river:update"),$url) ." <span class=\"river_item_time\">" . friendly_time($vars['item']->posted) . "</span></div>";
	
	echo $string;