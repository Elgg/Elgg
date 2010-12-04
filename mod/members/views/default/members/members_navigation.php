<?php

	/**
	 * A simple view to provide the user with group filters and the number of group on the site
	 **/
	 
	 $members = $vars['count'];
	 if(!$num_groups)
	 	$num_groups = 0;
	 	
	 $filter = $vars['filter'];
	 
	 //url
	 $url = elgg_get_site_url() . "mod/members/index.php";

?>
<div class="elgg-horizontal-tabbed-nav margin-top">
<ul>
	<li <?php if($filter == "newest") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=newest"><?php echo elgg_echo('members:label:newest'); ?></a></li>
	<li <?php if($filter == "pop") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=pop"><?php echo elgg_echo('members:label:popular'); ?></a></li>
	<li <?php if($filter == "active") echo "class='selected'"; ?>><a href="<?php echo $url; ?>?filter=active"><?php echo elgg_echo('members:label:active'); ?></a></li>
</ul>
</div>