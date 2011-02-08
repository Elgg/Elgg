<?php

$label = elgg_echo('bookmarks:this');
$user = elgg_get_logged_in_user_entity();
$url = "'" . $vars['url'] . "pg/bookmarks/add/{$user->username}?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)";

?>
<div id="owner_block_bookmark_this">
<a href="javascript:location.href=<?php echo $url; ?>"><?php echo $label ?></a>
</div>
