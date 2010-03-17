<?php
/**
 * Main site-wide navigation
 **/

$featured = $vars['config']->menu_items['featured_urls'];
$current_context = get_context();

echo '<div id="elgg_main_nav" class="clearfloat">
	<ul class="navigation">';

foreach ($featured as $info) {
	$selected = ($info->value->context == $current_context) ? 'class="selected"' : '';
	$title = htmlentities($info->name, ENT_QUOTES, 'UTF-8');
	$url = htmlentities($info->value->url, ENT_QUOTES, 'UTF-8');

	echo "<li $selected><a href=\"$url\" title=\"$title\"><span>$title</span></a></li>";
}

echo '
	</ul>
</div>';