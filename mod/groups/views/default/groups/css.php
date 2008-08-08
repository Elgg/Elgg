<?php

	/**
	 * Elgg Forum CSS extender
	 * 
	 * @package ElggForums
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Dave Tosh <dave@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

?>
/* forums overview page */
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


/* latest discussions on group page */
#latest_discussion_widget {
	margin:0 0 20px 0;
}
.forum_latest {
	margin:0 0 10px 0;
}
.forum_latest .topic_owner_icon {
	position:absolute;
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




