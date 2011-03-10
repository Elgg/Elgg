<?php
/**
 * @todo move into start.php using register menu item function
 */
?>
<ul class="submenu page_navigation">
<?php
	if(elgg_is_logged_in()){
		echo "<li><a href=\"".elgg_get_site_url()."groups/member/{elgg_get_logged_in_user_entity()->username}\">". elgg_echo('groups:yours') ."</a></li>";
		echo "<li><a href=\"".elgg_get_site_url()."groups/invitations/{elgg_get_logged_in_user_entity()->username}\">". elgg_echo('groups:invitations') ."</a></li>";
	}
?>
</ul>