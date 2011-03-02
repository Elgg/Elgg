<?php
/**
 * @todo cleanup
 */
$form_body = "";

foreach (array('sitename','sitedescription', 'siteemail') as $field) {
	$form_body .= "<div>";
	$form_body .= elgg_echo('installation:' . $field) . "<br />";
	$warning = elgg_echo('installation:warning:' . $field);
	if ($warning != 'installation:warning:' . $field) {
		echo "<b>" . $warning . "</b><br />";
	}
	$value = elgg_get_config($field);
	$form_body .= elgg_view("input/text",array('name' => $field, 'value' => $value));
	$form_body .= "</div>";
}

$languages = get_installed_translations();
$form_body .= "<div>" . elgg_echo('installation:language');
$form_body .= elgg_view("input/dropdown", array(
	'name' => 'language',
	'value' => elgg_get_config('language'),
	'options_values' => $languages,
)) . "</div>";

$form_body .= '<div class="elgg-divide-top">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo("save")));
$form_body .= '</div>';

echo $form_body;