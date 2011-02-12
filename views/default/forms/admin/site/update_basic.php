<?php
/**
 * @todo cleanup
 */
$form_body = "";

foreach (array('sitename','sitedescription', 'siteemail') as $field) {
	$form_body .= "<p>";
	$form_body .= elgg_echo('installation:' . $field) . "<br />";
	$warning = elgg_echo('installation:warning:' . $field);
	if ($warning != 'installation:warning:' . $field) {
		echo "<b>" . $warning . "</b><br />";
	}
	$value = elgg_get_config($field);
	$form_body .= elgg_view("input/text",array('internalname' => $field, 'value' => $value));
	$form_body .= "</p>";
}

$languages = get_installed_translations();
$form_body .= "<p>" . elgg_echo('installation:language');
$form_body .= elgg_view("input/dropdown", array(
	'internalname' => 'language',
	'value' => elgg_get_config('language'),
	'options_values' => $languages,
)) . "</p>";

$form_body .= '<p class="bta">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo("save")));
$form_body .= '</p>';
$form_body = "<div class='admin_settings site_admin'>".$form_body."</div>";

echo $form_body;