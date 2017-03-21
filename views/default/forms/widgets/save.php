<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 * @uses $vars['show_access']
 */

$widget = $vars['widget'];
$show_access = elgg_extract('show_access', $vars, true);

$edit_view = "widgets/$widget->handler/edit";
$custom_form_section = elgg_view($edit_view, ['entity' => $widget]);

$access = '';
if ($show_access) {
	$access = elgg_echo('access') . ': ' . elgg_view('input/access', [
		'name' => 'params[access_id]',
		'value' => $widget->access_id,
	]);
}

if (!$custom_form_section && !$access) {
	return true;
}

$hidden = elgg_view('input/hidden', ['name' => 'guid', 'value' => $widget->guid]);
if (elgg_in_context('default_widgets')) {
	$hidden .= elgg_view('input/hidden', ['name' => 'default_widgets', 'value' => 1]);
}
$submit = elgg_view('input/submit', ['value' => elgg_echo('save')]);

$body = <<<___END
	$custom_form_section
	<div>
		$access
	</div>
	<div class="elgg-foot">
		$hidden
		$submit
	</div>
___END;

echo $body;
