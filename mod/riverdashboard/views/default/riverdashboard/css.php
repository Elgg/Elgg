<?php

	/**
	 * Elgg riverdashboard CSS
	 * 
	 */

?>

.riverdashboard_filtermenu {
	margin:10px 0 10px 0;
	float:right;
}

.river_pagination .forward,
.river_pagination .back {
	display:block;
	float:left;
	border:1px solid #cccccc;
	color:#4690d6;
	text-align: center;
	font-size: 12px;
	font-weight: normal;
	margin:0 6px 0 0;
	padding:0 4px 1px 4px;
	cursor: pointer;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
}
.river_pagination .forward:hover,
.river_pagination .back:hover {
	background:#4690d6;
	color:white;
	text-decoration: none;
	border:1px solid #4690d6;
}
.river_pagination .back {
	margin:0 20px 0 0;
}
/* IE6 */
* html .river_pagination { margin-top:17px; }
/* IE7 */
*:first-child+html .river_pagination { margin-top:17px; }

/* activity widget */
.collapsable_box_content .content_area_user_title h2 {
	font-size:1.0em;
}
.collapsable_box_content .ContentWrapper.ActivityWidget {
	
}

.river_content img {
	margin:2px 0 2px 20px;
}

.river_content_title {
	margin-left:55px;
	
}
.river_content_title a {
	font-weight: bold;
	/* color:#333333; */
}

.river_content_display {
	border-left:1px solid #DDDDDD;
	font-size:90%;
	margin:8px 0 6px 55px;
	padding-left:5px;
}

.river_content_display p {
	padding:0;
	margin:0;
}

.following_icon {
	width:20px;
	height:25px;
	margin:0 2px 0 2px;
	background: url(<?php echo $vars['url']; ?>mod/riverdashboard/graphics/follow_icon.png) no-repeat left -7px;
}
.river_content_display div.usericon a.icon img {
	width:25px;
	height:25px;
}


/* ***************************************
	ACTIVITY RIVER
*************************************** */
.river_item_list {
	border-top:1px solid #cccccc;
}
.river_item p {
	margin:0;
}
.river_item {
	border-bottom:1px solid #cccccc;
	padding:7px 0 10px 0;
/*
	margin-top:5px;
	margin-bottom:5px;
*/
}
.river_item_annotation {

}
span.more_comments {

}
.river_more_comments {
	display:block;
	float:right;
	padding:2px 4px 7px 30px;
	text-align:right;
	width:auto;
}
.river_comments {
	margin:5px 0 0 55px;
	width:auto;
}
.comment_wrapper {
	margin-left:34px;
}
.comment_link {
	margin-left:55px;
}

.river_comment_owner_icon {
	float:left;
	margin:0 8px 4px 2px;
}

.river_comment {
	background-color: #f8f8f8;
	padding:3px;
	margin-bottom:3px;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;	
}
.river_comment.penultimate {
	background-color: #eeeeee;
}
.river_comment.latest {
	background-color: #dedede;
	margin-bottom:0;
}

.river_item_time {
	font-size:90%;
	color:#666666;
}
.river_item .river_item_useravatar {
	float:left;
	margin:3px 0 0 1px;
}
/* IE6 fix */
* html .river_item p { 
	/* padding:3px 0 3px 20px; */
}
/* IE7 */
*:first-child+html .river_item p {
	/* min-height:17px; */
}

/* .river_object_blog_update, */

.river_object_blog_create,
.river_object_page_create,
.river_object_page_update {
	float:left;
	width:26px;
	height:32px;
	border:none;
	background-color: transparent;
	margin-right:6px;
	margin-top:2px;
}
.river_object_blog_create {
	background-image: url(<?php echo $vars['url']; ?>mod/riverdashboard/graphics/icon_activity_blogs.png);
	background-position: left top;
	background-repeat: no-repeat;
}
.river_object_page_create,
.river_object_page_update {
	background-image: url(<?php echo $vars['url']; ?>mod/riverdashboard/graphics/icon_activity_pages.png);
	background-position: left top;
	background-repeat: no-repeat;
}


.collapsable_box_content .river_user_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_user_profileupdate {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_user_profileiconupdate {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_annotate {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_bookmarks_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_bookmarks.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_bookmarks_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_status_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_status.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_file_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_files.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_file_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_files.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_file_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_widget_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_plugin.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_forums_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_forums_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_widget_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_plugin.gif) no-repeat left -1px;	
}
.collapsable_box_content .river_object_blog_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_blog_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_blog_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_forumtopic_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.collapsable_box_content .river_user_friend {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_friends.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_relationship_friend_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_friends.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_relationship_member_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_thewire_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_thewire.gif) no-repeat left -1px;
}
.collapsable_box_content .river_group_join {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_groupforumtopic_annotate {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_groupforumtopic_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_sitemessage_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;	
}
.collapsable_box_content .river_user_messageboard {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;	
}
.collapsable_box_content .river_object_page_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_pages.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_page_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_pages.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_page_top_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_pages.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_page_top_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_pages.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_page_top_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.collapsable_box_content .river_object_page_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}



