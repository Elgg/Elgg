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

#content_area_group_title h2 {
	color:#0054A7;
	font-size:1.35em;
	line-height:1.2em;
	margin:0 0 0 8px;
	padding:5px;
}
#topic_posts #content_area_group_title h2 {
	margin:0 0 0 0;
}

#two_column_left_sidebar_maincontent #owner_block_content {
	margin:0 0 10px 0 !important;
}

#groups_info_column_left {
	width:435px;
	margin-left:230px;
	margin-right:10px;
}

#groups_info_column_left .odd {
	background:#E9E9E9;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
}
#groups_info_column_left .even {
	background:#E9E9E9;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
}
#groups_info_column_left p {
	margin:0 0 7px 0;
	padding:2px 4px;
}

#groups_info_column_right {
	float:left;
	width:230px;
	margin:0 0 0 10px;
}
#groups_info_wide p {
	text-align: right;
	padding-right:10px;
}
#group_stats {
	width:190px;
	background: #e9e9e9;
	padding:5px;
	margin:10px 0 20px 0;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
}
#group_stats p {
	margin:0;
}
#group_members {
	margin:10px;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background: white;
}

#right_column {
	clear:left;
	float:right;
	width:340px;
	margin:0 10px 0 0;
}
#left_column {
	width:340px;
	float:left;
	margin:0 10px 0 10px;

}
/* IE 6 fixes */
* html #left_column { 
	margin:0 0 0 5px;
}
* html #right_column { 
	margin:0 5px 0 0;
}

#group_members h2,
#right_column h2,
#left_column h2,
#fullcolumn h2 {
	margin:0 0 10px 0;
	padding:5px;
	color:#0054A7;
	font-size:1.25em;
	line-height:1.2em;
}
#fullcolumn .contentWrapper {
	margin:0 10px 20px 10px;
	padding:0 0 5px;
}

.member_icon {
	margin:0 0 6px 6px;
	float:left;
}

/* IE6 */
* html #topic_post_tbl { width:676px !important;}

/* all browsers - force tinyMCE on edit comments to be full-width */
.edit_forum_comments .defaultSkin table.mceLayout {
	width: 636px !important;
}

/* topics overview page */
#forum_topics {
    padding:10px;
    margin:0 10px 0 10px;
    background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;    
}
/* topics individual view page */
#topic_posts {
	margin:0 10px 5px 10px;
}
#topic_posts #pages_breadcrumbs {
	margin:2px 0 0 0px;
}
#topic_posts form {
    padding:10px;
    margin:30px 0 0 0;
    background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px; 
}
.topic_post {
	padding:10px;
    margin:0 0 5px 0;
    background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;  
}
.topic_post .post_icon {
    float:left;
    margin:0 8px 4px 0;
}
.topic_post h2 {
    margin-bottom:20px;
}
.topic_post p.topic-post-menu {
	margin:0;
}
.topic_post p.topic-post-menu a.collapsibleboxlink {
	padding-left:10px;
}
.topic_post table, .topic_post td {
    border:none;
}

/* group latest discussions widget */
#latest_discussion_widget {
	margin:0 0 20px 0;
	background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}
/* group files widget */
#filerepo_widget_layout {
	margin:0 0 20px 0;
	padding: 0 0 5px 0;
	background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}
/* group pages widget */
#group_pages_widget {
	margin:0 0 20px 0;
	padding: 0 0 5px 0;
	background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}
#group_pages_widget .search_listing {
	border: 2px solid #cccccc;
}
#right_column .filerepo_widget_singleitem {
	background: #dedede !important;
	margin:0 10px 5px 10px;
}
#left_column .filerepo_widget_singleitem {
	background: #dedede !important;
	margin:0 10px 5px 10px;
}
.forum_latest {
	margin:0 10px 5px 10px;
	background: #dedede;
	padding:5px;
   	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
}
.forum_latest:hover {

}
.forum_latest .topic_owner_icon {
	float:left;
}
.forum_latest .topic_title {
	margin-left:35px;
}
.forum_latest .topic_title p {
	line-height: 1.0em;
    padding:0;
    margin:0;
    font-weight: bold;
}
.forum_latest p.topic_replies {
    padding:3px 0 0 0;
    margin:0;
    color:#666666;
}
.add_topic {
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background:white;
	margin:5px 10px;
	padding:10px 10px 10px 6px;
}

a.add_topic_button {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: white;
	background:#4690d6;
	border:none;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
	width: auto;
	height: auto;
	padding: 3px 6px 3px 6px;
	margin:0;
	cursor: pointer;
}
a.add_topic_button:hover {
	background: #0054a7;
	color:white;
	text-decoration: none;
}



/* latest discussion listing */
.latest_discussion_info {
	float:right;
	width:300px;
	text-align: right;
	margin-left: 10px;
}
.groups .search_listing br {
	height:0;
	line-height:0;
}
span.timestamp {
	color:#666666;
	font-size: 90%;
}
.latest_discussion_info .timestamp {
	font-size: 0.85em;
}
/* new groups page */
.groups .search_listing {
	border:2px solid #cccccc;
	margin:0 0 5px 0;
}
.groups .search_listing:hover {
	background:#dedede;
}
.groups .group_count {
	font-weight: bold;
	color: #666666;
	margin:0 0 5px 4px;
}
.groups .search_listing_info {
	color:#666666;
}
.groupdetails {
	float:right;
}
.groupdetails p {
	margin:0;
	padding:0;
	line-height: 1.1em;
	text-align: right;
}
#groups_closed_membership {
	margin:0 10px 20px 10px;
	padding: 3px 5px 5px 5px;
	background:#bbdaf7;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;	
}
#groups_closed_membership p {
	margin:0;
}

/* groups membership widget */
.groupmembershipwidget .contentWrapper {
	margin:0 10px 5px 10px;
}
.groupmembershipwidget .contentWrapper .groupicon {
	float:left;
	margin:0 10px 0 0;
}
.groupmembershipwidget .search_listing_info p {
	color: #666666;
}
.groupmembershipwidget .search_listing_info span {
	font-weight: bold;
}

/* groups sidebar */
.featuredgroups .contentWrapper {
	margin:0 0 10px 0;
}
.featuredgroups .contentWrapper .groupicon {
	float:left;
	margin:0 10px 0 0;
}
.featuredgroups .contentWrapper p {
	margin: 0;
	line-height: 1.2em;
	color:#666666;
}
.featuredgroups .contentWrapper span {
	font-weight: bold;
}
#groupssearchform {
	border-bottom: 1px solid #cccccc;
	margin-bottom: 10px;
}
#groupssearchform input[type="submit"] {
	padding:2px;
	height:auto;
	margin:4px 0 5px 0;
}
.sidebarBox #owner_block_submenu {
	margin:5px 0 0 0;
}

/* delete post */
.delete_discussion {
	
}
.delete_discussion a {
	display:block;
	float:right;
	cursor: pointer;
	width:14px;
	height:14px;
	margin:0;
	background: url("<?php echo $vars['url']; ?>_graphics/icon_customise_remove.png") no-repeat 0 0;
}
.delete_discussion a:hover {
	background-position: 0 -16px;
	text-decoration: none;
}
/* IE6 */
* html .delete_discussion a { font-size: 1px; }
/* IE7 */
*:first-child+html .delete_discussion a { font-size: 1px; }

/* delete group button */
#delete_group_option input[type="submit"] {
	background:#dedede;
	border-color:#dedede;
	color:#333333; 
	margin:0;
	float:right;
	clear:both;
}
#delete_group_option input[type="submit"]:hover {
	background:red;
	border-color:red;
	color:white;
}

#groupsearchform .search_input {
	width:176px;
}

