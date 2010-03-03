<?php

$label = elgg_echo('bookmarks:this');
$url = "'" . $vars['url'] . "mod/bookmarks/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)";

?>
<div id="owner_block_bookmark_this">
<a href="javascript:location.href=<?php echo $url; ?>"><?php echo $label ?></a>
</div>
