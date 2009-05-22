<?php

	if ($vars['count'] > $vars['threshold']) {

?>
<div class="contentWrapper"><a href="<?php echo $vars['url']; ?>search/groups.php?tag=<?php echo urlencode($vars['tag']); ?>"><?php 
	
		echo elgg_echo("group:search:finishblurb"); 
	
	?></a></div>
<?php

	}

?>