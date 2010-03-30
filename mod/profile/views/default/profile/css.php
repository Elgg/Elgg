<?php
/**
 * Elgg Profile 
 * 
 * @package Profile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */
?>
/* ***************************************
	main layout blocks
*************************************** */
#profile_content {
	float:right;
	width:700px;
	position: relative;
}
#profile_sidebar {
	width:230px;
	float:left;
}
.elgg_horizontal_tabbed_nav.profile {
	margin-top:0px;
	margin-bottom:25px;
	position:relative;
}
.elgg_horizontal_tabbed_nav.profile .profile_name {
	display:block;
	width:265px;
	position:absolute;
}
.elgg_horizontal_tabbed_nav.profile .profile_name h2 {
	margin:0;
	padding:0;
	border:none;
}
.elgg_horizontal_tabbed_nav.profile ul {
	margin-left:260px;
}

/* ***************************************
	default avatar icons
*************************************** */
.usericon {
	position:relative;
}
.usericon.tiny {
	width:25px;
	height:25px;
}
.usericon.small {
	width:40px;
	height:40px;
}

/* ***************************************
	ownerblock in sidebar
*************************************** */
#profile_sidebar #owner_block {
	background-color: #eeeeee;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	padding:15px;
	min-height:270px;
}
#elgg_sidebar #owner_block {
	background-color: white;
	padding:5px;
	margin-bottom:10px;
}
#owner_block .owner_block_icon {
	float:left;
	padding:0;
	margin:0;
}
#owner_block .owner_block_icon.large {
	width:200px;
	height:200px;
	overflow: hidden;
	float:none;
}
#owner_block .owner_block_contents {
	margin-left: 50px;
}
#elgg_sidebar #owner_block .owner_block_contents {
	margin-left: 34px;
}
#owner_block .owner_block_contents h3 {
	margin-top:-4px;
	border-bottom:none;
	margin-bottom:0;
	padding-bottom:0;
}
#owner_block .owner_block_contents p.profile_info {
	margin:0;
	padding:0;
	color: #666666;
}
#owner_block .owner_block_contents p.profile_info.briefdescription {
	font-size: 90%;
	line-height:1.2em;
	font-style: italic;
}
#owner_block .owner_block_contents p.profile_info.location {
	font-size: 90%;
}
#owner_block .profile_actions {
	margin-top:10px;
}
#owner_block .profile_actions a.action_button {
	margin-bottom:4px;
	display: table;
}
/* ownerblock links to owners tools */
.owner_block_links {
	margin-top:5px;
}
.owner_block_links ul {
	margin:0;
	padding:0;
	list-style: none;
}
.owner_block_links ul li.selected a {
	background: #4690D6;
	color:white;
}
.owner_block_links ul li a {
	display:block;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background-color:white;
	margin:3px 0 5px 0;
	padding:2px 4px 2px 8px;
}
.owner_block_links ul li a:hover {
	background:#0054A7;
	color:white;
	text-decoration:none;
}


/* ***************************************
	admin menu in sidebar
*************************************** */
.owner_block_links .admin_menu_options {
	display: none;
}
.owner_block_links ul.admin_menu {
	background-color:white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	cursor:pointer;
}
.owner_block_links ul.admin_menu li a {
	background-color: red;
	color:white;
	margin-bottom:0;
}
.owner_block_links ul.admin_menu li ul.admin_menu_options li a {
	color:red;
	background-color:white;
	display:block;
	margin:0px;
	padding:2px 4px 2px 13px;
}
.owner_block_links ul.admin_menu li ul.admin_menu_options li a:hover {
	color:black;
	background:none;
	text-decoration: underline;
}


/* ***************************************
	full profile info panel
*************************************** */
#profile_content .odd {
	background-color:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	margin:0 0 7px 0;
	padding:2px 4px 2px 4px;
}
#profile_content .even {
	background-color:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	margin:0 0 7px 0;
	padding:2px 4px 2px 4px;
}
#profile_content .aboutme_title {
	background-color:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	margin:0 0 0px 0;
	padding:2px 4px 2px 4px;
}
#profile_content .aboutme_contents {
	padding:2px 0 0 3px;
}


/* ***************************************
	friends panel within profile
*************************************** */
#profile_content .entity_listing .entity_listing_info {
	width:664px;
}


/* ***************************************
	commentwall within profile
*************************************** */
#comment_wall_add textarea {
	width:685px;
}
#comment_wall_add #postit {
	float:right;
}


/* ***************************************
	twitter panel within profile
*************************************** */
ul#twitter_update_list li {
	background-image: url(<?php echo $vars['url']; ?>mod/elgg_layout/graphics/speech_bubble_tail.gif);
	background-position:right bottom;
	background-repeat: no-repeat;
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
	margin:0 0 5px 0;
	padding:0;
	overflow-x: hidden;
}
ul#twitter_update_list li span {
	color:#666666;
	background:#ececec;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	padding:3px 5px 4px 5px;
	display:block;
}
ul#twitter_update_list li a {
	display:block;
	margin:-2px 0 0 4px;
}
ul#twitter_update_list li span a {
	display:inline !important;
}
p.visit_twitter a {
    background:url(<?php echo $vars['url']; ?>mod/elgg_layout/graphics/twitter16px.png) left no-repeat;
    padding:0 0 0 20px;
    margin:0;
}
.visit_twitter {
	padding:5px 0;
	margin:0 0 0 0;
	border-top:1px solid #dedede;
}


/* ***************************************
	user avatar upload & crop page
*************************************** */
#avatar_upload {
	height:145px;
}	
#current_user_avatar {
	float:left;
	width:160px;
	height:130px;
	border-right:1px solid #cccccc;
	margin:0 20px 0 0;
}	
#avatar_croppingtool {
	border-top: 1px solid #cccccc;
	margin:20px 0 0 0;
	padding:10px 0 0 0;
}	
#user_avatar {
	float: left;
	margin-right: 20px;
}	
#user_avatar_preview {
	float: left;
	position: relative;
	overflow: hidden;
	width: 100px;
	height: 100px;
}


/* ***************************************
	banned user
*************************************** */
/* banned user full profile panel */
#profile_content .banned_user {
	border:2px solid red;
	padding:4px 8px;
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
}
/* banned user in friends lists */
.entity_listing_info p.entity_title.user.banned {
	text-decoration: line-through;
}
.entity_listing_info p.entity_title.user.banned a {
	color:red;
}


/* ***************************************
	admin area - custom profile fields
*************************************** */
.default_profile_reset {
	border-top: 1px solid #dedede;
	margin-top:30px;
}
.default_profile_reset .action_button {
	float:right;
}


/* ***************************************
	avatar drop-down menu
*************************************** */
.avatar_menu_button {
	width:15px;
	height:15px;
	position:absolute;
	cursor:pointer;
	display:none;
	right:0;
	bottom:0;
}
.avatar_menu_arrow {
	background: url(<?php echo $vars['url']; ?>_graphics/avatar_menu_arrows.gif) no-repeat left top;
	width:15px;
	height:15px;
}
.avatar_menu_arrow_on {
	background: url(<?php echo $vars['url']; ?>_graphics/avatar_menu_arrows.gif) no-repeat left -16px;
	width:15px;
	height:15px;
}
.avatar_menu_arrow_hover {
	background: url(<?php echo $vars['url']; ?>_graphics/avatar_menu_arrows.gif) no-repeat left -32px;
	width:15px;
	height:15px;
}
/* user avatar submenu options */
.usericon .sub_menu { 
	display:none; 
	position:absolute; 
	padding:0; 
	margin:0; 
	border-top:solid 1px #E5E5E5; 
	border-left:solid 1px #E5E5E5; 
	border-right:solid 1px #999999; 
	border-bottom:solid 1px #999999;  
	width:164px; 
	background:#FFFFFF; 
	text-align:left;
	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
div.usericon a.icon img {
	z-index:10;
}
.usericon .sub_menu a:link, 
.usericon .sub_menu a:visited, 
.usericon .sub_menu a:hover { 
	display:block;
}	
.usericon .sub_menu a:hover {
	background:#cccccc;
	text-decoration:none;
}
.usericon .sub_menu h3.displayname {
	font-size:1.3em !important; /* make sure nothing overrides our sub menu h3 */
	line-height: 1.1em !important;
	padding:0 !important;
	margin:0 !important;
	border-bottom:solid 1px #dddddd !important;
}
.usericon .sub_menu h3.displayname a {
	padding:3px 3px 3px 8px;
}
.usericon .sub_menu h3.displayname a .username {
	display:block;
	font-weight: normal;
	font-size: 0.8em;
}
.usericon .sub_menu a {
	padding:2px 3px 2px 8px;
}
/* admin menu options in avatar submenu */
.user_menu_admin {
	border-top:solid 1px #dddddd;
}
.usericon .sub_menu li.user_menu_admin a {
	color:red;
}
.usericon .sub_menu li.user_menu_admin a:hover {
	color:white;
	background:red;
}
