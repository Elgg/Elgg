<?php
$context = $vars['context'];
$offset = $vars['offset'];
$entities = $vars['entities'];
$limit = $vars['limit'];
$count = $vars['count'];
$baseurl = $vars['baseurl'];
$context = $vars['context'];
$listtype = $vars['listtype'];
$pagination = $vars['pagination'];
$fullview = $vars['fullview'];

$html = "";
$nav = "";
if (isset($vars['listtypetoggle'])) {
	$listtypetoggle = $vars['listtypetoggle'];
} else {
	$listtypetoggle = true;
}

if ($context == "search" && $count > 0 && $listtypetoggle) {
	$nav .= elgg_view("navigation/listtype",array(

				  'baseurl' => $baseurl,
				  'offset' => $offset,
				  'count' => $count,
				  'listtype' => $listtype,

				  ));
}

if ($pagination)
	$nav .= elgg_view('navigation/pagination',array(

				  'baseurl' => $baseurl,
				  'offset' => $offset,
				  'count' => $count,
				  'limit' => $limit,

				  ));

if ($listtype == "list") {
	if (is_array($entities) && sizeof($entities) > 0) {
		foreach($entities as $entity) {
			// print out the entity
			$ev = elgg_view_entity($entity, $fullview);
			// then add the search decorations around it
			$html .= elgg_view('search/listing', array('entity_view' => $ev,
								   'search_types' => $entity->getVolatileData('search')));

		}
	}
} else if ($listtype == "gallery") {
	if (is_array($entities) && sizeof($entities) > 0) {
		$html .= elgg_view("search/gallery",array('entities' => $entities));
	}
}

if ($count) {
	$html .= $nav;
}
echo $html;

?>
