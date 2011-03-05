<?php
/**
 * Elgg Messages CSS
 * 
 * @package ElggMessages
 */
?>


.message.unread a {
	color: #d40005;
}
.messages-buttonbank {
	text-align: right;
}
.messages-buttonbank input {
	margin-left: 10px;
}

/*** message metadata ***/
.messages-owner {
	float: left;
	width: 20%;
	margin-right: 2%;
}
.messages-subject {
	float: left;
	width: 55%;
	margin-right: 2%;
}
.messages-timestamp {
	float: left;
	width: 14%;
	margin-right: 2%;
}
.messages-delete {
	float: left;
	width: 5%;
}

/*** messages/new messages icon & counter in elgg-topbar ***/
.messages-icon {
	background:transparent url(<?php echo elgg_get_site_url(); ?>mod/messages/graphics/toolbar_messages_icon.gif) no-repeat left 2px;
	position: relative;
}
.messages-icon:hover {
	text-decoration: none;
	background-position: left -36px;
}
.messages-new {
	color: white;
	background-color: red;
	-webkit-border-radius: 10px; 
	-moz-border-radius: 10px;
	-webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50); /* safari v3+ */
	-moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50); /* FF v3.5+ */
	position: absolute;
	text-align: center;
	top: 0px;
	left: 26px;
	min-width: 16px;
	height: 16px;
	font-size: 10px;
	font-weight: bold;
}
