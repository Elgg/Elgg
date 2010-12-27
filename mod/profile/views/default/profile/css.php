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
#profile-owner-block {
	background-color: #eeeeee;
	padding: 15px;
}
.owner_block_icon {
	overflow: hidden;
	margin-bottom: 10px;
}
#profile-owner-block a.elgg-action-button {
	margin-bottom: 4px;
	display: table;
}
.profile-content-menu a {
	display: block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.profile-content-menu a:hover {
	background: #0054A7;
	color: white;
	text-decoration: none;
}

/* ***************************************
	admin menu in sidebar
*************************************** */
.profile-admin-menu {
	display: none;
}
.profile-admin-menu-wrapper a {
	display: block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.profile-admin-menu-wrapper {
	background-color: white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}
.profile-admin-menu-wrapper li a {
	background-color: white;
	color: red;
	margin-bottom: 0;
}
.profile-admin-menu-wrapper a:hover {
	color: black;
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

