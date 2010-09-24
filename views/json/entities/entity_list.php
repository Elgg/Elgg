<?php

$entities = $vars['entities'];
$fullview = $vars['fullview'];

if (is_array($entities) && sizeof($entities) > 0) {
	foreach($entities as $entity) {
		echo elgg_view_entity($entity, $fullview);
	}
}
