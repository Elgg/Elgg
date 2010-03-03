
<p class="user_menu_profile">
<?php
	echo "<a href=\"javascript:location.href='". $CONFIG->wwwroot . "mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">" . elgg_echo('reportedcontent:report') . "</a>";
?>
</p>