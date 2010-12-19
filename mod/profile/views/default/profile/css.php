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
.profile {
	float: left;
	margin-bottom: 15px;
}
.profile .elgg-inner {
	margin: 0 5px;
	border: 2px solid #eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}
#profile-sidebar {
	width:230px;
	float:left;
}
#profile-details {
	padding: 15px;
}

/* ***************************************
	ownerblock in sidebar
*************************************** */
#profile-sidebar #owner_block {
	background-color: #eeeeee;
	padding:15px;
}
#owner_block .owner_block_icon.large {
	overflow: hidden;
}
#owner_block .profile_actions {
	margin-top:10px;
}
#owner_block .profile_actions a.elgg-action-button {
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
#profile-details .odd {
	background-color:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	margin:0 0 7px 0;
	padding:2px 4px 2px 4px;
}
#profile-details .even {
	background-color:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	margin:0 0 7px 0;
	padding:2px 4px 2px 4px;
}
#profile-details .aboutme_title {
	background-color:#f4f4f4;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	margin:0 0 0px 0;
	padding:2px 4px 2px 4px;
}
#profile-details .aboutme_contents {
	padding:2px 0 0 3px;
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

