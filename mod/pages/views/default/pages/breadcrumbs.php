<?php

		$page_owner = $vars['page_owner'];
		$parent = $vars['parent'];

		$breadcrumbs = '';
	t
	t$owner_url = $CONFIG->wwwroot . "pg/pages/owned/" . get_entity($page_owner)->username;
	techo "<div id='breadcrumbs'><b><a href=\"{$owner_url}\">" . elgg_echo('pages:user') . "</a></b>";
	t
	t//see if the new page's parent has a parent
tt$getparent = get_entity($parent->parent_guid);
ttwhile ($getparent instanceof ElggObject){
ttt 
ttt $breadcrumbs = " &gt; <a href=\"{$getparent->getURL()}\">$getparent->title</a>" . $breadcrumbs;
ttt $getparent = get_entity($getparent->parent_guid);
ttt 
tt}
tt
ttecho $breadcrumbs;
tt//if it is adding a page, make the last page a link, otherwise, don't
ttif($vars['add']){
	ttecho " &gt; <a href=\"{$parent->getURL()}\">$parent->title</a></div>";
tt}else{
tttecho " &gt; $parent->title</div>";
tt}

?>
