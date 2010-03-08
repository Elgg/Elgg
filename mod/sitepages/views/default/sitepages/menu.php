<?php
/**
 * Elgg Site pages menu
 */
	 
//type
$type = $vars['type'];
	 
//set the url
$url = $vars['url'] . "pg/sitepages/index.php?type=";

?>

<div class="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($type == 'front') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>front"><?php echo elgg_echo('sitepages:frontpage'); ?></a></li>
	<li <?php if($type == 'about') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>about"><?php echo elgg_echo('sitepages:about'); ?></a></li>
	<li <?php if($type == 'terms') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>terms"><?php echo elgg_echo('sitepages:terms'); ?></a></li>
	<li <?php if($type == 'privacy') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>privacy"><?php echo elgg_echo('sitepages:privacy'); ?></a></li>
	<li <?php if($type == 'seo') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>seo"><?php echo elgg_echo('sitepages:seo'); ?></a></li>
</ul>
</div>