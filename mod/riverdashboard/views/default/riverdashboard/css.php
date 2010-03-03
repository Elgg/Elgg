<?php

	/**
	 * Elgg riverdashboard CSS
	 * 
	 * @package riverdashboard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

?>
.sidebarBox #thewire_sidebarInputBox {
	width:178px;
}
.sidebarBox .last_wirepost {
	margin:20px 0 20px 0;
}
.sidebarBox .last_wirepost .thewire-singlepage {
	margin:0;
}
.sidebarBox .last_wirepost .thewire-singlepage .thewire_options {
	display:none;
}
.sidebarBox .last_wirepost .thewire-singlepage .note_date {
	line-height: 1em;
	padding:3px 0 0 0;
	width:142px;
}
.sidebarBox .last_wirepost .thewire-singlepage .note_body {
	color:#666666;
	line-height: 1.2em;
}
.sidebarBox .last_wirepost .thewire-singlepage .thewire-post {
	background-position: 130px bottom;
}
.sidebarBox .thewire_characters_remaining {
	float:right;
}
.sidebarBox input.thewire_characters_remaining_field {
	background: #dedede;
}
.sidebarBox input.thewire_characters_remaining_field:focus {
	background: #dedede;
	border:none;
}
.sidebarBox input#thewire_submit_button {
	margin:2px 0 0 0;
	padding:2px 2px 1px 2px;
	height:auto;
}
.sidebarBox .membersWrapper {
	background: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	padding:7px;	
}
.sidebarBox .membersWrapper .recentMember {
	margin:2px;
	float:left;
}
.sidebarBox .membersWrapper .recentMember .usericon img {
	width:25px;
	height:25px;
}
/* br necessary for ie6 & 7 */
.sidebarBox .membersWrapper br {
	height:0;
	line-height:0;
}
.welcomemessage {
	background:white;
}
.riverdashboard_filtermenu {
	margin:10px 0 10px 0;
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
.collapsable_box_content .river_item p {
	color:#333333;
}

.collapsable_box_content .content_area_user_title h2 {
	font-size:1.25em;
	line-height:1.2em;
	margin:0;
	padding:0 0 2px 0;
	color:#4690d6;
}
.river_content img {
	margin:2px 0 2px 20px;
}

.river_content_display {
	border-left:1px solid #ddd;
	padding:2px 10px 0 10px;
	font-size:90%;
	margin:4px 0 2px 30px;
}

.river_content_display p {
	padding:0;
	margin:0;
}

.following_icon {
	width:20px;
	height:40px;
	margin:0 2px 0 2px;
	background: url(<?php echo $vars['url']; ?>mod/riverdashboard/graphics/follow_icon.png) no-repeat left top;
}
.river_content_display div.usericon a.icon img {
	width:40px;
	height:40px;
}


