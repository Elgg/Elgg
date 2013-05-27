<?php

$title = "Message";
global $CONFIG;

if ($vars['size']=='single') {
	$img_src = $CONFIG->url . "/mod/projects-contact/graphics/icons/projects-contact.png";
	$img = "<img src=\"$img_src\" alt=\"$title\" />";
	echo $img;
}else{
	if ($vars['size']=='full'){
		$img_src = $CONFIG->url . "/mod/projects-contact/graphics/icons/projects-contact.png";

	}else {
		$img_src = $CONFIG->url . "/mod/projects-contact/graphics/icons/projects-contactUnReaded.png";
	}

	$img = "<img src=\"$img_src\" alt=\"$title\" />";		
	$url = projects_contact_url($vars['entity']);
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
		);
	echo elgg_view('output/url', $params);	
}




