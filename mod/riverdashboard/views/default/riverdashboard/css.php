<?php
/**
 * Elgg riverdashboard CSS
 * 
 */
?>
#riverdashboard_updates {
	border-bottom:1px solid #cccccc;
}
#riverdashboard_updates a.update_link {
	display: inline-table;
	color:white;
	font-weight: bold;
	padding:1px 8px 2px 24px;
	margin-top:9px;
    cursor: pointer;
	background: red url("<?php echo $vars['url']; ?>mod/riverdashboard/graphics/refresh.png") no-repeat 5px 3px;
    -webkit-border-radius: 10px; 
    -moz-border-radius: 10px;    
}
#riverdashboard_updates a.update_link:hover {
	background: #4690D6 url("<?php echo $vars['url']; ?>mod/riverdashboard/graphics/refresh.png") no-repeat 5px -22px;
	color:white;
	text-decoration: none;
}
.riverdashboard_filtermenu {
	margin:10px 0 10px 0;
	float:right;
}

/* RIVER ENTRY */
.river_item {
	border-bottom:1px solid #cccccc;
	padding:7px 0 7px 0;
}
.river_item p {
	margin:0;
}
.river_item_useravatar {
	float:left;
	margin-top:3px;
	margin-left:1px;
}
.river_item_contents {
	margin-left:55px;
}
.river_content_display {
	border-left:1px solid #DDDDDD;
	font-size:90%;
	margin:8px 0 6px 0;
	padding-left:5px;
}
.following_icon {
	width:20px;
	height:25px;
	margin:0 2px 0 2px;
	background: url(<?php echo $vars['url']; ?>mod/riverdashboard/graphics/follow_icon.png) no-repeat left -7px;
}

/* LATEST COMMENTS IN RIVER */
.river_comments {
	margin:5px 0 0 55px;
	width:auto;
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
a.river_more_comments {
	display:block;
	float:right;
	padding:2px 4px 7px 30px;
	text-align:right;
	width:auto;
}
.river_comment_owner_icon {
	float:left;
	margin:3px 8px 4px 2px;
}
.river_comment_contents {
	margin-left:34px;
}
.river_item .comment_link {
	margin-left:55px;
}
.river_comments .entity_subtext {
	display: block;
}

/*  ACTIVITY WIDGET?
	@todo 
	
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

*/


