<?php
/**
 * Elgg Profile CSS
 * 
 * @package Profile
 */
?>
/* <style> /**/
/* ***************************************
	Profile
*************************************** */
.profile {
	float: left;
	margin-bottom: 15px;
}
.profile > .elgg-inner {
	border: 2px solid #eee;
	border-radius: 8px;
	margin: 0 5px;

	display: -webkit-box;
	display: -webkit-flex;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: start;
	-webkit-align-items: flex-start;
	-ms-flex-align: start;
	align-items: flex-start;
}
#profile-details {
	padding: 15px;

	-webkit-box-flex: 1;
	-webkit-flex: 1;
	-ms-flex: 1;
	flex: 1;
}
/*** ownerblock ***/
#profile-owner-block {
	width: 200px;
	background-color: #eee;
	padding: 15px;
}
#profile-owner-block .large {
	margin-bottom: 10px;
}
#profile-owner-block a.elgg-button-action {
	margin-bottom: 4px;
	display: table;
}
.profile-content-menu a {
	display: block;
	border-radius: 8px;	
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.profile-content-menu a:hover {
	background: #0054A7;
	color: white;
	text-decoration: none;
}
.profile-admin-menu {
	display: none;
}
.profile-admin-menu-wrapper a {
	display: block;
	border-radius: 8px;	
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.profile-admin-menu-wrapper {
	background-color: white;
	border-radius: 8px;
}
.profile-admin-menu-wrapper li a {
	background-color: white;
	color: red;
	margin-bottom: 0;
}
.profile-admin-menu-wrapper a:hover {
	color: black;
}
/*** profile details ***/
#profile-details .odd {
	background-color: #f4f4f4;
	border-radius: 4px;	
	margin: 0 0 7px;
	padding: 2px 4px;
}
#profile-details .even {
	background-color:#f4f4f4;
	border-radius: 4px;	
	margin: 0 0 7px;
	padding: 2px 4px;
}
.profile-aboutme-title {
	background-color:#f4f4f4;
	border-radius: 4px;	
	margin: 0;
	padding: 2px 4px;
}
.profile-aboutme-contents {
	padding: 2px 0 0 3px;
}
.profile-banned-user {
	margin: 10px 0;
	padding: 20px;
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
	border-radius: 5px;
}
.profile-banned-user h4 {
	color: #B94A48;
}
