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
		echo elgg_view('input/select', array(
			'name' => 'params[period]',
			'options_values' => array(
				'weekly' => elgg_echo('interval:weekly'),
				'monthly' => elgg_echo('interval:monthly'),
				'yearly' => elgg_echo('interval:yearly'),
			),
			'value' => $period
		));
	?>
</div>
