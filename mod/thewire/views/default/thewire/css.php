<?php
/**
 * The Wire CSS
 */

?>
/********************************
 The Wire
*********************************/
#thewire-textarea {
	height: 40px;
	padding: 6px;
}
#thewire-characters-remaining {
	text-align: right;
	float: right;
	font-weight: bold;
	color: #333333;
}
.thewire-characters-remaining {
	color:#333333;
	border:none;
	font-size: 100%;
	font-weight: bold;
	padding:0 2px 0 0;
	margin:0;
	text-align: right;
	background: white;
}
<?php
return true;
?>

/* new wire post form */
.new_wire_post {
	margin:10px 0 15px 0;
	padding-bottom:15px;
	border-bottom: 1px solid #dedede;
}
.new_wire_post input[type="submit"] {
	margin:3px 0 0 0;
	float:right;
}
.new_wire_post textarea {
	width: 719px;
	height: 52px;
	padding: 2px 5px 5px 5px;
	font-size: 120%;
	color:#333333;
}
.character_count {
	width: 642px;
	color:#666666;
}
.character_count input { 
	color:#666666;
	border:none;
	font-size: 100%;
	font-weight: bold;
	padding:0 2px 0 0;
	margin:0;
	text-align: right;
	background: white;
}
.character_count input:focus {
	border:none;
	background:white;
}


/* wire posts listings */
.wire_post {
	padding-bottom:10px;
	margin-bottom:5px;
	background-image: url(<?php echo elgg_get_site_url(); ?>mod/thewire/graphics/thewire_speech_bubble.gif);
	background-repeat: no-repeat;
	background-position: right bottom; 
}
.members-list .wire_post { /* when displayed in lists of friends */
	margin-top:4px;
}
.wire_post_contents {
	background-color: #eeeeee;
	margin:0;
	padding:5px;
	line-height: 1.2em;
	min-height: 34px;
	position: relative;
}
.wire_post_icon {
    float:left;
    margin-right:8px;
}
.wire_post_info {
	margin-top:-3px;
	float:left;
	width:620px;
	overflow: hidden;
}
.wire_post_options {
	float:right;
	width:65px;
}
.wire_post_options .elgg-button-action.reply.small {
	float:right;
}
.wire_post_options .elgg-button-delete {
	position: absolute;
	bottom:5px;
	right:5px;
}


/* latest wire post on profile page */
.wire_post .elgg-button-action.update.small {
	float:right;
	padding:4px;
	position: absolute;
	bottom:5px;
	right:5px;
}

/* river wire entry */
.river_item .reply_link {
	display:block;
}
