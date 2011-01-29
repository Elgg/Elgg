<?php
/**
 * Elgg Groups css
 * 
 * @package groups
 */

?>
#group_tools_latest > .elgg-module {
	float: left;
	margin-bottom: 40px;
	min-height: 200px;
	width: 350px;
}

#group_tools_latest > .elgg-module:nth-child(odd) {
	margin-right: 30px;
}

.group-widget-viewall {
	float: right;
	font-size: 85%;
}

.groups-latest-comment {
	float: right;
}


/* group listings */
.group_count {
	float:right;
}
.group_listings {
	/* wraps group lists on 
	latest discussion, newest, popular */
}
.entity-subtext.groups {
	float:right;
	width:300px;
	text-align: right;
	margin-left: 10px;
}
.topic_post.maintopic {
	margin:10px 0 0 0;
	padding-bottom:40px;
	border-bottom:1px solid #CCCCCC;
}
.entity-listing.topic .topic_post p {
	margin:10px 0 5px 0;
}
.entity-listing.topic:hover {
	background-color: white;
}


/* group invitations */
.group_invitations a.elgg-action-button,
.group_invitations a.elgg-submit-button {
	float:right;
	margin:0 0 0 14px;
}


/* GROUPS SIDEBAR ELEMENTS */
#groupsearchform .search-input {
	width:196px;
}
.featured_group {
	margin-bottom:15px;
}
.featured_group .elgg-user-icon {
	float:left;
	margin-right:10px;
}
.featured_group p.entity-title {
	margin-bottom:0;
}
.member_icon {
	margin:6px 6px 0 0;
	float:left;
}


/* GROUP PROFILE PAGE (individual group homepage) */
.group_profile_column {
	float:left;
	margin-top:10px;
}
.group_profile_column.icon {
	width:200px;
}
.group_profile_column.info {
	width:510px;
	margin-left:20px;
}
.group_profile_icon {
	width:200px;
	height:200px;
}
.group_stats {
	background: #eeeeee;
	padding:5px;
	margin-top:10px;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
}
.group_stats p {
	margin:0;
}
.group_profile_column .odd,
.group_profile_column .even {
	background:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	padding:2px 4px;
	margin:0 0 7px;
}

/* tool content boxes on group profile page */
#group_tools_latest {
	min-height: 300px;
	margin-top:20px;
}
.group_tool_widget {
	float:left;
	margin-right:30px;
	margin-bottom:40px;
	min-height:200px;
	width:350px;
}
.group_tool_widget.odd {
	margin-right:0;
}
.group_tool_widget h3 {
	border-bottom:1px solid #CCCCCC;	
	background:#e4e4e4;
	color:#333333;
	padding:5px 5px 3px 5px;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
}

/* group activity latest
	(hide some items used on the full riverdashboard activity) 
	@todo provide a separate view for a groups latest activity
	- so we can have tiny avatars and not have to manually hide elements
*/
.group_tool_widget.activity a.river_comment_form_button,
.group_tool_widget.activity .river_comments_tabs,
.group_tool_widget.activity .river_content_display,
.group_tool_widget.activity .river-comments,
.group_tool_widget.activity .river_link_divider,
.group_tool_widget.activity .river_user-like_button {
	display:none;
}
.group_tool_widget.activity .river_item .entity-subtext {
	padding:0;
}

/* override default entity-listing-info width */
.group_tool_widget .entity-listing-info {
	width:315px;
}
.group_widget_link {
	float:right;
	margin:4px 6px 0 0;
	font-size: 85%;
}

/* edit group page */
.delete_group {
	float: right;
	margin-top:-44px;
}

/* edit forum posts
   - force tinyMCE to correct width */
.edit_comment .defaultSkin table.mceLayout {
	width: 694px !important;
}