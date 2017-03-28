<?php

$label = elgg_extract('label', $vars);
$value = elgg_extract('value', $vars);
if (!$value) {
	return;
}

if ($label) {
	$label = elgg_format_element('div', [
		'class' => 'profile-field-label',
	], $label);
}

$value = elgg_format_element('div', [
	'class' => 'profile-field-value',
], $value);
?>
<div class='clearfix profile-field list-group-item'>
	<?= $label ?>
	<?= $value ?>
</div>