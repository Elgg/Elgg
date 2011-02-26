<?php
/**
 * CSS form/input elements
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	Form Elements
*************************************** */
fieldset > div {
	margin-bottom: 15px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}

label {
	font-weight: bold;
	color: #333333;
	font-size: 110%;
}

input, textarea {
	font: 120% Arial, Helvetica, sans-serif;
	color: #666666;
	
	padding: 5px;
	
	border: 1px solid #cccccc;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	
	width: 100%;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
}

input:focus, textarea:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333333;
}

textarea {
	height: 200px;
}


.elgg-longtext-control {
	float: right;
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}


.elgg-input-access {
	margin:5px 0 0 0;
}

input[type="checkbox"],
input[type="radio"] {
	margin:0 3px 0 0;
	padding:0;
	border:none;
	width:auto;
}
.elgg-input-checkboxes.elgg-horizontal li,
.elgg-input-radio.elgg-horizontal li {
	display: inline;
	padding-right: 10px;
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
	background-color: #dedede;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
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
	height: 40px;
	width: 40px;
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
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat left top;
}
.friends-picker-navigation-r {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat -60px top;
}
.friends-picker-navigation-l:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat left -44px;
}
.friends-picker-navigation-r:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat -60px -44px;
}
.friendspicker-savebuttons .elgg-button-submit,
.friendspicker-savebuttons .elgg-button-cancel {
	margin:5px 20px 5px 5px;
}
.friendspicker-members-table {
	background: #dedede;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	margin:10px 0 0 0;
	padding:10px 10px 0 10px;
}

/* ***************************************
	USER PICKER
*************************************** */

.user-picker .user-picker-entry {
	clear:both;
	height:25px;
	padding:5px;
	margin-top:5px;
	border-bottom:1px solid #cccccc;
}
.user-picker-entry .elgg-button-delete {
	margin-right:10px;
}