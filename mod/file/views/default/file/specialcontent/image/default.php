<?php

	if ($vars['full'] && $smallthumb = $vars['entity']->smallthumb) {
 
		echo "<p><a href=\"".elgg_get_site_url()."mod/file/download.php?file_guid={$vars['entity']->getGUID()}\"><img src=\"".elgg_get_site_url()."mod/file/thumbnail.php?file_guid={$vars['entity']->getGUID()}&size=large\" border=\"0\" /></a></p>";
		
	}

?>