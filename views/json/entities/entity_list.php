<?php

$entities = $vars['entities'];
$full_view = $vars['full_view'];

if (is_array($entities) && sizeof($entities) > 0) {
	foreach($entities as $entity) {
		echo elgg_view_entity($entity, $full_view);
	}
}
