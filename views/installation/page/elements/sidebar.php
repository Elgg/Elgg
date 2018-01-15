<?php
/**
 * Install sidebar
 *
 * @uses $vars['step'] Current step
 * @uses $vars['steps'] Array of steps
 */

$current_step = elgg_extract('step', $vars);
$steps = elgg_extract('steps', $vars);
if (empty($steps)) {
	return;
}

$current_step_index = array_search($current_step, $steps);

echo '<ol>';
foreach ($steps as $index => $step) {
	if ($index < $current_step_index) {
		$class = 'past';
	} elseif ($index == $current_step_index) {
		$class = 'present';
	} else {
		$class = 'future';
	}
	$text = elgg_echo("install:$step");
	echo "<li class=\"$class\">$text</li>";
}
echo '</ol>';
