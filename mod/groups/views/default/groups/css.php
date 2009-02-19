<?php

	/**
	 * Elgg Groups 
	 * 
	 * @package ElggForums
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

?>

#content_area_group_title h2 {
	padding:5px;
	margin:0 0 10px 0;
	font-size:1.35em;
	line-height:1.2em;
	color:#333333;
	border-bottom:1px solid #999999;
}

#two_column_left_sidebar_maincontent #owner_block_content {
	margin:0 0 10px 0 !important;
}

#groups_info_column_left {
	float:left:
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

.right_column {
	clear:left;
	float:right;
	width:350px;
	margin:0 10px 0 0;
}
#left_column {
	width:350px;
	float:left;
	margin:0 0 0 0;

}
/* IE 6 fixes */
* html #left_column { 
	/* width:328px; */
}
* html .right_column { 
	/* width:328px; */
}

#group_members h2,
.right_column h2,
#left_column h2 {
	margin:0 0 10px 0;
	padding:5px;
	color:#0054A7;
	font-size:1.25em;
	line-height:1.2em;
}
#left_column h2 {
	padding:0px;
}

#left_column #mb_input_wrapper {
	border:none;
	padding:5px;
	margin:0 10px 10px 10px !important;
}
#left_column #mb_input_wrapper #testing.input_textarea {
	width:306px;
	/* margin:6px 0 0 0; */
}
#left_column #mb_input_wrapper #postit {
	margin:10px 0 0 0;
}
#left_column #messageboard_wrapper {
	padding:0 !important;
}
#left_column #mb_input_wrapper #messageboard_widget_menu {
	text-align: right;
}
.member_icon {
	margin:0 0 6px 6px;
	float:left;
}


/* group forums overview page */
.forums_table_head {
    background: #333333;
    color:white;
    padding:4px;
}
.forums_table_topics {
    padding:4px;
    border-bottom:1px solid #999999;
}
.forums_table_topics h3 a {
	/* font-size: 1.3em; */

}
.forums_table_topics h3 a:hover {
	/* color:white; */
}
.forum_access {
	font-size: small;	
}
.forums_table_topics p {
	margin:0px 0px 5px 0;
}
.forums_table_topics a {
	/* color:#666666; */
}
.forums_table_topics a:hover {
}
.forums_table_topics p.forum_tags a {
}


/* topics overview page */
#forum_topics {
    padding:10px;
    margin:0 10px 0 10px;
    background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;    
}
#topic_titles {
    background: #333333;
    color:white;
    padding:4px;
    margin:20px 0 0 0;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-webkit-border-top-right-radius:5px;
	-webkit-border-top-left-radius:5px;
}

/* topic posts pages */
.post_icon {
    float:left;
    margin:0 8px 4px 0;
}
#topic_posts {
	margin:0 10px 5px 10px;
}
#topic_posts form {
	margin-top: 30px;
}
.topic_post {
	padding:10px;
    margin:0 0 5px 0;
    background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;  
}

.topic_post h2 {
    margin-bottom:20px;
}
.topic_post p.topic-post-menu {
	margin:0;
}

.topic_post table, td {
    border:none;
}

.forum_topics {
    padding:0;
    margin:0;
    border-top:0;
}
.topic_title {
	font-size: 1.2em;
	line-height: 1.1em;
	margin:0;
	padding:0 0 4px 0;
}
.forum_topics p.topic_title a {
	font-weight: bold;
	color:#333333;
}

/* alternating bckgnd on topics */
.forum_topics .odd {
	background-color:#dedede;
	padding: 4px;
	border-bottom:1px solid #999999;
}
.forum_topics .even {
	background-color:#eeeeee;
	padding: 4px;
	border-bottom:1px solid #999999;
}
.forum_topics .even a,
.forum_topics .odd a {
	/* color:#666666; */
}
.forum_topics .even a:hover,
.forum_topics .odd a:hover {
	
}
.forum_topics .even p.topic_title a,
.forum_topics .odd p.topic_title a {
	
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
.right_column .filerepo_widget_singleitem {
	background: #dedede !important;
	margin:0 10px 5px 10px;
}
.right_column .search_listing {
	background: #dedede !important;
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
	font-size: 0.8em;
	line-height: 1.0em;
    padding:0;
    margin:0;
    font-weight: bold;
}
.forum_latest p.topic_replies {
    padding:3px 0 0 0;
    margin:0;
}

a.add_topic_button {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: white;
	background:#666666;
	border:none;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
	width: auto;
	height: 25px;
	padding: 3px 6px 3px 6px;
	margin:0px 0 10px 20px;
	cursor: pointer;
}
a.add_topic_button:hover {
	background: black;
	color:white;
	text-decoration: none;
}



/* latest discussion listing */
p.latest_discussion_info {
	float:right;
	width:220px;
}

span.timestamp {
	color:#666666;
	font-size: 90%;
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

/* groups sidebar */
.featuredgroups .contentWrapper {
	margin:0 0 10px 0;
}
.featuredgroups .contentWrapper .groupicon {
	float:left;
	margin:0 10px 10px 0
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


