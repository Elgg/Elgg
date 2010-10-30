<?php
/**
 * Elgg notifications CSS
 * 
 * @package notifications
 */
?>

#notificationstable td.namefield {
	width:250px;
	text-align: left;
	vertical-align: middle;
}
#notificationstable td.namefield p {
	margin:0;
	vertical-align: middle;
	line-height: 1.1em;
	padding:5px 0 5px 0;
}
#notificationstable td.namefield p.namefieldlink {
	margin:5px 0 0 0;
}
#notificationstable td.namefield a img {
	float:left;
	width:25px;
	height:25px; 
	margin:5px 10px 5px 5px;
}
#notificationstable td.emailtogglefield,
#notificationstable td.smstogglefield {
	width:50px;
	text-align: center;
	vertical-align: middle;
}
#notificationstable td.spacercolumn {
	width:30px;
}
#notificationstable td {
	border-bottom: 1px solid silver;
}
#notificationstable td.emailtogglefield input {
	margin-right:36px;
	margin-top:5px;
}
#notificationstable td.emailtogglefield a {
	width:46px;
	height:24px;
	cursor: pointer;
	display: block;
	outline: none;
}
#notificationstable td.sitetogglefield {
	width:50px;
	text-align: center;
	vertical-align: middle;
}
#notificationstable td.sitetogglefield input {
	margin-right:36px;
	margin-top:5px;
}
#notificationstable td.sitetogglefield a {
	width:46px;
	height:24px;
	cursor: pointer;
	display: block;
	outline: none;
}
#notificationstable td.emailtogglefield a.emailtoggleOff {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_email.gif) no-repeat right 2px;
}
#notificationstable td.emailtogglefield a.emailtoggleOn {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_email.gif) no-repeat right -36px;
}
#notificationstable td.sitetogglefield a.sitetoggleOff {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_site.gif) no-repeat right 2px;
}
#notificationstable td.sitetogglefield a.sitetoggleOn {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_site.gif) no-repeat right -37px;
}
.notification_friends,
.notification_personal,
.notifications_per_user {
	margin-bottom: 25px;
}



