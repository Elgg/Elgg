<?php

$getAnchor = function(array $options = []) {
	
	$vars = [
		'class' => elgg_extract_class($options, ['elgg-button']),
		'href' => '#',
		'text' => 'anchor',
	];
	unset($options['class']);
	
	return elgg_view('output/url', array_merge($vars, $options));
};

$getButton = function(array $options = []) {
	
	$vars = [
		'value' => elgg_echo('submit'),
	];
	
	return elgg_view('input/button', array_merge($vars, $options));
};

?>
<table class="elgg-table">
	<thead>
		<tr>
			<th>Anchor links</th>
			<th>Default</th>
			<th>Default with icons</th>
			<th>Disabled (.elgg-state-disabled)</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Base (.elgg-button)</th>
			<td><?= $getAnchor() ?></td>
			<td><?= $getAnchor(['icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-state-disabled', 'icon' => 'plus', 'icon_alt' => 'remove']) ?>
			</td>
		</tr>
		<tr>
			<th>Action (.elgg-button-action)</th>
			<td><?= $getAnchor(['class' => 'elgg-button-action']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-action', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-action elgg-state-disabled']) ?>
		</tr>
		<tr>
			<th>Cancel (.elgg-button-cancel)</th>
			<td><?= $getAnchor(['class' => 'elgg-button-cancel']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-cancel', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-cancel elgg-state-disabled']) ?>
		</tr>
		<tr>
			<th>Submit (.elgg-button-submit)</th>
			<td><?= $getAnchor(['class' => 'elgg-button-submit']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-submit', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-submit elgg-state-disabled']) ?>
		</tr>
		<tr>
			<th>Special (.elgg-button-special)</th>
			<td><?= $getAnchor(['class' => 'elgg-button-special']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-special', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-special elgg-state-disabled']) ?>
		</tr>
		<tr>
			<th>Delete (.elgg-button-delete)</th>
			<td><?= $getAnchor(['class' => 'elgg-button-delete']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-delete', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getAnchor(['class' => 'elgg-button-delete elgg-state-disabled']) ?>
		</tr>
	</tbody>
</table>

<table class="elgg-table mtl">
	<thead>
		<tr>
			<th>Input type="button"</th>
			<th>Default</th>
			<th>Default with icons</th>
			<th>Disabled (.elgg-state-disabled)</th>
			<th>Disabled [attribute disabled=true]</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Base (.elgg-button)</th>
			<td><?= $getButton() ?></td>
			<td><?= $getButton(['icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getButton(['class' => 'elgg-state-disabled', 'icon' => 'plus', 'icon_alt' => 'remove']) ?>
			<td><?= $getButton(['disabled' => true, 'icon' => 'plus', 'icon_alt' => 'remove']) ?>
		</tr>
		<tr>
			<th>Action (.elgg-button-action)</th>
			<td><?= $getButton(['class' => 'elgg-button-action']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-action', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-action elgg-state-disabled']) ?>
			<td><?= $getButton(['class' => 'elgg-button-action', 'disabled' => true]) ?>
		</tr>
		<tr>
			<th>Cancel (.elgg-button-cancel)</th>
			<td><?= $getButton(['class' => 'elgg-button-cancel']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-cancel', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-cancel elgg-state-disabled']) ?>
			<td><?= $getButton(['class' => 'elgg-button-cancel', 'disabled' => true]) ?>
		</tr>
		<tr>
			<th>Submit (.elgg-button-submit)</th>
			<td><?= $getButton(['class' => 'elgg-button-submit']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-submit', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-submit elgg-state-disabled']) ?>
			<td><?= $getButton(['class' => 'elgg-button-submit', 'disabled' => true]) ?>
		</tr>
		<tr>
			<th>Special (.elgg-button-special)</th>
			<td><?= $getButton(['class' => 'elgg-button-special']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-special', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-special elgg-state-disabled']) ?>
			<td><?= $getButton(['class' => 'elgg-button-special', 'disabled' => true]) ?>
		</tr>
		<tr>
			<th>Delete (.elgg-button-delete)</th>
			<td><?= $getButton(['class' => 'elgg-button-delete']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-delete', 'icon' => 'plus', 'icon_alt' => 'remove']) ?></td>
			<td><?= $getButton(['class' => 'elgg-button-delete elgg-state-disabled']) ?>
			<td><?= $getButton(['class' => 'elgg-button-delete', 'disabled' => true]) ?>
		</tr>
	</tbody>
</table>