<?php
/**
 * Elgg user display (details)
 *
 * @uses $vars['entity'] The user entity
 * @uses $vars['microformats'] Mapping of fieldnames to microformats
 */

$microformats = [
	'mobile' => 'tel p-tel',
	'phone' => 'tel p-tel',
	'website' => 'url u-url',
	'contactemail' => 'email u-email',
];
$microformats = array_merge($microformats, (array) elgg_extract('microformats', $vars, []));

$user = elgg_extract('entity', $vars);

$profile_fields = elgg_get_config('profile_fields');

$fields_output = '';
if (is_array($profile_fields) && sizeof($profile_fields) > 0) {
	// move description to the bottom of the list
	if (isset($profile_fields['description'])) {
		$temp = $profile_fields['description'];
		unset($profile_fields['description']);
		$profile_fields['description'] = $temp;
	}
	
	foreach ($profile_fields as $shortname => $valtype) {
		$value = $user->$shortname;

		if (!is_null($value)) {
			// fix profile URLs populated by https://github.com/Elgg/Elgg/issues/5232
			// @todo Replace with upgrade script, only need to alter users with last_update after 1.8.13
			if ($valtype == 'url' && $value == 'http://') {
				$user->$shortname = '';
				continue;
			}

			// validate urls
			if ($valtype == 'url' && !preg_match('~^https?\://~i', $value)) {
				$value = "http://$value";
			}

			$class = elgg_extract($shortname, $microformats, '');
						
			$field_title = elgg_echo("profile:{$shortname}");
			$field_value = elgg_format_element('span', [
				'class' => $class,
			], elgg_view("output/{$valtype}", [
				'value' => $value,
			]));
			
			$fields_output .= <<<___FIELD
			<div class='clearfix profile-field'>
				<div class='elgg-col elgg-col-1of5'>
					<b>{$field_title}:</b>
				</div>
				<div class='elgg-col elgg-col-4of5'>
					{$field_value}
				</div>
			</div>
___FIELD;
		}
	}
}

$result = elgg_view('profile/status', ['entity' => $user]);
$result .= $fields_output;

echo elgg_format_element('div', [
	'id' => 'profile-details',
	'class' => 'elgg-body pll',
], $result);
