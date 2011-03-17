<?php
/**
 * Elgg Admin CSS
 *
 * This is a distinct theme from the theme of the site. There are dependencies
 * on the HTML created by the views in Elgg core.
 *
 * @package Elgg.Core
 * @subpackage UI
 */

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
	background-color: #eee;
	font-size: 80%;
	line-height: 1.4em;
	font-family: "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: auto;
	color: #666;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }

a {
	color: #333;
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
.elgg-grid:after,
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
	overflow: hidden;
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
	PAGE WRAPPER
*************************************** */
.elgg-page > .elgg-inner {
	margin: 0 auto;
	padding: 20px 40px 0;
	min-width: 800px;
	max-width: 1600px;
}

/* ***************************************
	HEADER
*************************************** */
.elgg-page-header {
	background-color: #111;
	border: 1px solid #999;
	padding: 20px 20px;
}
.elgg-heading-site {
	font-size: 1.8em;
	float: left;
}
.elgg-heading-site a {
	color: #ffffff;
	text-decoration: none;
}
.elgg-heading-site a:hover {
	color: white;
	text-decoration: none;
}
.elgg-menu-user {
	float: right;
	margin-top: 5px;
}
.elgg-menu-user, .elgg-menu-user a {
	color: #999999;
}
.elgg-menu-user a {
	text-decoration: underline;
}
.elgg-menu-user a:hover {
	color: white;
}
.elgg-menu-user li {
	display: inline;
}
.elgg-menu-user li:after{
	content: "|";
	display: inline-block;
	font-weight: normal;
	margin-left: 8px;
	margin-right: 4px;
}
.elgg-menu-user li:last-child:after {
	content: "";
}

.elgg-page-messages {
	padding: 20px 0 0;
	width: 500px;
	margin-bottom: -10px;
}
.elgg-system-messages p {
	margin: 0;
}
.elgg-message {
	padding: 10px;
	margin-bottom: 10px;
	border: 2px solid #ddd;
}

/* ***************************************
	BODY
*************************************** */
.elgg-page-body {
	padding: 20px 0;
}
.elgg-main  {
	background-color: #fff;
	border: 1px solid #ccc;
	padding: 20px;
	position: relative;
	min-height: 400px;
}
.elgg-sidebar {
	width: 210px;
	float: right;
	margin-left: 30px;
}
.elgg-main > .elgg-head {
	margin-bottom: 10px;
}
.elgg-main h2 {
	color: #333333;
}

/* ***************************************
	FOOTER
*************************************** */
.elgg-page-footer {
	background-color: #111;
	border: 1px solid #999;
	padding: 10px 20px;
}
.elgg-page-footer a {
	color: #ddd;
	font-weight: bold;
	text-decoration: none;
}
.elgg-page-footer a:hover {
	text-decoration: underline;
}


/* ***************************************
	SIDEBAR MENU
*************************************** */
.elgg-admin-sidebar-menu a {
	border: 1px solid red;
	display: block;
	padding: 5px;
	color: #333;
	cursor: pointer;
	text-decoration: none;
	margin-bottom: 2px;
	border: 1px solid #CCC;

	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}

.elgg-admin-sidebar-menu a:hover {
	text-decoration: none;
	background: black;
	color: white;
	border: 1px solid black;
}
.elgg-admin-sidebar-menu li.elgg-state-selected > a {
	background-color: #BBB;
}

.elgg-admin-sidebar-menu .elgg-menu-closed:before {
	content: "\25B8";
	padding-right: 4px;
}

.elgg-admin-sidebar-menu .elgg-menu-opened:before {
	content: "\25BE";
	padding-right: 4px;
}

.elgg-admin-sidebar-menu .elgg-child-menu {
	display: none;
	padding-left: 30px;
}
.elgg-admin-sidebar-menu li.elgg-state-selected > ul {
	display: block;
}

.elgg-admin-sidebar-menu h2 {
	padding-bottom: 5px;
}

.elgg-admin-sidebar-menu ul.elgg-menu-page {
	padding-bottom: 15px;
}


/* ***************************************
	MODULES
*************************************** */

.elgg-module-main {
	background-color: #fff;
	border: 1px solid #ccc;
	padding: 10px;
}
.elgg-module-main > .elgg-head {
	margin-bottom: 5px;
}
.elgg-module-inline {
	margin: 20px 0;
}
.elgg-module-inline > .elgg-head {
	background-color: #999;
	color: white;
	padding: 5px;
	margin-bottom: 10px;
	
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
}
.elgg-module-inline > .elgg-head h3 {
	color: white;
}

/* ***************************************
	TABLE
*************************************** */
.elgg-table {
	width: 100%;
	border-top: 1px solid #ccc;
}
.elgg-table td, .elgg-table th {
	background: white;
	border: 1px solid #ccc;
}
.elgg-table th {
	background-color: #ddd;
}
.elgg-table .alt td {
	background: #eee;
}
.elgg-table td {
	padding: 4px 8px;
	border-bottom: 1px solid #ccc;
}
.elgg-table-alt {
	width: 100%;
	border-top: 1px solid #ccc;
}
.elgg-table-alt td {
	padding: 2px 4px;
	border-bottom: 1px solid #ccc;
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
.elgg-image-block .elgg-image-alt {
	float: right;
	margin-left: 5px;
}

.elgg-list-item {
	margin: 3px;
}
.elgg-menu-metadata {
	float: right;
	margin-left: 15px;
	font-size: 90%;
}
.elgg-menu-metadata > li {
	float: left;
	margin-left: 15px;
}
.elgg-menu-metadata, .elgg-menu-metadata a {
	color: #aaa;
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
fieldset > div {
	margin-bottom: 15px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}
input {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #ccc;
	color:#666;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
input[type="submit"], .elgg-button-submit, .elgg-button-action {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-decoration: none;
	background-color: #333;
	border-color: #333;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	width: auto;
	padding: 2px 4px;
	margin: 10px 5px 10px 0;
	cursor: pointer;
	outline: none;
}
input[type="submit"]:hover, .elgg-button-submit:hover, .elgg-button-action:hover {
	color: white;
	background-color: #000;
	text-decoration: none;
}
.elgg-button-submit, .elgg-button-action {
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
	color: #333;
	border: 1px solid #333;
	font-size: 12px;
	text-decoration: none;
}
.elgg-pagination a:hover {
	background: #333;
	color: white;
	text-decoration: none;
}

.elgg-pagination .elgg-state-disabled {
	color: #CCC;
	border-color: #CCC;
}
.elgg-pagination .elgg-state-selected {
	color: #555;
	border-color: #555;
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	margin-bottom: 5px;
	border-bottom: 1px solid #ccc;
	display: table;
	width: 100%;
}
.elgg-tabs li {
	float: left;
	border: 1px solid #ccc;
	border-bottom-width: 0;
	background: #eee;
	margin: 0 0 0 10px;
}
.elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0 10px;
	text-align: center;
	height: 21px;
	color: #999;
}
.elgg-tabs a:hover {
	background: #dedede;
	color:#333;
}
.elgg-tabs .elgg-state-selected {
	border-color: #ccc;
	background: white;
}
.elgg-tabs .elgg-state-selected a {
	position: relative;
	top: 2px;
	background: white;
}

/* ***************************************
	WIDGETS
*************************************** */
.elgg-widgets {
	float: left;
	min-height: 30px;
}
.elgg-widget-add-control {
	text-align: right;
	margin: 5px 5px 15px;
}
.elgg-widgets-add-panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #eee;
	border: 1px solid #ccc;
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
	background-color: #eee;
	border: 1px solid #ccc;
	font-weight: bold;
}
.elgg-widgets-add-panel li a {
	display: block;
}

.elgg-module-widget {
	background-color: #dedede;
	padding: 1px;
	margin: 0 5px 15px;
	position: relative;
}
.elgg-module-widget:hover {
	background-color: #ccc;
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
	color: #333;
}
.elgg-module-widget > .elgg-head a {
	position: absolute;
	top: 5px;
	display: inline-block;
	width: 18px;
	height: 18px;
	padding: 2px 2px 0 0;
	border: 1px solid transparent;
}

.elgg-widget-collapse-button {
	left: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 0px -385px;
}
.elgg-widget-collapsed {
	background-position: 0px -365px;
}
.elgg-widget-delete-button {
	right: 5px;
}
.elgg-widget-edit-button {
	right: 25px;
}
.elgg-module-widget .elgg-widget-edit-button:hover, 
.elgg-module-widget .elgg-widget-delete-button:hover {
	border: 1px solid #ccc;
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




.elgg-subtext {
	color: #666;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
	margin-bottom: 5px;
}

.avatar_menu_button {
	display: none;
}

<?php // @todo clean up and figure out what admin css needs ?>
.elgg-avatar {
	position: relative;
}
.elgg-avatar > a > img {
	display: block;
}
.elgg-avatar-tiny > a > img {
	width: 25px;
	height: 25px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
	-o-background-size: 25px;
	background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 40px;
	-khtml-background-size: 40px;
	-moz-background-size: 40px;
	-o-background-size: 40px;
	background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large > a > img {
	width: 200px;
	height: 200px;
}

.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;

	width: 165px;
	border: solid 1px #E5E5E5;
	border-color: #E5E5E5 #999 #999 #E5E5E5;
	background-color: #FFF;
	
	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
.elgg-menu-hover > li {
	border-bottom: 1px solid #ddd;
}
.elgg-menu-hover > li:last-child {
	border-bottom: none;
}
.elgg-menu-hover .elgg-heading-basic {
	display: block;
}
.elgg-menu-hover a {
	display: block;
	padding: 2px 8px;
	font-size: 92%;
}
.elgg-menu-hover a:hover {
	background: #ccc;
	text-decoration: none;
}
.elgg-menu-hover-admin a {
	color: red;
}
.elgg-menu-hover-admin a:hover {
	color: white;
	background-color: red;
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
	border: 1px solid #ccc;
	color:#666;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
.admin_area textarea {
	font: 120% Arial, Helvetica, sans-serif;
	border: solid 1px #ccc;
	padding: 5px;
	color:#666;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
.admin_area textarea:focus,
.admin_area input[type="text"]:focus {
	border: solid 1px #666;
	background: #f5f5f5;
	color:#333;
}
.admin_area .input-textarea.monospace {
	font-family:Monaco,"Courier New",Courier,monospace;
	font-size:13px;
}
a.elgg-longtext-control {
	float: right;
	margin-left: 14px;
}
.admin_area .elgg-button-submit {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #0054A7;
	background: #0054A7 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	width: auto;
	padding: 2px 4px;
	margin:0 10px 10px 0;
	cursor: pointer;
	
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.admin_area .elgg-button-submit:hover {
	color: white;
	border-color: #333;
	text-decoration:none;
	background: #333 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
}
.admin_area input[type="password"]:focus {
	border: solid 1px #666;
	background-color: #f5f5f5;
	color: #333;
}
.admin_area input[type="submit"] {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #0054A7;
	background: #0054A7 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 0;
	cursor: pointer;
	outline: none;
	
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.admin_area input[type="submit"]:hover {
	border-color: #333;
	text-decoration:none;
	background: #333 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
}
.admin_area .elgg-button-cancel {
	font-size: 14px;
	font-weight: bold;
	text-decoration:none;
	color: #333;
	background: #ddd url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
	border: 1px solid #999;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 10px;
	cursor: pointer;
}
.admin_area .elgg-button-cancel:hover {
	background-color: #999;
	background-position:  left 10px;
	text-decoration:none;
	color:white;
}
.admin_area .content-header-options .elgg-button-action {
	margin-top:0;
	margin-left:10px;
}
.admin_area input.elgg-button-action,
.admin_area a.elgg-button-action {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	background: #ccc url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif) repeat-x 0 0;
	border:1px solid #999;
	color:#333;
	padding:2px 15px;
	text-align:center;
	font-weight:bold;
	text-decoration:none;
	text-shadow:0 1px 0 white;
	cursor:pointer;
	
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
.admin_area input.elgg-button-action:hover,
.admin_area a.elgg-button-action:hover,
.admin_area input.elgg-button-action:focus,
.admin_area a.elgg-button-action:focus {
	background-position:0 -15px;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	color:#111;
	text-decoration: none;
	background-color:#ccc;
	border:1px solid #999;
}
.admin_area .elgg-button-action:active {
	background-image:none;
}
.admin_area .elgg-button-action.elgg-state-disabled {
	color:#999;
	padding:2px 7px;
}
.admin_area .elgg-button-action.elgg-state-disabled:hover {
	background-position:0 -15px;
	color:#111;
	border:1px solid #999;
}
.admin_area .elgg-button-action.elgg-state-disabled:active {
	background-image:none;
}
.admin_area .elgg-button-action.download {
	padding: 5px 9px 5px 6px;
}
.admin_area .elgg-button-action.download:hover {

}
.admin_area .elgg-button-action.download img {
	margin-right:6px;
	position:relative;
	top:5px;
}
.admin_area .elgg-button-action.small {
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	
	width: auto;
	height:8px;
	padding: 4px;
	font-size: 0.9em;
	line-height: 0.6em;
}
.admin_area .elgg-button-action.small:hover {
	background-color: #0054A7;
	background-image: none;
	border-color: #0054A7;
	color:white;
	text-shadow:0 -1px 0 #999;
}


.manifest_file {
	background-color:#eee;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	padding:5px 10px;
	margin:4px 0;
}


.plugin_controls {
	padding: 3px 3px 3px 0;
	font-weight: bold;
	float: left;
	width: 150px;
}
form.admin_plugins_simpleview .elgg-button-submit {
	margin-right:20px;
}
.plugin_info {
	margin: 3px;
	padding-left: 150px;
	display: block;
}
.plugin_metadata {
	display:block;
	color:#999;
}
.plugin_name input[type="checkbox"] {
	margin-right: 10px;
}
ul.admin_plugins {
	margin-bottom:0;
	padding-left:0;
	list-style: none;
}
.elgg-plugin {
	border:1px solid #999;
	margin:0 0 5px;
	padding:0 7px 4px 10px;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}

.elgg-plugin p {
	margin:0;
	padding:0;
}
.elgg-plugin h3 {
	color:black;
	padding-bottom: 10px;
}
.plugin_settings {
	font-weight: normal;
}

.elgg-plugin-screenshot {
	display: inline;
}
.elgg-plugin-screenshot img {
	border: 1px solid #999;
}
.elgg-plugin-screenshot-lightbox {
	display: block;
	position: absolute;
	width: 99%;
	text-align: center;
	background-color: white;
	border: 1px solid #999;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
.elgg-plugin-screenshot-lightbox h2 {
	color:black;
}

.elgg-plugin.elgg-state-active {
	background: white;
}

.admin_notices {
	padding-bottom: 15px;
}
.admin_notices p {
	background-color:#BDE5F8;
	color: black;
	border: 1px solid blue;
	font-weight: bold;
	padding: 3px 0px 3px 10px;
	
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}

.admin_notices a {
	float: right;
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
.elgg-grid {}
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

/* ***************************************
	ICONS
*************************************** */

.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	display: block;
	float: left;
	margin: 0 2px;
}
.elgg-icon-settings {
	background-position: -302px -44px;
}
.elgg-icon-friends {
	background-position: 0 -300px;
	width: 36px;
}
.elgg-icon-friends:hover {
	background-position: 0 -340px;
}
.elgg-icon-help {
	background-position: -302px -136px;
}
.elgg-icon-delete {
	background-position: -199px 1px;
}
.elgg-icon-delete:hover {
	background-position: -199px -15px;
}
.elgg-icon-thumbs-up {
	background-position: 0px -101px;
	width: 20px;
	height: 20px;
}
.elgg-icon-thumbs-up:hover {
	background-position: 0px -131px;
}
.elgg-icon-thumbs-up-alt {
	background-position: 0px -131px;
	width: 20px;
	height: 20px;
}
.elgg-icon-arrow-s {
	background-position: -146px -56px;
}
.elgg-icon-arrow-s:hover {
	background-position: -146px -76px;
}
.elgg-icon-following {
	background-position: -35px -100px;
	width: 22px;
	height: 20px;
}
.elgg-icon-rss {
	background-position: -249px 1px;
}
.elgg-icon-hover-menu {
	background-position: -150px 0;
}
.elgg-icon-hover-menu:hover {
	background-position: -150px -32px;
}
.elgg-icon-dragger {
	background-position: -302px -186px;
	width: 21px;
	height: 21px;
}
.elgg-icon-gear {
	background-position: -300px -2px;
}

.elgg-avatar > .elgg-icon-hover-menu {
	display: none;
	position: absolute;
	right: 0;
	bottom: 0;
	margin: 0;
	cursor: pointer;
}

.elgg-ajax-loader {
	background: white url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif) no-repeat center center;
	min-height: 33px;
	min-width: 33px;
}

/* ***************************************
	AVATAR ICONS
*************************************** */
.elgg-avatar {
	position: relative;
}
.elgg-avatar > a > img {
	display: block;
}
.elgg-avatar-tiny > a > img {
	width: 25px;
	height: 25px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
	-o-background-size: 25px;
	background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 40px;
	-khtml-background-size: 40px;
	-moz-background-size: 40px;
	-o-background-size: 40px;
	background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large > a > img {
	width: 200px;
	height: 200px;
}


.elgg-menu-metadata {
	list-style-type: none;
	float: right;
	margin-left: 15px;
	font-size: 90%;
}
.elgg-menu-metadata > li {
	float: left;
	margin-left: 15px;
}

.right {float:right}
.elgg-toggle {cursor:pointer}

/* ***************************************
	FOOTER
*************************************** */
.elgg-menu-footer {color:gray}

.elgg-menu-footer li {
	float: left;
}
.elgg-menu-footer li:after{
	content: "\007C";
	display: inline-block;
	padding: 0 4px 0 4px;
	font-weight: normal;
}
.elgg-menu-footer li:last-child:after {
	content: "";
}

/* ***************************************
	STATES
*************************************** */

.elgg-state-active {
	background:#ccc;
	color: #333;
}

.elgg-state-inactive {
	background:#dedede;
}

.elgg-state-available {
	color: #333;
	cursor: pointer;
}

.elgg-state-available:hover {
	border-color: #aaa;
}

.elgg-state-unavailable {
	color: #888;
}

.elgg-state-success {
	background: #e6efc2;
	color: #264409;
	border-color: #c6d880;
}

.elgg-state-error {
	background: #fbe3e4;
	color: #8a1f11;
	border-color: #fbc2c4;
	font-weight: bold;
}

p.elgg-state-error {
	padding: 5px;
}

<?php //@todo elgg-drag-handle instead? ?>
.elgg-state-draggable > .elgg-head {
	cursor: move;
}

<?php //What to do with states that don't have default styles? ?>
.elgg-state-selected {}
.elgg-state-disabled {}

/* ***************************************
	Footer and horizontal menus
*************************************** */

/* Horizontal menus w/ separator support */
.elgg-menu-hz > li,
.elgg-menu-hz > li:after,
.elgg-menu-hz > li > a {
	display:inline-block;
	vertical-align:middle;
}

/* Allow inline image blocks in horizontal menus */
.elgg-menu-hz .elgg-body:after {
	content: '.';
}
.elgg-menu-admin-footer a {
	color: #eee;
}

.elgg-menu-admin-footer > li {
	padding-right: 25px;
}