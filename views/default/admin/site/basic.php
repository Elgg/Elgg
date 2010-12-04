<?php
/**
 * Elgg administration site basic settings
 *
 * @package Elgg
 * @subpackage Core
 */

$action = elgg_get_site_url() . "action/admin/site/update_basic";

$form_body = "";

foreach(array('sitename','sitedescription', 'siteemail') as $field) {
	$form_body .= "<p>";
	$form_body .= elgg_echo('installation:' . $field) . "<br />";
	$warning = elgg_echo('installation:warning:' . $field);
	if ($warning != 'installation:warning:' . $field) echo "<b>" . $warning . "</b><br />";
	$value = $vars['config']->$field;
	$form_body .= elgg_view("input/text",array('internalname' => $field, 'value' => $value));
	$form_body .= "</p>";
}

$languages = get_installed_translations();
$form_body .= "<p>" . elgg_echo('installation:language') . elgg_view("input/pulldown", array('internalname' => 'language', 'value' => $vars['config']->language, 'options_values' => $languages)) . "</p>";

$form_body .= "<div class='divider'></div>".elgg_view('input/submit', array('value' => elgg_echo("save")));
$form_body = "<div class='admin_settings site_admin margin-top'>".$form_body."</div>";
echo elgg_view_title(elgg_echo('admin:site'));
echo elgg_view('input/form', array('action' => $action, 'body' => $form_body));
