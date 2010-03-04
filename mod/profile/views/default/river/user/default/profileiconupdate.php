<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = "<div class=\"river_content_title\">" . sprintf(elgg_echo("profile:river:update"),$url) ."</div>";
	
	echo $string;