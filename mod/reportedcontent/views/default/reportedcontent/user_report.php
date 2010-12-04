<li class="user_menu_profile">
<?php
	echo "<a class='report_this' href=\"javascript:location.href='". elgg_get_site_url() . "pg/reportedcontent/add/?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">" . elgg_echo('reportedcontent:report') . "</a>";
?>
</li>