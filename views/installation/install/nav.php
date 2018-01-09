<?php
/**
 * Navigation for installation pages
 *
 * @uses $vars['url'] base url of site
 * @uses $vars['next_step'] next step as string
 * @uses $vars['refresh'] should refresh button be shown?
 * @uses $vars['advance'] should the next button be active?
 */


// has a refresh button been requested
$refresh = '';
if (isset($vars['refresh']) && $vars['refresh']) {
	$refresh_text = elgg_echo('install:refresh');
	$refresh = "<a href=\"\" class=\"elgg-button elgg-button-action\">$refresh_text</a>";
}

// create next button and selectively disable
$next_text = elgg_echo('install:next');
$next_link = elgg_get_site_url()."install.php?step={$vars['next_step']}";
$next = "<a href=\"$next_link\" class=\"elgg-button elgg-button-submit\">$next_text</a>";
if (isset($vars['advance']) && !$vars['advance']) {
	// disable the next button
	$next = "<a class=\"elgg-button elgg-button-submit elgg-state-disabled\">$next_text</a>";
}


echo <<<___END
<div class="elgg-install-nav">
	$refresh	
	$next
</div>

___END;
