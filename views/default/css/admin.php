<?php
/**
 * Elgg Admin CSS
 *
 * @package Elgg.Core
 * @subpackage UI
 */


//echo elgg_view('css/elements/reset', $vars);
//echo elgg_view('css/elements/grid', $vars);
//echo elgg_view('css/elements/spacing', $vars);
//echo elgg_view('css/elements/base', $vars);

// remove these as we finish the admin theme
//echo elgg_view('css/elements/typography', $vars);
//echo elgg_view('css/elements/chrome', $vars);
//echo elgg_view('css/elements/forms', $vars);
//echo elgg_view('css/elements/navigation', $vars);
//echo elgg_view('css/elements/core', $vars);
//echo elgg_view('css/elements/icons', $vars);
//echo elgg_view('css/elements/layout', $vars);
//echo elgg_view('css/elements/misc', $vars);

?>

/* ***************************************
	RESET CSS
*************************************** */
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
}
img {
	border-width:0;
	border-color:transparent;
}
:focus {
	outline:0 none;
	-moz-outline-style: none;
}
ol, ul {
	list-style: none;
}
em, i {
	font-style:italic;
}
ins {
	text-decoration:none;
}
del {
	text-decoration:line-through;
}
strong, b {
	font-weight:bold;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
caption, th, td {
	text-align: left;
	font-weight: normal;
	vertical-align: top;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: "";
}
blockquote, q {
	quotes: "" "";
}

/* ***************************************
	BASICS
*************************************** */

body {
	background-color: #eeeeee;
	font-size: 80%;
	line-height: 1.4em;
	font-family: "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: auto;
	color: #666666;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }

a {
	color: #333333;
}
a:hover {
	color: black;
	text-decoration: underline;
}
pre, code {
	background: #EBF5FF;
}
blockquote {
	background: #EBF5FF;
}
p {
	margin-bottom: 15px;
}

table.mceLayout {
	width:100% !important;
}

.clearfloat { 
	clear:both;
}

/* Clearfix! */
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}
.elgg-body {
	width: auto;
	word-wrap: break-word;
	display: table-cell;
}
.elgg-body:after {
	display: block;
	visibility: hidden;
	height: 0 !important;
	line-height: 0;
	font-size: xx-large;
	content: " x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x ";
}
.hidden {
	display:none;
}
.centered {
	margin:0 auto;
}
.center {
	text-align: center;
}

/* ***************************************
	HEADER
*************************************** */
.elgg-page-header {
	margin: 0 auto;
	padding: 20px 40px 0;
	min-width: 800px;
	max-width: 1600px;
}
.elgg-page-header > .elgg-inner {
	background-color: #111111;
	border: 1px solid #999999;
	padding: 20px 20px;
}
.elgg-site-title {
	font-size: 1.8em;
	float: left;
}
.elgg-site-title a {
	color: #dddddd;
	text-decoration: none;
}
.elgg-site-title a:hover {
	color: white;
	text-decoration: none;
}
.elgg-user-menu {
	float: right;
	margin-top: 5px;
}
.elgg-user-menu, .elgg-user-menu a {
	color: #999999;
}
.elgg-user-menu a {
	text-decoration: underline;
}
.elgg-user-menu a:hover {
	color: white;
}
.elgg-user-menu li {
	display: inline;
}
.elgg-user-menu li:after{
	content: "|";
	display: inline-block;
	font-weight: normal;
	margin-left: 8px;
	margin-right: 4px;
}
.elgg-user-menu li:last-child:after {
	content: "";
}

/* ***************************************
	BODY
*************************************** */
.elgg-page-body {
	margin: 0 auto;
	padding: 20px 40px;
	min-width: 800px;
	max-width: 1600px;
}
.elgg-main  {
	background-color: #ffffff;
	border: 1px solid #cccccc;
	padding: 20px;
	position: relative;
}
.elgg-main > .elgg-inner {
	min-height: 400px;
}
.elgg-sidebar {
	width: 210px;
	float: right;
	margin-left: 30px;
}
.elgg-main > .elgg-inner > .elgg-head {
	margin-bottom: 10px;
}
.elgg-main h2 {
	color: #333333;
}

/* ***************************************
	SIDEBAR MENU
*************************************** */
.elgg-page-menu a {
	display: block;
	padding: 5px;
	color: #333333;
	cursor: pointer;
	text-decoration: none;
}
.elgg-page-menu .elgg-menu-closed:before {
	content: "\25B8";
	padding-right: 4px;
}
.elgg-page-menu .elgg-menu-opened:before {
	content: "\25BE";
	padding-right: 4px;
}
.elgg-page-menu a:hover {
	color: black;
	text-decoration: none;
}
.elgg-page-menu li.selected > ul {
	display: block;
}
.elgg-page-menu .elgg-child-menu {
	display: none;
}
.elgg-page-menu .elgg-child-menu a {
	padding-left: 20px;
}

/* ***************************************
	MODULES
*************************************** */

.elgg-main-module {
	background-color: #ffffff;
	border: 1px solid #cccccc;
	padding: 10px;
}
.elgg-main-module > .elgg-head {
	margin-bottom: 5px;
}
.elgg-inline-module {
	margin: 20px 0;
}
.elgg-inline-module > .elgg-head {
	background-color: #999999;
	color: white;
	padding: 5px;
	margin-bottom: 10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.elgg-inline-module > .elgg-head h3 {
	color: white;
}

/* ***************************************
	TABLE
*************************************** */
.elgg-table {
	width: 100%;
	border-top: 1px solid #cccccc;
}
.elgg-table td, th {
	background: white;
	border: 1px solid #cccccc;
}
.elgg-table th {
	background-color: #dddddd;
}
.elgg-table .alt td {
	background: #eeeeee;
}
.elgg-table td {
	padding: 4px 8px;
	border-bottom: 1px solid #cccccc;
}
.elgg-table-alt {
	width: 100%;
	border-top: 1px solid #cccccc;
}
.elgg-table-alt td {
	padding: 2px 4px 2px 4px;
	border-bottom: 1px solid #cccccc;
}
.elgg-table-alt td:first-child {
	width: 200px;
}
.elgg-table-alt tr:hover {
	background: #E4E4E4;
}

/* ***************************************
	LISTS AND IMAGE BLOCK
*************************************** */
.elgg-image-block {
	padding: 3px 0;
}
.elgg-image-block .elgg-image {
	float: left;
	margin-right: 5px;
}
.elgg-list-item {
	margin: 3px;
}
.elgg-list-metadata {
	float: right;
	margin-left: 15px;
	font-size: 90%;
}
.elgg-list-metadata > li {
	float: left;
	margin-left: 15px;
}
.elgg-list-metadata, .elgg-list-metadata a {
	color: #aaaaaa;
}
.elgg-simple-list li {
	margin-bottom: 5px;
}
/* ***************************************
	FORMS AND INPUT
*************************************** */
label {
	font-weight: bold;
	color:#333333;
	font-size: 110%;
}
input {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #cccccc;
	color:#666666;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
input[type="submit"], .elgg-submit-button, .elgg-action-button {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-decoration: none;
	background-color: #333333;
	border-color: #333333;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin: 10px 5px 10px 0;
	cursor: pointer;
	outline: none;
}
input[type="submit"]:hover, .elgg-submit-button:hover, .elgg-action-button:hover {
	color: white;
	background-color: #000000;
	text-decoration: none;
}
.elgg-submit-button, .elgg-action-button {
	padding: 4px 8px;
}
/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination {
	margin: 10px 0;
	display: block;
	text-align: center;
}
.elgg-pagination li {
	display: inline;
	margin: 0 6px 0 0;
	text-align: center;
}
.elgg-pagination a, .elgg-pagination span {
	padding: 2px 6px;
	color: #333333;
	border: 1px solid #333333;
	font-size: 12px;
	text-decoration: none;
}
.elgg-pagination a:hover {
	background: #333333;
	color: white;
	text-decoration: none;
}

<?php //@todo use "disabled" on the li ?>
.elgg-pagination .inactive {
	color: #CCCCCC;
	border-color: #CCCCCC;
}

<?php //@todo Choose b/w elgg-state-active or elgg-state-selected ?>
.elgg-pagination .active {
	color: #555555;
	border-color: #555555;
}

/* ***************************************
	WIDGETS
*************************************** */
.elgg-widgets {
	float: right;
	min-height: 30px;
}
.elgg-widget-add-control {
	text-align: right;
	margin: 5px 5px 15px;
}
.elgg-widgets-add-panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #eeeeee;
	border: 1px solid #cccccc;
}

<?php //@todo location-dependent style: make an extension of elgg-gallery ?>
.elgg-widgets-add-panel ul {
	padding: 0;
	margin: 0;
}
.elgg-widgets-add-panel li {
	float: left;
	margin: 2px 10px;
	list-style: none;
	width: 200px;
	padding: 4px;
	background-color: #eeeeee;
	border: 1px solid #cccccc;
	font-weight: bold;
}
.elgg-widgets-add-panel li a {
	display: block;
}
.elgg-state-available {
	color: #333333;
	cursor: pointer;
}
.elgg-state-available:hover {
	border-color: #aaaaaa;
}
.elgg-state-unavailable {
	color: #888888;
}
.elgg-module-widget {
	background-color: #dedede;
	padding: 1px;
	margin: 0 5px 15px;
	position: relative;
}
.elgg-module-widget:hover {
	background-color: #cccccc;
}
.elgg-module-widget > .elgg-head {
	background-color: #f5f5f5;
	height: 30px;
	line-height: 30px;
	overflow: hidden;
}
.elgg-module-widget > .elgg-head h3 {
	float: left;
	padding: 0 45px 0 20px;
	color: #333333;
}
.elgg-module-widget > .elgg-head a {
	position: absolute;
	top: 5px;
	display: block;
	width: 18px;
	height: 18px;
	border: 1px solid transparent;
}
.elgg-state-draggable > .elgg-head {
	cursor: move;
}
a.elgg-widget-collapse-button {
	left: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 0px -385px;
}
a.elgg-widget-collapsed {
	background-position: 0px -365px;
}
a.elgg-widget-delete-button {
	right: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -198px 3px;
}
a.elgg-widget-edit-button {
	right: 25px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -1px;
}
a.elgg-widget-edit-button:hover, a.elgg-widget-delete-button:hover {
	border: 1px solid #cccccc;
}
.elgg-module-widget > .elgg-body {
	border-top: 1px solid #dedede;
	background-color: white;
	width: 100%;
	overflow: hidden;
}
.elgg-widget-edit {
	display: none;
	width: 96%;
	padding: 2%;
	border-bottom: 1px solid #dedede;
}
.elgg-widget-content {
	padding: 10px;
}
.elgg-widget-placeholder {
	border: 2px dashed #dedede;
	margin-bottom: 15px;
}




.elgg-list-subtitle, .elgg-subtitle {
	color: #666666;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
	margin-bottom: 5px;
}

.avatar_menu_button {
	display: none;
}

<?php // @todo clean up and figure out what admin css needs ?>
.elgg-user-icon {
	position:relative;
}
.elgg-hover-menu {
	display: none;
}
.elgg-user-icon.tiny,
img.tiny {
	width:25px;
	height:25px;

	-o-background-size: 25px;
	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
}
.elgg-user-icon.small,
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




/*

.elgg-admin .elgg-page-footer {
	background: #333333;
	border-top: 1px solid #222222;
	height: 30px;
	width: 100%;
}


*/




<?php //@todo convert to new style ?>
.admin_settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.elgg-admin .elgg-input-textarea {
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
.input-text,
.input-tags,
.input-url,
.input-textarea {
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
.admin_area .elgg-submit-button {
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
.admin_area .elgg-submit-button:hover {
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
.admin_area .elgg-cancel-button {
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
.admin_area .elgg-cancel-button:hover {
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
form.admin_plugins_simpleview .elgg-submit-button {
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


.add-user form {
	width:300px;
}
/* ***************************************
	Spacing (from OOCSS)
*************************************** */
.pan{padding:0}
.pas{padding:5px}
.pam{padding:10px}
.pal{padding:20px}
.ptn{padding-top:0}
.pts{padding-top:5px}
.ptm{padding-top:10px}
.ptl{padding-top:20px}
.prn{padding-right:0}
.prs{padding-right:5px}
.prm{padding-right:10px}
.prl{padding-right:20px}
.pbn{padding-bottom:0}
.pbs{padding-bottom:5px}
.pbm{padding-bottom:10px}
.pbl{padding-bottom:20px}
.pln{padding-left:0}
.pls{padding-left:5px}
.plm{padding-left:10px}
.pll{padding-left:20px}
.phn{padding-left:0;padding-right:0}
.phs{padding-left:5px;padding-right:5px}
.phm{padding-left:10px;padding-right:10px}
.phl{padding-left:20px;padding-right:20px}
.pvn{padding-top:0;padding-bottom:0}
.pvs{padding-top:5px;padding-bottom:5px}
.pvm{padding-top:10px;padding-bottom:10px}
.pvl{padding-top:20px;padding-bottom:20px}
.man{margin:0}
.mas{margin:5px}
.mam{margin:10px}
.mal{margin:20px}
.mtn{margin-top:0}
.mts{margin-top:5px}
.mtm{margin-top:10px}
.mtl{margin-top:20px}
.mrn{margin-right:0}
.mrs{margin-right:5px}
.mrm{margin-right:10px}
.mrl{margin-right:20px}
.mbn{margin-bottom:0}
.mbs{margin-bottom:5px}
.mbm{margin-bottom:10px}
.mbl{margin-bottom:20px}
.mln{margin-left:0}
.mls{margin-left:5px}
.mlm{margin-left:10px}
.mll{margin-left:20px}
.mhn{margin-left:0;margin-right:0}
.mhs{margin-left:5px;margin-right:5px}
.mhm{margin-left:10px;margin-right:10px}
.mhl{margin-left:20px;margin-right:20px}
.mvn{margin-top:0;margin-bottom:0}
.mvs{margin-top:5px;margin-bottom:5px}
.mvm{margin-top:10px;margin-bottom:10px}
.mvl{margin-top:20px;margin-bottom:20px}

/* ***************************************
	GRID
*************************************** */
.elgg-grid {
}
.elgg-col {
	float: left;
}
.elgg-col-1of1 {
	float: none;
}
.elgg-col-1of2 {
	width: 50%;
}
.elgg-col-1of3 {
	width: 33.33%;
}
.elgg-col-2of3 {
	width: 66.66%;
}
.elgg-col-1of4 {
	width: 25%;
}
.elgg-col-3of4 {
	width: 75%;
}
.elgg-col-1of5 {
	width: 20%;
}
.elgg-col-2of5 {
	width: 40%;
}
.elgg-col-3of5 {
	width: 60%;
}
.elgg-col-4of5 {
	width: 80%;
}
.elgg-col-1of6 {
	width: 16.66%;
}
.elgg-col-5of6 {
	width: 83.33%;
}
