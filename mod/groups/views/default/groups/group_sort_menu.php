<?php
/**
 * All groups listing page navigation
 *
 * @todo should use navigation tab view
 */

$group_count = (int)elgg_get_entities(array('types' => 'group', 'count' => true));

$selected = elgg_extract('selected', $vars);
	
$url = elgg_get_site_url() . "pg/groups/all/";

?>
<div class="elgg-tabs mtm">
	<div class="right">
		<?php echo $group_count . " " . elgg_echo("groups:count"); ?>
	</div>
<ul>
	<li <?php if($selected == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('groups:newest'); ?></a></li>
	<li <?php if($selected == "pop") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=pop"><?php echo elgg_echo('groups:popular'); ?></a></li>
	<li <?php if($selected == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active"><?php echo elgg_echo('groups:latestdiscussion'); ?></a></li>
</ul>
</div>
