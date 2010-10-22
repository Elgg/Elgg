<?php
/**
 * Elgg Messages CSS
 * 
 * @package ElggMessages
 */
?>

/* messages/new messages icon & counter in elgg_topbar */
a.privatemessages {
	background:transparent url(<?php echo $vars['url']; ?>mod/messages/graphics/toolbar_messages_icon.gif) no-repeat left 2px;
	padding-left:16px;
	margin:4px 15px 0 5px;
	cursor:pointer;
}
a.privatemessages:hover {
	text-decoration: none;
	background:transparent url(<?php echo $vars['url']; ?>mod/messages/graphics/toolbar_messages_icon.gif) no-repeat left -36px;
}
a.privatemessages.new {
	background:transparent url(<?php echo $vars['url']; ?>mod/messages/graphics/toolbar_messages_icon.gif) no-repeat left 2px;
	padding-left:18px;
	margin:4px 15px 0 5px;
	color:white;
}
a.privatemessages.new:hover {
	text-decoration: none;
	background:transparent url(<?php echo $vars['url']; ?>mod/messages/graphics/toolbar_messages_icon.gif) no-repeat left -36px;
}
a.privatemessages.new span {
	background-color: red;
	-webkit-border-radius: 10px; 
	-moz-border-radius: 10px;
	-webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50); /* safari v3+ */
	-moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50); /* FF v3.5+ */
	color:white;
	display:block;
	float:right;
	padding:0;
	position:relative;
	text-align:center;
	top:-3px;
	right:5px;
	min-width: 16px;
	height:16px;
	font-size:10px;
	font-weight:bold;
}

/* page content */
.message {
	border-bottom:1px dotted #cccccc;
	padding:5px 0 7px 0;
}
.message.notread .entity_listing_info p.entity_title a {
	color:#d40005;
}
.message_sender {
	float:left;
	width:180px;
	overflow: hidden;
}
.messages_to {
	float: left;
	margin-right: 10px;
}

/* view and reply to message view */
.message_body {
	margin-left: 120px;
}
.message_subject {
	float:left;
	width:513px;
	padding-top:6px;
}
.message .delete_button {
	margin-top:3px;
}
.entity_listing.messages:hover {
	background-color:white;
}
.messages_buttonbank {
	margin:5px 0;
	text-align: right;
}
.messages_buttonbank input {
	margin:0 0 0 10px;
}
