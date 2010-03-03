<?php

$label = elgg_echo('reportedcontent:report');
$url = "'" . $vars['url'] . "mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)";

?>
<div id="owner_block_report_this">
<a href="javascript:location.href=<?php echo $url; ?>"><?php echo $label ?></a>
</div>
