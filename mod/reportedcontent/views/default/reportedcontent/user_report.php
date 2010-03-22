<li class="user_menu_profile">
<?php
	echo "<a class='report_this' href=\"javascript:location.href='". $CONFIG->wwwroot . "mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">" . elgg_echo('reportedcontent:report') . "</a>";
?>
</li>