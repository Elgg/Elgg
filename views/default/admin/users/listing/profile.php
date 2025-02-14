<?php
/**
 * Show user profile information in admin listings
 *
 * @uses $vars['entity']       The user entity to show
 * @uses $vars['microformats'] Mapping of fieldnames to microformats
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$fields = elgg()->fields->get('user', 'user');
if (empty($fields)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('admin:users:details:profile:no_fields'),
	]);
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
	
	$value = $entity->getProfileData($shortname);
	if (elgg_is_empty($value)) {
		continue;
	}
	
	// validate urls
	if ($valtype === 'url' && is_string($value) && !preg_match('~^https?\://~i', $value)) {
		$value = "http://{$value}";
	}
	
	$output .= elgg_view('object/elements/field', [
		'label' => elgg_extract('#label', $field),
		'value' => elgg_format_element('span', [
			'class' => elgg_extract($shortname, $microformats),
		], elgg_view("output/{$valtype}", [
			'value' => $value,
		])),
		'name' => $shortname,
	]);
}

if (empty($output)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('admin:users:details:profile:no_information'),
	]);
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-profile-fields'], $output);
