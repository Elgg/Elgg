<?php
/**
 * Reset profile fields form
 */

echo '<div class="elgg-foot">';
$params = array(
	'value' => elgg_echo('profile:resetdefault'),
	'class' => 'elgg-button-cancel',
);
echo elgg_view('input/submit', $params);
echo '</div>';
