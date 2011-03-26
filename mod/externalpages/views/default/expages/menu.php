<?php
/**
 * External pages menu
 *
 * @uses $vars['type']
 */

$type = $vars['type'];

 //set the url
 $url = $vars['url'] . "admin/site/expages?type=";
 
$pages = array('about', 'terms', 'privacy');
$tabs = array();
foreach ($pages as $page) {
	$tabs[] = array(
		'title' => elgg_echo("expages:$page"),
		'url' => "admin/appearance/expages?type=$page",
		'selected' => $page == $type,
	);
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));

return true;

/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to ul
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'title' => string, // Title of link
 * 	'url' => string, // URL for the link
 * 	'class' => string  // Class of the li element
 * 	'id' => string, // ID of the li element
 * 	'selected' => bool // if this li element is currently selected
 * 	'url_class' => string, // Class to pass to the link
 * 	'url_id' => string, // ID to pass to the link
 * )
 */

?>

<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($type == 'front') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>front"><?php echo elgg_echo('expages:frontpage'); ?></a></li>
	<li <?php if($type == 'about') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>about"><?php echo elgg_echo('expages:about'); ?></a></li>
	<li <?php if($type == 'terms') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>terms"><?php echo elgg_echo('expages:terms'); ?></a></li>
	<li <?php if($type == 'privacy') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>privacy"><?php echo elgg_echo('expages:privacy'); ?></a></li>
</ul>
</div>