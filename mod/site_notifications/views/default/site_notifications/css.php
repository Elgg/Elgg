<?php
/**
 * Site notifications CSS
 */
?>

#notificationstable td.sitetogglefield a.sitetoggleOff {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_site.gif) no-repeat right 2px;
}
#notificationstable td.sitetogglefield a.sitetoggleOn {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_site.gif) no-repeat right -37px;
}
.site-notifications-buttonbank {
	text-align: right;
}
.site-notifications-buttonbank input {
	margin-left: 10px;
}