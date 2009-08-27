<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	$subtype = get_subtype_from_id($object->subtype);
	$comment = $object->getAnnotations("generic_comment", 1, 0, "desc"); 
	foreach($comment as $c){
		$contents = $c->value;
	}
	$contents = strip_tags($contents);//this is so we don't get large images etc in the activity river
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("river:posted:generic"),$url) . " ";
	$string .= elgg_echo("{$subtype}:river:annotate") . " | <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
	$string .= "<div class=\"river_content_display\">";
	if(strlen($contents) > 200) {
        	$string .= substr($contents, 0, strpos($contents, ' ', 200)) . "...";
    }else{
	    $string .= $contents;
    }
	$string .= "</div>";
?>

<?php echo $string; ?>