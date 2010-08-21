<?php

	$performed_by = get_entity($vars['item']->subject_guid);
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	$title = $object->title;
	if(!$title)
		$title = elgg_echo('file:untitled');
	$subtype = get_subtype_from_id($object->subtype);
	//grab the annotation, if one exists
	if($vars['item']->annotation_id != 0)
		$comment = get_annotation($vars['item']->annotation_id)->value; 
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("river:posted:generic"),$url) . " ";
	$string .= elgg_echo("{$subtype}:river:annotate") . "  <a href=\"{$object->getURL()}\">" . $title . "</a> <span class='entity_subtext'>". elgg_view_friendly_time($object->time_created) ."<a class='river_comment_form_button link'>Comment</a>";
	$string .= elgg_view('likes/forms/link', array('entity' => $object));
	$string .= "</span>";
	if(get_context() != 'riverdashboard'){
		$string .= "<div class='river_content_display'>";
		if($comment){
			$contents = strip_tags($comment);//this is so we don't get large images etc in the activity river
			if(strlen($contents) > 200)
	        		$string .= substr($contents, 0, strpos($contents, ' ', 200)) . "&hellip;";
	    		else
		    		$string .= $contents;
       	}
		$string .= "</div>";
	}
echo $string;