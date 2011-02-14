<?php
/**
 *
 */

?>




<?php //@todo needs revamp: elgg-login-form, elgg-register-form? ?>
/* ***************************************
	LOGIN / REGISTER
*************************************** */
#login-dropdown {
	position: absolute;
	top:10px;
	right:0;
	z-index: 9599;
}

.elgg-button-dropdown {
	padding:3px 6px;
	text-decoration:none;
	display:block;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color: white;
	border:1px solid #71B9F7;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
	/*background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position:-150px -51px;
	background-repeat:no-repeat;*/
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size:smaller;
}

.elgg-button-dropdown:hover {
	background-color:#71B9F7;
	text-decoration:none;
}

.elgg-button-dropdown.elgg-state-active {
	background: #cccccc;
	outline: none;
	color: #333333;
	
	border:1px solid #cccccc;
	-webkit-border-radius:4px 4px 0 0;
	-moz-border-radius:4px 4px 0 0;
	border-radius:4px 4px 0 0;
}

<?php //@todo needs revamp ?>
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
.friendspicker-savebuttons .elgg-button-submit,
.friendspicker-savebuttons .elgg-button-cancel {
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
