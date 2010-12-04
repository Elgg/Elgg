<?php

	/**
	 * A simple view to provide the user with group filters and the number of group on the site
	 **/
	 
	 $num_groups = $vars['count'];
	 if(!$num_groups)
	 	$num_groups = 0;
	 	
	 $filter = $vars['filter'];
	 
	 //url
	 $url = elgg_get_site_url() . "pg/groups/world/";

?>
<div class="elgg-horizontal-tabbed-nav margin-top">
<div class="group_count"><?php echo $num_groups . " " . elgg_echo("groups:count"); ?></div>
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('groups:newest'); ?></a></li>
	<li <?php if($filter == "pop") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=pop"><?php echo elgg_echo('groups:popular'); ?></a></li>
	<li <?php if($filter == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active"><?php echo elgg_echo('groups:latestdiscussion'); ?></a></li>
</ul>
</div>
