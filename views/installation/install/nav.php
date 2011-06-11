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
	$refresh_text = elgg_echo('Refresh');
	$refresh = "<a href=\"\">$refresh_text</a>";
}

// create next button and selectively disable
$next_text = elgg_echo('next');
$next_link = elgg_get_site_url()."install.php?step={$vars['next_step']}";
$next = "<a href=\"$next_link\">$next_text</a>";
if (isset($vars['advance']) && !$vars['advance']) {
	// disable the next button
	$next = "<a class=\"elgg-state-disabled\">$next_text</a>";
}


echo <<<___END
<div class="elgg-install-nav">
	$next
	$refresh
</div>

___END;
