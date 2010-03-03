<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$contents = strip_tags($object->description); //strip tags from the contents to stop large images etc blowing out the river view
	$string = sprintf(elgg_echo("blog:river:created"),$url) . " ";
	$string .= elgg_echo("blog:river:create") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
	$string .= "<div class=\"river_content_display\">";
	if(strlen($contents) > 200) {
        	$string .= substr($contents, 0, strpos($contents, ' ', 200)) . "...";
    }else{
	    $string .= $contents;
    }
	$string .= "</div>";
?>

<?php echo $string; ?>