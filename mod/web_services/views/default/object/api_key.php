<?php
/**
 * Object view for API key
 *
 * @uses $vars['entity'] the api key object
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggApiKey) {
	return;
}

// summary view
$content = '';

$description = $entity->description;
if (!empty($description)) {
	$content .= elgg_view('output/longtext', [
		'value' => $description,
	]);
}

$public = elgg_format_element('strong', ['class' => 'mrs'], elgg_echo('webservices:api_key:public'));
$public .= $entity->getPublicKey();

$secret = $entity->getSecretKey();
if (!empty($secret) && elgg_is_admin_logged_in()) {
	$public .= elgg_view('output/url', [
		'text' => elgg_echo('webservices:api_key:secret:show'),
		'href' => false,
		'data-toggle-selector' => '.webservices-secret-' . $entity->guid,
		'rel' => 'toggle',
		'class' => 'mlm'
	]);
	
	$secret = elgg_format_element('strong', ['class' => 'mrs'], elgg_echo('webservices:api_key:secret'));
	$secret .= $entity->getSecretKey();
}

$content .= elgg_format_element('div', [], $public);

if (!empty($secret)) {
	$content .= elgg_format_element('div', ['class' => ['hidden', 'webservices-secret-' . $entity->guid]], $secret);
}

// imprint
$imprint = [];

if ($entity->hasActiveKeys()) {
	$imprint[] = [
		'icon_name' => 'check',
		'content' => elgg_echo('status:enabled'),
	];
} else {
	$imprint[] = [
		'icon_name' => 'ban',
		'content' => elgg_echo('status:disabled'),
	];
}

$params = [
	'title' => $entity->getDisplayName(),
	'byline' => false,
	'access' => false,
	'content' => $content,
	'imprint' => $imprint,
];
$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
