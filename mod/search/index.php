<?php

  /** Main search page */

global $CONFIG;

$tag = get_input('tag');
$offset = get_input('offset', 0);
$limit = get_input('limit', 10);
$viewtype = get_input('search_viewtype','list');
$searchtype = get_input('searchtype', 'all');
$object_type = get_input('object_type', '');
$subtype = get_input('subtype', '');
$owner_guid = get_input('owner_guid', '');
$tagtype = get_input('tagtype', '');
$friends = (int)get_input('friends', 0);


$title = sprintf(elgg_echo('searchtitle'), $tag); 


if (substr_count($owner_guid,',')) {
    $owner_guid_array = explode(",",$owner_guid);
} else {
    $owner_guid_array = $owner_guid;
}
if ($friends > 0) {
    if ($friends = get_user_friends($friends,'',9999)) {
	$owner_guid_array = array();
	foreach($friends as $friend) {
	    $owner_guid_array[] = $friend->guid;
	}
    } else {
	$owner_guid = -1;
    }
}

// Set up submenus
if ($object_types = get_registered_entity_types()) {
    
    foreach($object_types as $ot => $subtype_array) {
	if (is_array($subtype_array) && sizeof($subtype_array))
	    foreach($subtype_array as $object_subtype) {
		$label = 'item:' . $ot;
		if (!empty($object_subtype)) $label .= ':' . $object_subtype;
		add_submenu_item(elgg_echo($label), $CONFIG->wwwroot . "pg/search/?tag=". urlencode($tag) ."&subtype=" . $object_subtype . "&object_type=". urlencode($ot) ."&tagtype=" . urlencode($md_type) . "&owner_guid=" . urlencode($owner_guid));
	    }
    }
    add_submenu_item(elgg_echo('all'), $CONFIG->wwwroot . "pg/search/?tag=". urlencode($tag) ."&owner_guid=" . urlencode($owner_guid));
    
}

$body = '';
if (!empty($tag)) {

    // blank the results to start off
    $results = new stdClass();
    $results->entities = array();
    $results->total = 0;

    $results = trigger_plugin_hook('search:entities', '', array('tag' => $tag,
								'offset' => $offset,
								'limit' => $limit,
								'searchtype' => $searchtype,
								'object_type' => $object_type,
								'subtype' => $subtype,
								'tagtype' => $tagtype,
								'owner_guid' => $owner_guid_array
								),
				   $results);

    /*
    $searchtypes = trigger_plugin_hook('search:types', '', NULL, array());
    add_submenu_item(elgg_echo('search:type:all'),
    $CONFIG->wwwroot . "pg/search/?tag=". urlencode($tag) ."&searchtype=all");
		     
     foreach ($searchtypes as $st) {
     add_submenu_item(elgg_echo('search:type:' . $st),
     $CONFIG->wwwroot . "pg/search/?tag=". urlencode($tag) ."&searchtype=" . $st);
    }
    */
    
		
    if (empty($objecttype) && empty($subtype)) {
	$title = sprintf(elgg_echo('searchtitle'),$tag); 
    } else {
	if (empty($objecttype)) $objecttype = 'object';
	$itemtitle = 'item:' . $objecttype;
	if (!empty($subtype)) $itemtitle .= ':' . $subtype;
	$itemtitle = elgg_echo($itemtitle);
	$title = sprintf(elgg_echo('advancedsearchtitle'),$itemtitle,$tag);
    }
		



    //print_r($results);

    $body .= elgg_view_title($title); // elgg_view_title(sprintf(elgg_echo('searchtitle'),$tag));
    $body .= elgg_view('search/startblurb',array('tag' => $tag));
    

    $body .= elgg_view('search/entity_list',array('entities' => $results->entities,
						  'count' => $results->total,
						  'offset' => $offset,
						  'limit' => $limit,
						  'baseurl' => $_SERVER['REQUEST_URI'],
						  'fullview' => false,
						  'context' => 'search', 
						  'viewtypetoggle' => true,
						  'viewtype' => $viewtype,
						  'pagination' => true
						  ));




elgg_view_entity_list($results->entities, count($results->entities), 0, count($results->entities), false);
} else {

    $body .= elgg_view_title(elgg_echo('search:enterterm'));
    $body .= elgg_view('page_elements/contentwrapper', array('body' => '<div>' . elgg_view('page_elements/searchbox') . '</div>'));


}
$layout = elgg_view_layout('two_column_left_sidebar','',$body);


page_draw($title, $layout);


?>