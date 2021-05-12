<?php
/**
 * @uses $vars['entity']       The user entity
 * @uses $vars['microformats'] Mapping of fieldnames to microformats
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$fields = elgg()->fields->get('user', 'user');
if (empty($fields)) {
	return;
}

$microformats = [
	'mobile' => 'tel p-tel',
	'phone' => 'tel p-tel',
	'website' => 'url u-url',
	'contactemail' => 'email u-email',
];
$microformats = array_merge($microformats, (array) elgg_extract('microformats', $vars, []));

$output = '';
foreach ($fields as $field) {
	$shortname = $field['name'];
	$valtype = $field['#type'];
	
	$value = $user->getProfileData($shortname);
	if (elgg_is_empty($value)) {
		continue;
	}
	
	// validate urls
	if ($valtype === 'url' && is_string($value) && !preg_match('~^https?\://~i', $value)) {
		$value = "http://$value";
	}

	$class = elgg_extract($shortname, $microformats, '');

	$output .= elgg_view('object/elements/field', [
		'label' => elgg_extract('#label', $field),
		'value' => elgg_format_element('span', [
			'class' => $class,
		], elgg_view("output/{$valtype}", [
			'value' => $value,
		])),
		'name' => $shortname,
	]);
}

if (empty($output)) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-profile-fields'], $output);
