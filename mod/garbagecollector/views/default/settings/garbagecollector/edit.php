<?php
	$period = $vars['entity']->period;
	if (!$period) $period = 'monthly';
		
?>
<p>
	<?php echo elgg_echo('garbagecollector:period'); ?>
	
	<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'params[period]',
			'options_values' => array(
				'weekly' => elgg_echo('garbagecollector:weekly'),
				'monthly' => elgg_echo('garbagecollector:monthly'),
				'yearly' => elgg_echo('garbagecollector:yearly'),
			),
			'value' => $period
		));
	?>
</p>
