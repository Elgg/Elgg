<?php
/**
 *
 */

?>

/* ***************************************
	LIKES
*************************************** */
.elgg-likes-list {
	width: 345px;
	position: absolute;
}

/* ***************************************
	RIVER
*************************************** */
.elgg-river {
	border-top: 1px solid #CCCCCC;
}
.elgg-river > li {
	border-bottom: 1px solid #CCCCCC;
}
.elgg-river-item {
	padding: 7px 0;
}
.elgg-river-item .elgg-pict {
	margin-right: 20px;
}
.elgg-river-timestamp {
    color: #666666;
    font-size: 85%;
    font-style: italic;
    line-height: 1.2em;
}
.elgg-river-content {
	border-left: 1px solid #CCCCCC;
	font-size: 85%;
	line-height: 1.5em;
	margin: 8px 0 5px 0;
	padding-left: 5px;
}
.elgg-river-content .elgg-user-icon {
	float: left;
}
.elgg-river-layout .elgg-input-dropdown {
	float: right;
	margin: 10px 0;
}

.elgg-river-comments-tab {
	display: block;
	background-color: #EEEEEE;
	color: #4690D6;
	margin-top: 5px;
	width: auto;
	float: right;
	font-size: 85%;
	padding: 1px 7px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
}
.elgg-river-comments {
	margin: 0;
	border-top: none;
}
.elgg-river-comments li:first-child {
	-moz-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
}
.elgg-river-comments li:last-child {
	-moz-border-radius-bottomleft: 5px;
	-moz-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.elgg-river-comments li {
	background-color: #EEEEEE;
	border-bottom: none;
	padding: 4px;
	margin-bottom: 2px;
}
.elgg-river-comments .elgg-media {
	padding: 0;
}
.elgg-river-more {
	background-color: #EEEEEE;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	padding: 2px 4px;
	font-size: 85%;
	margin-bottom: 2px;
}
.elgg-river-item form {
	background-color: #EEEEEE;
	padding: 4px 4px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	display: none;
	height: 30px;
}
.elgg-river-item input[type=text] {
	width: 80%;
}
.elgg-river-item input[type=submit] {
	margin: 0 0 0 10px;
}
.elgg-river-item > .elgg-alt a {
	font-size: 90%;
	float: right;
	clear: both;
}

/* ***************************************
	LOGIN / REGISTER
*************************************** */
/* login in sidebar */
.elgg-aside #login {
	width:auto;
}
.elgg-aside #login form {
	width:auto;
}
.elgg-aside #login .login-textarea {
	width:196px;
}
/* default login and register forms */
#login input[type="text"],
#login input[type="password"],
.register input[type="text"],
.register input[type="password"] {
	margin:0 0 10px 0;
}
.register input[type="text"],
.register input[type="password"] {
	width:380px;
}
.rememberme label {
	font-weight:normal;
	font-size:100%;
}
.loginbox .elgg-submit-button {
	margin-right: 15px;
}
#login .persistent-login {
	float:right;
	display:block;
	margin-top:-34px;
	margin-left:80px;
}
#login .persistent-login label {
	font-size:1.0em;
	font-weight: normal;
	cursor: pointer;
}
#login-dropdown {
	float:right;
	position: absolute;
	top:10px;
	right:0;
	z-index: 9599;
}
#login-dropdown #signin-button {
	padding:10px 0px 12px;
	line-height:23px;
	text-align:right;
}
#login-dropdown #signin-button a.signin {
	padding:2px 6px 3px 6px;
	text-decoration:none;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color:white;
	border:1px solid #71B9F7;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
}
#login-dropdown #signin-button a.signin span {
	padding:4px 0 6px 12px;
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position:-150px -51px;
	background-repeat:no-repeat;
}
#login-dropdown #signin-button a.signin:hover {
	background-color:#71B9F7;
	/* color:black; */
}
#login-dropdown #signin-button a.signin:hover span {
	/* background-position:-150px -71px; */
}
#login-dropdown #signin-button a.signin.menu-open {
	background:#cccccc !important;
	color:#666666 !important;
	border:1px solid #cccccc;
	outline:none;
}
#login-dropdown #signin-button a.signin.menu-open span {
	background-position:-150px -71px;
	color:#333333;
}
#login-dropdown #signin-menu {
	-moz-border-radius-topleft:5px;
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	-webkit-border-top-left-radius:5px;
	-webkit-border-bottom-left-radius:5px;
	-webkit-border-bottom-right-radius:5px;
	display:none;
	background-color:white;
	position:absolute;
	width:210px;
	z-index:100;
	border:5px solid #CCCCCC;
	text-align:left;
	padding:12px;
	top: 26px;
	right: 0px;
	margin-top:5px;
	margin-right: 0px;
	color:#333333;
	-webkit-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.45);
}
#login-dropdown #signin-menu input[type=text],
#login-dropdown #signin-menu input[type=password] {
	width:203px;
	margin:0 0 5px;
}
#login-dropdown #signin-menu p {
	margin:0;
}
#login-dropdown #signin-menu label {
	font-weight:normal;
	font-size: 100%;
}
#login-dropdown #signin-menu .elgg-submit-button {
	margin-right:15px;
}


/* ***************************************
	USER SETTINGS
*************************************** */
table.styled {
	width:100%;
}
table.styled {
	border-top:1px solid #cccccc;
}
table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
table.styled td.column-one {
	width:200px;
}
table.styled tr:hover {
	background: #E4E4E4;
}

/* ***************************************
	FRIENDS PICKER
*************************************** */
.friends-picker-container h3 {
	font-size:4em !important;
	text-align: left;
	margin:10px 0 20px 0 !important;
	color:#999999 !important;
	background: none !important;
	padding:0 !important;
}
.friends-picker .friends-picker-container .panel ul {
	text-align: left;
	margin: 0;
	padding:0;
}
.friends-picker-wrapper {
	margin: 0;
	padding:0;
	position: relative;
	width: 100%;
}
.friends-picker {
	position: relative;
	overflow: hidden;
	margin: 0;
	padding:0;
	width: 730px;
	height: auto;
}
.friendspicker-savebuttons {
	background: white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	margin:0 10px 10px 10px;
}
.friends-picker .friends-picker-container { /* long container used to house end-to-end panels. Width is calculated in JS  */
	position: relative;
	left: 0;
	top: 0;
	width: 100%;
	list-style-type: none;
}
.friends-picker .friends-picker-container .panel {
	float:left;
	height: 100%;
	position: relative;
	width: 730px;
	margin: 0;
	padding:0;
}
.friends-picker .friends-picker-container .panel .wrapper {
	margin: 0;
	padding:4px 10px 10px 10px;
	min-height: 230px;
}
.friends-picker-navigation {
	margin: 0 0 10px 0;
	padding:0 0 10px 0;
	border-bottom:1px solid #cccccc;
}
.friends-picker-navigation ul {
	list-style: none;
	padding-left: 0;
}
.friends-picker-navigation ul li {
	float: left;
	margin:0;
	background:white;
}
.friends-picker-navigation a {
	font-weight: bold;
	text-align: center;
	background: white;
	color: #999999;
	text-decoration: none;
	display: block;
	padding: 0;
	width:20px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
.tabHasContent {
	background: white;
	color:#333333 !important;
}
.friends-picker-navigation li a:hover {
	background: #333333;
	color:white !important;
}
.friends-picker-navigation li a.current {
	background: #4690D6;
	color:white !important;
}
.friends-picker-navigation-l, .friends-picker-navigation-r {
	position: absolute;
	top: 46px;
	text-indent: -9000em;
}
.friends-picker-navigation-l a, .friends-picker-navigation-r a {
	display: block;
	height: 43px;
	width: 43px;
}
.friends-picker-navigation-l {
	right: 48px;
	z-index:1;
}
.friends-picker-navigation-r {
	right: 0;
	z-index:1;
}
.friends-picker-navigation-l {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat left top;
}
.friends-picker-navigation-r {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat -60px top;
}
.friends-picker-navigation-l:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat left -44px;
}
.friends-picker-navigation-r:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat -60px -44px;
}
.friendspicker-savebuttons .elgg-submit-button,
.friendspicker-savebuttons .elgg-cancel-button {
	margin:5px 20px 5px 5px;
}
#collectionMembersTable {
	background: #dedede;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	margin:10px 0 0 0;
	padding:10px 10px 0 10px;
}




/* ***************************************
	AVATAR UPLOADING & CROPPING
*************************************** */
#avatar-upload {
	height:145px;
}
#current-user-avatar {
	float:left;
	width:160px;
	height:130px;
	border-right:1px solid #cccccc;
	margin:0 20px 0 0;
}
#avatar-croppingtool {
	border-top: 1px solid #cccccc;
	margin:20px 0 0 0;
	padding:10px 0 0 0;
}
#user-avatar {
	float: left;
	margin-right: 20px;
}
#user-avatar-preview {
	float: left;
	position: relative;
	overflow: hidden;
	width: 100px;
	height: 100px;
}

/* ***************************************
	MISC
*************************************** */

.user-picker .user-picker-entry {
	clear:both;
	height:25px;
	padding:5px;
	margin-top:5px;
	border-bottom:1px solid #cccccc;
}
.user-picker-entry .delete-button {
	margin-right:10px;
}
#dashboard-info .elgg-inner {
	border: 2px solid #dedede;
}
