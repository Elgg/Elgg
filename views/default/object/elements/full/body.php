<?php

/**
 * Outputs object full view
 *
 * @uses $vars['body'] Body
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
$body = elgg_extract('body', $vars);

if (!isset($body)) {
	$body = elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'elgg-listing-full-description',
	]);
}

if (!$body) {
	return;
}
?>
<div class="elgg-listing-summary-body"><?= $body ?></div>