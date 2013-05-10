<?php

$cookie_timestamp = $_COOKIE['banner'];

$timestamp = elgg_get_plugin_setting("timestamp", "banner");

if ($timestamp != $cookie_timestamp) {
?>

	<div class="elgg-banner">
		<a href="javascript:void(0);" class="elgg-banner-close" data-timestamp="<?php echo elgg_echo($timestamp); ?>"><?php echo elgg_echo('close'); ?></a>
		<? echo elgg_get_plugin_setting("text", "banner"); ?>
	</div>
<?php
	}
?>



