<?php

$strength = $vars['strength'];

?>
<p><?php echo elgg_echo('admin:site:secret:intro'); ?></p>

<table class="elgg-table">
	<tr>
		<th><?php echo elgg_echo('site_secret:current_strength'); ?></th>
		<td class="elgg-strength-<?php echo $strength; ?>">
			<h4><?php echo elgg_echo("site_secret:strength:$strength"); ?></h4>
			<div><?php echo elgg_echo("site_secret:strength_msg:$strength"); ?></div>
		</td>
	</tr>
</table>

<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array(
			'value' => elgg_echo('admin:site:secret:regenerate'),
			'class' => 'elgg-requires-confirmation elgg-button elgg-button-submit',
		)); ?>
	<p class="elgg-text-help mts"><?php echo elgg_echo('admin:site:secret:regenerate:help'); ?></p>
</div>
