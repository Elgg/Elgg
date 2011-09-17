<?php
/**
 * Elgg garbage collector plugin settings.
 *
 * @package ElggGarbageCollector
 */

$period = $vars['entity']->period;
if (!$period) {
	$period = 'monthly';
}

?>
<div>
	<?php echo elgg_echo('garbagecollector:period'); ?>
	
	<?php
		echo elgg_view('input/dropdown', array(
			'name' => 'params[period]',
			'options_values' => array(
				'weekly' => elgg_echo('garbagecollector:weekly'),
				'monthly' => elgg_echo('garbagecollector:monthly'),
				'yearly' => elgg_echo('garbagecollector:yearly'),
			),
			'value' => $period
		));
	?>
</div>
