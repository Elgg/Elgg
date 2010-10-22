<?php
/**
 * Elgg reported content CSS
 * 
 * @package reportedcontent
 */

?>
/* link in footer */
#report_this {
	text-align: left;
	float:left;
}
#report_this a {
	font-size: 90%;
	padding:0 0 4px 20px;
	background: url(<?php echo $vars['url']; ?>mod/reportedcontent/graphics/icon_reportthis.gif) no-repeat left top;
}
/* admin area */
.admin_settings.reported_content {
	margin:5px 0 0 0;
	padding:5px 7px 3px 9px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.admin_settings.reported_content p {
	margin:0;
}
.active_report {
	border:1px solid #D3322A;
	background:#F7DAD8;
}
.archived_report {
	border:1px solid #666666;
	background:#dedede;
}
.admin_settings.reported_content .controls {
	float:right;
	margin:14px 5px 0 0;
}
.admin_settings.reported_content a.action_button {
	display:inline;
	float:right;
	margin-left:15px;
}
.admin_settings.reported_content .details_link {
	cursor: pointer;
}

