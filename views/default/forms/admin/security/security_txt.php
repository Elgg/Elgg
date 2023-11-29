<?php
/**
 * Form to configure the contents of the security.txt endpoint
 *
 * @see https://securitytxt.org/
 */

$help_urls = [
	'contact' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.3',
	'expires' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.5',
	'encryption' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.4',
	'acknowledgments' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.1',
	'language' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.8',
	'canonical' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.2',
	'policy' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.7',
	'hiring' => 'https://www.rfc-editor.org/rfc/rfc9116#section-2.5.6',
	'csaf' => 'https://docs.oasis-open.org/csaf/csaf/v2.0/os/csaf-v2.0-os.html#718-requirement-8-securitytxt',
];

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:contact'),
	'#help' => elgg_echo('admin:security:security_txt:contact:help', [elgg_view_url($help_urls['contact'])]),
	'name' => 'contact',
	'value' => elgg_extract('contact', $vars),
	'required' => true,
	'placeholder' => 'mailto:security@' . elgg_get_site_entity()->getDomain(),
]);

echo elgg_view_field([
	'#type' => 'date',
	'#label' => elgg_echo('admin:security:security_txt:expires'),
	'#help' => elgg_echo('admin:security:security_txt:expires:help', [elgg_view_url($help_urls['expires'])]),
	'name' => 'expires',
	'value' => elgg_extract('expires', $vars),
	'required' => true,
	'timestamp' => true,
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:encryption'),
	'#help' => elgg_echo('admin:security:security_txt:encryption:help', [elgg_view_url($help_urls['encryption'])]),
	'name' => 'encryption',
	'value' => elgg_extract('encryption', $vars),
	'placeholder' => elgg_get_site_url() . 'pgp-key.txt',
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:acknowledgments'),
	'#help' => elgg_echo('admin:security:security_txt:acknowledgments:help', [elgg_view_url($help_urls['acknowledgments'])]),
	'name' => 'acknowledgments',
	'value' => elgg_extract('acknowledgments', $vars),
	'placeholder' => elgg_get_site_url() . 'hall-of-fame.html',
]);

$languages = [
	'en',
	elgg_get_config('language'),
	elgg_get_current_language(),
];
$languages = array_unique($languages);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('admin:security:security_txt:language'),
	'#help' => elgg_echo('admin:security:security_txt:language:help', [elgg_view_url($help_urls['language'])]),
	'name' => 'language',
	'value' => elgg_extract('language', $vars),
	'placeholder' => implode(', ', $languages),
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:canonical'),
	'#help' => elgg_echo('admin:security:security_txt:canonical:help', [elgg_view_url($help_urls['canonical'])]),
	'name' => 'canonical',
	'value' => elgg_extract('canonical', $vars),
	'placeholder' => elgg_generate_url('security.txt'),
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:policy'),
	'#help' => elgg_echo('admin:security:security_txt:policy:help', [elgg_view_url($help_urls['policy'])]),
	'name' => 'policy',
	'value' => elgg_extract('policy', $vars),
	'placeholder' => elgg_get_site_url() . 'security-policy.html',
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:hiring'),
	'#help' => elgg_echo('admin:security:security_txt:hiring:help', [elgg_view_url($help_urls['hiring'])]),
	'name' => 'hiring',
	'value' => elgg_extract('hiring', $vars),
	'placeholder' => elgg_get_site_url() . 'jobs.html',
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('admin:security:security_txt:csaf'),
	'#help' => elgg_echo('admin:security:security_txt:csaf:help', [elgg_view_url($help_urls['csaf'])]),
	'name' => 'csaf',
	'value' => elgg_extract('csaf', $vars),
	'placeholder' => elgg_get_site_url() . '.well-known/csaf/provider-metadata.json',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
