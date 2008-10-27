<?php
	$period = $vars['entity']->period;
	if (!$period) $period = 'monthly';
		
?>
<p>
	<?php echo elgg_echo('logrotate:period'); ?>
	
	<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'params[period]',
			'options_values' => array(
				'weekly' => elgg_echo('logrotate:weekly'),
				'monthly' => elgg_echo('logrotate:monthly'),
				'yearly' => elgg_echo('logrotate:yearly'),
			),
			'value' => $period
		));
	?>
</p>
