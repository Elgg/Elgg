<?php
/**
 * Elgg Admin CSS
 *
 * @package Elgg.Core
 * @subpackage UI
 */


echo elgg_view('css/elements/reset', $vars);
echo elgg_view('css/elements/core', $vars);
echo elgg_view('css/elements/typography', $vars);
echo elgg_view('css/elements/spacing', $vars);
echo elgg_view('css/elements/navigation', $vars);
echo elgg_view('css/elements/grid', $vars);
echo elgg_view('css/elements/heading', $vars);
echo elgg_view('css/elements/forms', $vars);

?>



/* ***************************************
	BASICS
*************************************** */
body {
	background-color: #444444;
}
a:hover,
a.selected {
	color: black;
	text-decoration: underline;
}
pre, code {
	background:#EBF5FF;
}
blockquote {
	background:#EBF5FF;
}
table.mceLayout {
	width:100% !important;
}







.elgg-admin .elgg-page-header {
	background-color: #333333;
	border-bottom: 1px solid #555555;
}
.elgg-admin .elgg-page-header > .elgg-inner {
	padding: 10px 20px;
}
.elgg-admin .elgg-page-footer {
	background: #333333;
	border-top: 1px solid #222222;
	height: 30px;
	width: 100%;
}
.elgg-admin .elgg-page-header h1 {
	height: 45px;
	line-height: 45px;
}
.elgg-admin .elgg-page-header h1 a {
	color: white;
}
.elgg-admin .elgg-page-header h1 a:hover {
	color: white;
	text-decoration: underline;
}
.elgg-admin .elgg-page-header a.return_to_network {
	font-size: 12px;
	font-weight: normal;
	color: #666666;
	position: absolute;
	top: 20px;
	right: 40px;
}
.elgg-admin .elgg-page-body {
	margin: 0 auto;
	padding: 20px;
	min-height: 400px;
	position: relative;
	max-width: 1600px;
}
.elgg-admin .elgg-main  {
	padding: 14px;
	background-color: white;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
}
.elgg-admin .elgg-sidebar {
	width: 210px;
	float: right;
	margin-left: 30px;
}

.elgg-admin h1,
.elgg-admin h2,
.elgg-admin h3,
.elgg-admin h4,
.elgg-admin h5,
.elgg-admin h6 {
	color:#666666;
}
.elgg-admin .elgg-sidebar .submenu {
	margin:0;
	padding:0;
	list-style: none;
	background-color: transparent;
	background-image: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
}
.elgg-admin .submenu li.selected a,
.elgg-admin .submenu li.selected li.selected a,
.elgg-admin .submenu li.selected li.selected li.selected a {
	background-color: black;
	color:white;
}
.elgg-admin .submenu li a {
	display:block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color:white;
	margin:0 0 3px 0;
	padding:2px 4px 2px 8px;
	color:#333333;
}
.elgg-admin .submenu li a:hover {
	background-color:black;
	color:white;
	text-decoration:none;
}
.elgg-admin .submenu ul.child {
	margin-bottom:10px;
}
.elgg-admin .submenu .child li a {
	margin-left:15px;
	background-color:#dedede;
	color:#333333;
}
.elgg-admin .submenu .child li a:hover {
	background-color:black;
	color:white;
}

.admin_settings h3 {
	background:#999999;
	color:white;
	padding:5px;
	margin-top:10px;
	margin-bottom:10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.admin_settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.elgg-admin .input-textarea {
	width:98%;
}
.elgg-admin form#plugin_settings {
	margin-top: 10px;
}
.elgg-admin form#plugin_settings .elgg-action-button.disabled {
	margin-top:10px;
	float:right;
}


/* ***************************************
	GENERAL FORM ELEMENTS
*************************************** */
/* default elgg core input field classes */
.admin_area .input-text,
.admin_area .input-tags,
.admin_area .input-url,
.admin_area .input-textarea {
	width:98%;
}
.admin_area .input-access {
	margin:5px 0 0 0;
}
.admin_area .input-password {
	width:200px;
}
.admin_area .input-textarea {
	height: 200px;
	width:718px;
}
.admin_area input[type="checkbox"],
.admin_area input.input-radio {
	margin:0 3px 0 0;
	padding:0;
	border:none;
}
.admin_area label {
	font-weight: bold;
	color:#333333;
	font-size: 110%;
}
.admin_area input {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #cccccc;
	color:#666666;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.admin_area textarea {
	font: 120% Arial, Helvetica, sans-serif;
	border: solid 1px #cccccc;
	padding: 5px;
	color:#666666;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.admin_area textarea:focus,
.admin_area input[type="text"]:focus {
	border: solid 1px #666666;
	background: #f5f5f5;
	color:#333333;
}
.admin_area .input-textarea.monospace {
	font-family:Monaco,"Courier New",Courier,monospace;
	font-size:13px;
}
.admin_area a.longtext-control {
	float:right;
	margin-left:14px;
}
.admin_area .submit-button {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #0054A7;
	background-color:#0054A7;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat: repeat-x;
	background-position: left 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin:0 10px 10px 0;
	cursor: pointer;
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.admin_area .submit-button:hover {
	color: white;
	border-color: #333333;
	text-decoration:none;
	background-color:#333333;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.admin_area input[type="password"]:focus {
	border: solid 1px #666666;
	background-color: #f5f5f5;
	color:#333333;
}
.admin_area input[type="submit"] {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #0054A7;
	background-color:#0054A7;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 0;
	cursor: pointer;
	-moz-outline-style: none;
	outline: none;
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.admin_area input[type="submit"]:hover {
	border-color: #333333;
	text-decoration:none;
	background-color:#333333;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.admin_area .cancel-button {
	font-size: 14px;
	font-weight: bold;
	text-decoration:none;
	color: #333333;
	background-color:#dddddd;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	border: 1px solid #999999;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 10px;
	cursor: pointer;
}
.admin_area .cancel-button:hover {
	background-color: #999999;
	background-position:  left 10px;
	text-decoration:none;
	color:white;
}
.admin_area .content-header-options .elgg-action-button {
	margin-top:0;
	margin-left:10px;
}
.admin_area input.elgg-action-button,
.admin_area a.elgg-action-button {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	background-color:#cccccc;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	background-position: 0 0;
	border:1px solid #999999;
	color:#333333;
	padding:2px 15px 2px 15px;
	text-align:center;
	font-weight:bold;
	text-decoration:none;
	text-shadow:0 1px 0 white;
	cursor:pointer;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.admin_area input.elgg-action-button:hover,
.admin_area a.elgg-action-button:hover,
.admin_area input.elgg-action-button:focus,
.admin_area a.elgg-action-button:focus {
	background-position:0 -15px;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	color:#111111;
	text-decoration: none;
	background-color:#cccccc;
	border:1px solid #999999;
}
.admin_area .elgg-action-button:active {
	background-image:none;
}
.admin_area .elgg-action-button.disabled {
	color:#999999;
	padding:2px 7px 2px 7px;
}
.admin_area .elgg-action-button.disabled:hover {
	background-position:0 -15px;
	color:#111111;
	border:1px solid #999999;
}
.admin_area .elgg-action-button.disabled:active {
	background-image:none;
}
.admin_area .elgg-action-button.download {
	padding: 5px 9px 5px 6px;
}
.admin_area .elgg-action-button.download:hover {

}
.admin_area .elgg-action-button.download img {
	margin-right:6px;
	position:relative;
	top:5px;
}
.admin_area .elgg-action-button.small {
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	width: auto;
	height:8px;
	padding: 4px;
	font-size: 0.9em;
	line-height: 0.6em;
}
.admin_area .elgg-action-button.small:hover {
	background-color: #0054A7;
	background-image: none;
	border-color: #0054A7;
	color:white;
	text-shadow:0 -1px 0 #999999;
}



/* ***************************************
	CONTENT HEADER
**************************************** */
#content_header {
	border-bottom:1px solid #CCCCCC;
}
#content_header:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}
.content-header-title {
	float:left;
}
.content-header-title {
	margin-right:10px;
	max-width: 530px;
}
.content-header-title h2 {
	border:none;
	margin-bottom:0;
	padding-bottom:5px;
}
.content-header-options {
	float:right;
}
.content-header-options .elgg-action-button {
	float:right;
	margin:0 0 5px 10px;
}

/* ECML */
.ecml_admin_table {
	width:100%;
}
.ecml_admin_table td, th {
	border: 1px solid gray;
	text-align: center;
	padding: 5px;
}
.ecml_admin_table th, .ecml_keyword_desc {
	font-weight: bold;
}
.ecml_row_odd {
	background-color: #EEE;
}
.ecml_row_even {

}
.ecml_restricted {
	color: #555;
}








.admin_settings {
	margin-bottom:20px;
}
.admin_settings table.styled {
	width:100%;
}
.admin_settings table.styled {
	border-top:1px solid #cccccc;
}
.admin_settings table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
.admin_settings table.styled td.column-one {
	width:200px;
}
.admin_settings table.styled tr:hover {
	background: #E4E4E4;
}
.admin_settings.users_online .profile_status {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	line-height:1.2em;
}
.admin_settings.users_online .profile_status span {
	font-size:90%;
	color:#666666;
}
.admin_settings.users_online  p.owner_timestamp {
	padding-left:3px;
}
.admin_plugin_reorder {
	float:right;
	width:200px;
	text-align: right;
}
.admin_plugin_reorder a {
	padding-left:10px;
	font-size:80%;
	color:#999999;
}
.manifest_file {
	background-color:#eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:5px 10px 5px 10px;
	margin:4px 0 4px 0;
}
.admin_plugin_enable_disable {
	width:150px;
	margin:10px 0 0 0;
	float:right;
	text-align: right;
}
.admin_plugin_enable_disable a {
	margin:0;
}
.pluginsettings {
	margin:15px 0 5px 0;
	background-color:#eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:10px;
}
.pluginsettings h3 {
	padding:0 0 5px 0;
	margin:0 0 5px 0;
	border-bottom:1px solid #999999;
}
#updateclient_settings h3 {
	padding:0;
	margin:0;
	border:none;
}
.plugin_controls {
	padding: 3px 3px 3px 0;
	font-weight: bold;
	float: left;
	width: 150px;
}
form.admin_plugins_simpleview .submit-button {
	margin-right:20px;
}
.plugin_info {
	margin: 3px;
	padding-left: 150px;
	display: block;
}
.plugin_metadata {
	display:block;
	color:#999999;
}
.plugin_name input[type="checkbox"] {
	margin-right: 10px;
}
ul.admin_plugins {
	margin-bottom:0;
	padding-left:0;
	list-style: none;
}
.plugin_details {
	margin:0 0 5px 0;
	padding:0 7px 4px 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.plugin_details p {
	margin:0;
	padding:0;
}
.plugin_details h3 {
	margin-top:-13px;
	color:black;
}
.plugin_settings {
	font-weight: normal;
}
.active {
	border:1px solid #999999;
	background:white;
}
.not_active {
	border:1px solid #999999;
	background:#dedede;
}
.configure_menuitems {
	margin-bottom:30px;
}
.admin_settings.menuitems .input-pulldown {
	margin-right:15px;
	margin-bottom:10px;
}
.admin_settings.menuitems .custom_menuitems {
	list-style: none;
	padding:0;
	margin:0;
}
.admin_settings.menuitems li.custom_menuitem {
	margin-bottom:20px;
}
.admin_notices {
	padding-bottom: 15px;
}
.admin_notices p {
	background-color:#BDE5F8;
	color: black;
	border: 1px solid blue;
	font-weight: bold;
	padding:3px 10px;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}

/* ***************************************
	ELGG TABBED PAGE NAVIGATION
*************************************** */
.elgg-horizontal-tabbed-nav {
	margin-bottom:5px;
	padding: 0;
	border-bottom: 2px solid #cccccc;
	display:table;
	width:100%;
}
.elgg-horizontal-tabbed-nav ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
.elgg-horizontal-tabbed-nav li {
	float: left;
	border: 2px solid #cccccc;
	border-bottom-width: 0;
	background: #eeeeee;
	margin: 0 0 0 10px;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-webkit-border-top-left-radius:5px;
	-webkit-border-top-right-radius:5px;
}
.elgg-horizontal-tabbed-nav a {
	text-decoration: none;
	display: block;
	padding:3px 10px 0 10px;
	text-align: center;
	height:21px;
	color:#999999;
}
.elgg-horizontal-tabbed-nav a:hover {
	background: #dedede;
	color:#4690D6;
}
.elgg-horizontal-tabbed-nav .selected {
	border-color: #cccccc;
	background: white;
}
.elgg-horizontal-tabbed-nav .selected a {
	position: relative;
	top: 2px;
	background: white;
}
