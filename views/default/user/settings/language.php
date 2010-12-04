<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;
$user = elgg_get_page_owner();

if ($user) {
?>
<div class="user-settings language">
<h3><?php echo elgg_echo('user:set:language'); ?></h3>
<p>

	<?php echo elgg_echo('user:language:label'); ?>: <?php

		$value = $CONFIG->language;
		if ($user->language) {
			$value = $user->language;
		}

		echo elgg_view("input/pulldown", array('internalname' => 'language', 'value' => $value, 'options_values' => get_installed_translations()));

	?>

</p>
</div>
<?php
}