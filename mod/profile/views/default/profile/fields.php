<?php
/**
 * @uses $vars['entity']       The user entity
 * @uses $vars['microformats'] Mapping of fieldnames to microformats
 * @uses $vars['fields']       Array of profile fields to show
 */

$microformats = [
	'mobile' => 'tel p-tel',
	'phone' => 'tel p-tel',
	'website' => 'url u-url',
	'contactemail' => 'email u-email',
];
$microformats = array_merge($microformats, (array) elgg_extract('microformats', $vars, []));

$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}

$fields = (array) elgg_extract('fields', $vars, []);
if (empty($fields)) {
	return;
}

// move description to the bottom of the list
if (isset($fields['description'])) {
	$temp = $fields['description'];
	unset($fields['description']);
	$fields['description'] = $temp;
}

$output = '';

foreach ($fields as $shortname => $valtype) {
	$annotations = $user->getAnnotations([
		'annotation_names' => "profile:$shortname",
		'limit' => false,
	]);
	$values = array_map(function (ElggAnnotation $a) {
		return $a->value;
	}, $annotations);

	if (!$values) {
		continue;
	}
	// emulate metadata API
	$value = (count($values) === 1) ? $values[0] : $values;

	// validate urls
	if ($valtype == 'url' && !preg_match('~^https?\://~i', $value)) {
		$value = "http://$value";
	}

	$class = elgg_extract($shortname, $microformats, '');

	$output .= elgg_view('object/elements/field', [
		'label' => elgg_echo("profile:{$shortname}"),
		'value' => elgg_format_element('span', [
			'class' => $class,
		], elgg_view("output/{$valtype}", [
			'value' => $value,
		])),
		'name' => $shortname,
	]);
}

if ($output) {
	echo elgg_format_element('div', ['class' => 'elgg-profile-fields'], $output);
}

