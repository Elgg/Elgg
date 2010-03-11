<?php
/**
 * Elgg Groups css
 * 
 * @package groups
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

?>
/* group listings */
.group_count {
	float:right;
}
.group_listings {
	/* wraps group lists on 
	latest discussion, newest, popular */
}
.entity_subtext.groups {
	float:right;
	width:300px;
	text-align: right;
	margin-left: 10px;
}
.entity_listing.topic:hover {
	background-color: white;
}


/* group invitations */
.group_invitations a.action_button,
.group_invitations a.submit_button {
	float:right;
	margin:0 0 0 14px;
}


/* GROUPS SIDEBAR ELEMENTS */
#groupsearchform .search_input {
	width:196px;
}
.featured_group {
	margin-bottom:15px;
}
.featured_group .usericon {
	float:left;
	margin-right:10px;
}
.featured_group p.entity_title {
	margin-bottom:0;
}
.member_icon {
	margin:6px 6px 0 0;
	float:left;
}


/* GROUP PROFILE PAGE (a groups homepage) */
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
.group_profile.forum_latest {
	margin-top:20px;
}
.group_profile.forum_latest h3 {
	border-bottom:1px solid #CCCCCC;
	padding-bottom:5px;
}
.group_profile_column.right {
	float:right;
	width:350px;
	margin-top:20px;
}
.group_profile_column.left {
	width:350px;
	float:left;
	margin-top:20px;
}
/* edit group page */
.delete_group {
	float: right;
	margin-top:-44px;
}

/* Group forum */
.topic .link {
	color:#aaaaaa;
	margin-right:10px;
}
.topic .entity_metadata .delete_button {
	margin-top:3px;
}
/* all browsers - force tinyMCE on edit forum posts to be full-width */
.edit_comment .defaultSkin table.mceLayout {
	width: 694px !important;
}

