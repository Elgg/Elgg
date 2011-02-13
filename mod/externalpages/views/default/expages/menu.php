<?php

	/**
	 * Elgg External pages menu
	 * 
	 * @package ElggExpages
	 * 
	 */
	 
	 //type
	 $type = $vars['type'];
	 
	 //set the url
	 $url = $vars['url'] . "pg/expages/index.php?type=";

?>

<div id="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($type == 'front') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>front"><?php echo elgg_echo('expages:frontpage'); ?></a></li>
	<li <?php if($type == 'about') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>about"><?php echo elgg_echo('expages:about'); ?></a></li>
	<li <?php if($type == 'terms') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>terms"><?php echo elgg_echo('expages:terms'); ?></a></li>
	<li <?php if($type == 'privacy') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>privacy"><?php echo elgg_echo('expages:privacy'); ?></a></li>
</ul>
</div>