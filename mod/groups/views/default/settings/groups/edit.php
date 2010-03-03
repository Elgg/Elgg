<?php
	$hidden_groups = $vars['entity']->hidden_groups;
	if (!$hidden_groups) $hidden_groups = 'no';
?>	
<p>
	<?php echo elgg_echo('groups:allowhiddengroups'); ?>
	
	<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'params[hidden_groups]',
			'options_values' => array(
				'no' => elgg_echo('option:no'),
				'yes' => elgg_echo('option:yes')
			),
			'value' => $hidden_groups
		));
	?>
</p>