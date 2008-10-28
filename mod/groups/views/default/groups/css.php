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
	background:#FDFFC3;
	padding:5px;
	margin:0 0 10px 0;
	border-top:2px solid #4690D6;
	color:#0054A7;
	font-size:1.35em;
	line-height:1.2em;
}

#two_column_left_sidebar_maincontent #owner_block_content {
	margin:0 0 10px 0 !important;
}

#groups_info_column_left {
	float:left;
	width:465px;
	/* margin-left:221px; */
}

#groups_info_column_left .odd {
	background:#f5f5f5;
}
#groups_info_column_left p {
	margin:0 0 7px 0;
	padding:2px 4px;
}

#groups_info_column_right {
	float:left;
	width:220px;
}
#group_stats {
	width:180px;
	background: #eeeeee;
	padding:10px;
	margin:10px 0 20px 0;
}
#group_stats p {
	margin:0;
}
#group_members {
	margin:10px;
}

.right_column {
	clear:left;
	float:right;
	width:330px;
}
#left_column {
	width:330px;
	float:left;
	margin:0 20px 0 0;

}
#group_members h2,
.right_column h2,
#left_column h2 {
	background:#F5F5F5;
	border-top:2px solid #4690D6;
	margin:0 0 5px 0;
	padding:5px;
	color:#0054A7;
	font-size:1.25em;
	line-height:1.2em;
}

#left_column #mb_input_wrapper {
	background:white;
	border:none;
	padding:0px;
}
#left_column #mb_input_wrapper .input_textarea {
	width:318px;
}

.member_icon {
	margin:3px;
	float:left;
}


/* group forums overview page */
.forums_table_head {
    background: #4690d6;
    color:#ffffff;
    padding:4px;
}
.forums_table_topics {
    padding:4px;
    border-bottom:1px solid #cccccc;
}
.forums_table_topics h3 a {
	font-size: 1.3em;
}
.forum_access {
	font-size: small;	
}
.forums_table_topics p {
	margin:0px 0px 5px 0;
}

/* topics overview page */
#topic_titles {
    background: #4690d6;
    color:#ffffff;
    padding:4px;
    margin:20px 0 0 0;
}

/* topic posts pages */
.post_icon {
    float:left;
    margin:0 8px 4px 0;
}

.topic_post {
    border-bottom:1px solid #cccccc;
    margin:10px 0 10px 0;
}

.topic_post h2 {
    margin-bottom:20px;
}

.topic_post table, td {
    border:none;
}

.topic_title {
	font-size: 1.2em;
	line-height: 1.1em;
	margin:0;
	padding:0 0 4px 0;
}

.forum_topics {
    padding:0;
    margin:0;
    border:1px solid #ddd;
    border-top:0;
}

/* alternating bckgnd on topics */
.forum_topics .odd {
	background-color:#ebebeb;
	padding: 4px;
}
.forum_topics .even {
	background-color:#f5f5f5;
	padding: 4px;
}


/* group latest discussions widget */
#latest_discussion_widget {
	margin:0 0 20px 0;
}
.forum_latest {
	margin:0 0 10px 0;
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
}

.forum_latest p.topic_replies {
	color:#999999;
    padding:3px 0 0 0;
    margin:0;
}

a.add_topic_button {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#4690d6;
	border: 2px solid #4690d6;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	/*
	width: auto;
	height: 25px;
	*/
	padding: 4px 6px 4px 6px;
	margin:0;
	cursor: pointer;
	display:table;
}
a.add_topic_button:hover {
	background: #0054a7;
	border: 2px solid #0054a7;
	text-decoration: none;
}

/* group files widget */
#filerepo_widget_layout {
	margin:0 0 20px 0;
}
/* group pages widget */
#group_pages_widget {
	margin:0 0 20px 0;
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



