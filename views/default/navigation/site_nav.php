<?php
/**
 * Main site-wide navigation
 *
 **/

$nav_items = elgg_get_nav_items();
$featured = $nav_items['featured'];
$more = $nav_items['more'];

$nav_html = '';
$more_nav_html = '';
$context = elgg_get_context();

// sort more links alphabetically
$more_sorted = array();
foreach ($more as $info) {
	$more_sorted[] = $info->name;
}

// required because array multisort is case sensitive
$more_sorted_lower = array_map('elgg_strtolower', $more_sorted);
array_multisort($more_sorted_lower, $more);

$item_count = 0;

// if there are no featured items, display the standard tools in alphabetical order
if ($featured) {
	foreach ($featured as $info) {
		$selected = ($info->value->context == $context) ? 'class="selected"' : '';
		$title = htmlentities($info->name, ENT_QUOTES, 'UTF-8');
		$url = htmlentities($info->value->url, ENT_QUOTES, 'UTF-8');

		$nav_html .= "<li $selected><a href=\"$url\" title=\"$title\"><span>$title</span></a></li>";
	}
} elseif ($more) {
	for ($i=0; $i<6; $i++) {
		if (!array_key_exists($i, $more)) {
			break;
		}
		$info = $more[$i];

		$selected = ($info->value->context == $context) ? 'class="selected"' : '';
		$title = htmlentities($info->name, ENT_QUOTES, 'UTF-8');
		$url = htmlentities($info->value->url, ENT_QUOTES, 'UTF-8');

		$nav_html .= "<li $selected><a href=\"$url\" title=\"$title\"><span>$title</span></a></li>";
		$more[$i]->used = TRUE;
		$item_count++;
	}
}

// display the rest.
foreach ($more as $info) {
	if ($info->used) {
		continue;
	}
	$selected = ($info->value->context == $context) ? 'class="selected"' : '';
	$title = htmlentities($info->name, ENT_QUOTES, 'UTF-8');
	$url = htmlentities($info->value->url, ENT_QUOTES, 'UTF-8');

	$more_nav_html .= "<li $selected><a href=\"$url\" title=\"$title\"><span>$title</span></a></li>\n";
	$item_count++;
}

if ($more_nav_html) {
	$more = elgg_echo('more');
	$nav_html .= "<li class='navigation-more'><a class='subnav' title=\"$more\"><span>$more</span></a>
		<ul>
			$more_nav_html
		</ul>
	</li>";
}

// only display, if there are nav items to display
if ($nav_html) {
	echo <<<___END
	<div id="elgg-main-nav" class="clearfix">
		<ul class="navigation">
			$nav_html
		</ul>
	</div>
___END;
}
?>
