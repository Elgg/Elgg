<?php

	if ($vars['count'] > $vars['threshold']) {

?>
<div class="contentWrapper"><a href="<?php echo $vars['url']; ?>search/users.php?tag=<?php echo urlencode($vars['tag']); ?>"><?php 
	
		echo elgg_echo("user:search:finishblurb"); 
	
	?></a></div>
<?php

	}

?>