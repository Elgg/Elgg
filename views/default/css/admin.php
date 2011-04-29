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
<?php // force vertical scroll bar ?>
html, body {
	height: 100%;
	margin-bottom: 1px;
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
	text-decoration: none;
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

.elgg-output dt { font-weight: bold }
.elgg-output dd { margin: 0 0 1em 2em }

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
	cursor: pointer;
}
.elgg-message.elgg-state-error {
	background: #fbe3e4;
	color: #8a1f11;
	border-color: #fbc2c4;
	font-weight: bold;
}
.elgg-message.elgg-state-success {
	background: #e6efc2;
	color: #264409;
	border-color: #c6d880;
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
<?php // elgg-layout gets clearfix ?>
.elgg-layout:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
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
.elgg-table-alt th {
	background-color: #eee;
	font-weight: bold;
}
.elgg-table-alt td, th {
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
/* default elgg core input field classes */
.input-text,
.input-tags,
.input-url,
.input-textarea,
<?php // until we're all on elgg-, need to duplicate ?>
.elgg-input-text,
.elgg-input-tags,
.elgg-input-url,
.elgg-input-textarea {
	width:98%;
}
textarea {
	height: 100px;
}
input[type="submit"], .elgg-button-submit, .elgg-button-action, .elgg-button-cancel {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-decoration: none;
	background-color: #333;
	border-color: #333;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

	display: inline-block;
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
.elgg-button-submit, .elgg-button-action, .elgg-button-cancel {
	padding: 4px 8px;
}
.elgg-button-cancel {
	color: #333;
	background-color: #999;
}
.elgg-button-cancel:hover {
	color: #222;
	background-color: #666;
	text-decoration: none;
}
.elgg-button-action.elgg-state-disabled {
	background-color: #aaa;
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
	FOOTER MENU
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
	HOVER MENU
*************************************** */
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
	MORE MENUS
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
	background: #eee;
	border: 1px solid #ccc;
}
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
.elgg-widget-single.elgg-state-available {
	color: #333;
	cursor: pointer;
}
.elgg-widget-single.elgg-state-available:hover {
	border-color: #aaa;
}
.elgg-widget-single.elgg-state-unavailable {
	color: #888;
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
	height: 26px;
	overflow: hidden;
}
.elgg-module-widget > .elgg-head h3 {
	float: left;
	padding: 4px 45px 0 20px;
	color: #333;
}
.elgg-module-widget > .elgg-head a {
	position: absolute;
	top: 4px;
	display: inline-block;
	width: 18px;
	height: 18px;
	padding: 2px 2px 0 0;
}

.elgg-widget-collapse-button {
	left: 5px;
	color: #c5c5c5;
	text-decoration: none;
}
a.elgg-widget-collapse-button:hover,
a.elgg-widget-collapsed:hover {
	color: #9d9d9d;
	text-decoration: none;
}
a.elgg-widget-collapse-button:before {
	content: "\25BC";
}
a.elgg-widget-collapsed:before {
	content: "\25BA";
}
.elgg-widget-delete-button {
	right: 5px;
}
.elgg-widget-edit-button {
	right: 25px;
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


.elgg-subtext {
	color: #666;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
	margin-bottom: 5px;
}

a.elgg-longtext-control {
	float: right;
	margin-left: 14px;
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
	ICONS
*************************************** */
.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/admin_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	display: inline-block;
	margin: 0 2px;
}
.elgg-icon-delete:hover,
.elgg-icon-delete-alt:hover {
	background-position: 0 -0px;
}
.elgg-icon-delete,
.elgg-icon-delete-alt {
	background-position: 0 -18px;
}
.elgg-icon-drag-arrow:hover {
	background-position: 0 -36px;
}
.elgg-icon-drag-arrow {
	background-position: 0 -54px;
}
.elgg-icon-hover-menu:hover {
	background-position: 0 -72px;
}
.elgg-icon-hover-menu {
	background-position: 0 -90px;
}
.elgg-icon-settings-alt:hover {
	background-position: 0 -108px;
}
.elgg-icon-settings-alt {
	background-position: 0 -126px;
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
.elgg-avatar > .elgg-icon-hover-menu {
	display: none;
	position: absolute;
	right: 0;
	bottom: 0;
	margin: 0;
	cursor: pointer;
}
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

<?php //@todo elgg-drag-handle instead? ?>
.elgg-state-draggable .elgg-head {
	cursor: move;
}

/* ***************************************
	ADMIN MISC
*************************************** */

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
	margin-bottom: 0;
	padding-left: 0;
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
.elgg-plugin.elgg-state-inactive {
	background: #dedede;
}
.elgg-dependency.elgg-state-error {
	background: #fbe3e4;
	color: #8a1f11;
	border-color: #fbc2c4;
	font-weight: bold;
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

/****************************************
	Markdown Text
****************************************/

.elgg-markdown {
	margin: 15px;
}

.elgg-markdown h1,
.elgg-markdown h2,
.elgg-markdown h3,
.elgg-markdown h4,
.elgg-markdown h5,
.elgg-markdown h6 {
	margin: 1em 0 1em -15px;
	color: #333;
}

.elgg-markdown ol {
	list-style: decimal;
	padding-left: 2em;
}

.elgg-markdown ul {
	list-style: disc;
	padding-left: 2em;
}

.elgg-markdown p {
	margin: 15px 0;
}