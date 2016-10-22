<?php
/**
 * Outputs object title
 * 
 * @uses $vars['title'] Title
 * @uses $vars['entity'] Entity
 */
$entity = elgg_extract('entity', $vars);
$title = elgg_extract('title', $vars);

if (!isset($title)) {
	$title = elgg_view('output/url', [
		'text' => elgg_get_excerpt($entity->getDisplayName(), 100),
		'href' => $entity->getURL(),
	]);
}

$menu = '';
if (elgg_extract('use_hover', $vars, true)) {
	$menu = elgg_view('object/elements/summary/hover', $vars);
}

if ($menu) {
	?>
	<div class="elgg-listing-summary-menu"><?= $menu ?></div>
	<?php
}

if ($title) {
	?>
	<h3 class="elgg-listing-summary-title"><?= $title ?></h3>
	<?php
}
