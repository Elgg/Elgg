<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$type = $entity->getType();
$subtype = $entity->getSubtype();

$views = [
	"search/$type/$subtype",
	"search/$type/default",
];

foreach ($views as $view) {
	if (elgg_view_exists($view)) {
		echo elgg_view($view, $vars);
		return;
	}
}

echo json_encode($entity->toObject());
