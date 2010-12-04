<?php
/**
 * Elgg Profile 
 * 
 * @package Profile
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
.elgg-horizontal-tabbed-nav.profile {
	margin-top:0px;
	margin-bottom:25px;
	position:relative;
}
.elgg-horizontal-tabbed-nav.profile .profile_name {
	display:block;
	width:265px;
	position:absolute;
}
.elgg-horizontal-tabbed-nav.profile .profile_name h2 {
	margin:0;
	padding:0;
	border:none;
}
.elgg-horizontal-tabbed-nav.profile ul {
	margin-left:260px;
}

/* ***************************************
	default avatar icons
*************************************** */
.usericon {
	position:relative;
}
.usericon.tiny,
img.tiny {
	width:25px;
	height:25px;
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px;
	-moz-background-clip:  border;
	
	-o-background-size: 25px;
	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
}
.usericon.small,
img.small {
	width:40px;
	height:40px;
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
	-moz-background-clip:  border;
	
	-o-background-size: 40px;
	-webkit-background-size: 40px;
	-khtml-background-size: 40px;
	-moz-background-size: 40px;
}
img.large {
	width:200px;
	height:200px;
}
img.medium {
	width:100px;
	height:100px;
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
#elgg-sidebar #owner_block {
	background-color: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
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
#elgg-sidebar #owner_block .owner_block_contents {
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
#owner_block .profile_actions a.action-button {
	margin-bottom:4px;
	display: table;
}
/* ownerblock links to owners tools */
#owner_block .owners_content_links {
	border-top:1px dotted #cccccc;
	margin-top:4px;
	padding-top:2px;
}
#owner_block .owners_content_links ul {
	margin:0;
	padding:0;
}
#owner_block .owners_content_links ul li {
	display:block;
	float:left;
	width:95px;
	font-size: 90%;
}
/* profile pages - ownerblock links to owners tools */
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
	background-color: white;
	color:red;
	margin-bottom:0;
}
.owner_block_links ul.admin_menu li a:hover {
	color:black;
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
#profile_content .entity-listing .entity-listing-info {
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
ul#twitter_update_list {
	padding-left:0;
}
ul#twitter_update_list li {
	background-image: url(<?php echo elgg_get_site_url(); ?>mod/profile/graphics/speech_bubble_tail.gif);
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
    background:url(<?php echo elgg_get_site_url(); ?>mod/profile/graphics/twitter16px.png) left no-repeat;
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
.entity-listing-info p.entity-title.user.banned {
	text-decoration: line-through;
}
.entity-listing-info p.entity-title.user.banned a {
	color:red;
}


/* ***************************************
	admin area - custom profile fields
*************************************** */
.default_profile_reset {
	border-top: 1px solid #dedede;
	margin-top:30px;
}
.default_profile_reset .action-button {
	float:right;
}
/* field re-order */
#sortable_profile_fields {
	list-style: none;
	padding:0;
	margin:0;
	border-top:1px solid #cccccc;
}
#sortable_profile_fields li {
	padding:5px 0 5px 0;
	border-bottom:1px solid #cccccc;
}
#sortable_profile_fields li img.handle {
	margin-right: 7px;
	cursor: move;
}
#sortable_profile_fields .ui-sortable-helper {
	background: #eeeeee;
	color:#333333;
	padding: 5px 0 5px 0;
	margin: 0;
	width:100%;
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
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -150px top;
	width:15px;
	height:15px;
}
.avatar_menu_arrow_on {
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -150px -16px;
	width:15px;
	height:15px;
}
.avatar_menu_arrow_hover {
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -150px -32px;
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
	font-size:14px;
}
div.usericon a.icon img {
	z-index:10;
}
.usericon .sub_menu a:link, 
.usericon .sub_menu a:visited, 
.usericon .sub_menu a:hover { 
	display:block;
	font-weight: normal;
}	
.usericon .sub_menu a:hover {
	background:#cccccc;
	text-decoration:none;
}
.usericon .sub_menu .displayname {
	padding:0 !important;
	margin:0 !important;
	border-bottom:solid 1px #dddddd !important;
	font-size:14px !important;
}
.usericon .sub_menu .displayname a {
	padding:3px 3px 3px 8px;
	font-size:14px;
	font-weight: bold;
}
.usericon .sub_menu .displayname a .username {
	display:block;
	font-weight: normal;
	font-size:12px;
	text-align: left;
	margin:0;
}
.sub_menu ul.sub_menu_list {
	list-style: none;
	margin-bottom:0;
	padding-left:0;
}
.usericon .sub_menu a {
	padding:2px 3px 2px 8px;
	font-size:12px;
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
