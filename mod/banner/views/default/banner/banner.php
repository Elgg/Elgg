<?php

$opened = banner_get_cookie();

if ($opened) {
?>
	<div>
		<a href="javascript:void(0);" class="elgg-banner-close"><?php echo elgg_echo('close'); ?></a>
		<? echo elgg_get_plugin_setting("text", "banner"); ?>
	</div>
<?php
	}	

function banner_get_cookie() {	
	return $_COOKIE['banner']!='closed';		
}
?>



