<?php

$opened = banner_is_cookie_set();

if ($opened) {
?>
	<div class="elgg-banner">
		<a href="javascript:void(0);" class="elgg-banner-close"><?php echo elgg_echo('close'); ?></a>
		<? echo elgg_get_plugin_setting("text", "banner"); ?>
	</div>
<?php
	}
?>



