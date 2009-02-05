#notificationstable td.namefield {
	width:250px;
	text-align: left;
	vertical-align: middle;
}
#notificationstable td.namefield img {
	padding:6px 10px 6px 3px;
	float:left;
}
#notificationstable td.namefield p.namefieldlink {
	margin:9px 0 0 0;
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
#notificationstable td.emailtogglefield a.emailtoggleOff {
	background: url(<?php echo $vars['url']; ?>mod/notifications/graphics/icon_notifications_email.gif) no-repeat right 2px;
}
#notificationstable td.emailtogglefield a.emailtoggleOn {
	background: url(<?php echo $vars['url']; ?>mod/notifications/graphics/icon_notifications_email.gif) no-repeat right -36px;
}

.notification_collections,
.notification_personal {
	margin-bottom: 25px;
}