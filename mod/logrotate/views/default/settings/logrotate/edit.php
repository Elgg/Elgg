<?php
/**
 * Elgg log rotator plugin settings.
 *
 * @package ElggLogRotate
 */

$period = $vars['entity']->period;
if (!$period) {
	$period = 'monthly';
}
		
?>
<p>
	<?php echo elgg_echo('logrotate:period'); ?>
	
	<?php
		echo elgg_view('input/dropdown', array(
			'name' => 'params[period]',
			'options_values' => array(
				'weekly' => elgg_echo('logrotate:weekly'),
				'monthly' => elgg_echo('logrotate:monthly'),
				'yearly' => elgg_echo('logrotate:yearly'),
			),
			'value' => $period
		));
	?>
</p>
