<?php
/**
 * Elgg primary CSS view
 *
 * @package Elgg.Core
 * @subpackage UI
 */

// check if there is a theme overriding the old css view and use it, if it exists
$old_css_view = elgg_get_view_location('css');
if ($old_css_view != "{$CONFIG->viewpath}") {
	echo elgg_view('css', $vars);
	return true;
}


echo elgg_view('css/components/reset', $vars);
echo elgg_view('css/components/grid', $vars);
echo elgg_view('css/components/typography', $vars);
echo elgg_view('css/components/spacing', $vars);
echo elgg_view('css/components/heading', $vars);
echo elgg_view('css/components/forms', $vars);


?>

/**
 * ELGG DEFAULT CSS
 */

/* Table of Contents:

	RESET CSS 					reduce browser inconsistencies in line height, margins, font size...
	CSS BASICS					<body> <p> <a> <h1>
	PAGE LAYOUT					main page content blocks: header, sidebar, footer...
	GENERIC SELECTORS			reusable generic classes
	ELGG TOPBAR					elgg topbar
	HEADER CONTENTS
	ELGG SITE NAVIGATION		Primary site navigation in header
	FOOTER CONTENTS
	SYSTEM MESSAGES				system messages overlay
	BREADCRUMBS
	SUBMENU						current page/tool submenu in sidebar
	PAGINATION					re-usable default page navigation
	ELGG TABBED NAVIGATION 		re-usable tabbed navigation
	WIDGETS
	LOGIN / REGISTER			login box, register, and lost password page styles
	CONTENT HEADER
	DEFAULT COMMENTS
	ENTITY LISTINGS				elgg's default entity listings
	USER SETTINGS				styles for user settings
	GENERAL FORM ELEMENTS		default styles for all elgg input/form elements
	FRIENDS PICKER
	LIKES
	MISC

*/
/* Colors:

	#4690D6 - elgg light blue
	#0054A7 - elgg dark blue
	#e4ecf5 - elgg v light blue
*/



/* ***************************************
	BASICS
*************************************** */
body {
	text-align:left;
	margin:0 auto;
	padding:0;
	background-color: white;
	font-size: 80%;
	line-height: 1.4em;
	font-family: "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
}
a {
	color: #4690D6;
	text-decoration: none;
	-moz-outline-style: none;
	outline: none;
}
a:hover,
a.selected {
	color: #555555;
	text-decoration: underline;
}
p {
	margin-bottom:15px;
}
p:last-child {
	margin-bottom:0;
}
small {
	font-size: 90%;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: auto;
	color:#0054A7;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }
dt {
	font-weight: bold;
}
dd {
	margin: 0 0 1em 1em;
}
pre, code {
	font-family:Monaco,"Courier New",Courier,monospace;
	font-size:12px;
	background:#EBF5FF;
	color:#000000;
	overflow:auto;

	overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */
	white-space: pre-wrap; /* css-3 */
	white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
	white-space: -pre-wrap; /* Opera 4-6 */
	white-space: -o-pre-wrap; /* Opera 7 */
	word-wrap: break-word; /* Internet Explorer 5.5+ */
}
code {
	padding:2px 3px;
}
pre {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
}
blockquote {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
	background:#EBF5FF;
	border:none;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}


/* ***************************************
	GENERIC SELECTORS
*************************************** */
h2 {
/*	border-bottom:1px solid #CCCCCC; */
	padding-bottom:5px;
}

.clearfloat { clear:both; }

/* Clearfix! */
.clearfix:after,
.listing:after,
.listing .info:after {
	content:".";
	display:block;
	height:0;
	clear:both;
	visibility:hidden;
}

.listing .icon { float: left; margin-right: 10px; }
.listing .icon img { width: auto }
.listing .info { display: table-cell; }

.link {
	cursor:pointer;
}
.small {
	font-size: 90%;
}
.divider {
	border-top:1px solid #cccccc;
}
.hidden {
	display:none;
}
.radius8 {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}
.margin-none {
	margin:0;
}
.margin-top {
	margin-top:10px;
}
.rss-link {
	margin-top:-10px;
	margin-bottom:10px;
}
.rss-link a {
	display:block;
	width:14px;
	height:14px;
	float:right;
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: -250px top;
	text-indent: -1000em;
}
.tags {
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: left -196px;
	padding:1px 0 0 14px;
	font-size: 85%;
}
.tagcloud {
	text-align:justify;
}
.ajax-loader {
	background-color: white;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif);
	background-repeat: no-repeat;
	background-position: center center;
	min-height:33px;
	min-width:33px;
}
.ajax-loader.left {
	background-position: left center;
}

.right {
	float: right;
}

.left {
	float: left;
}

/* ***************************************
	PAGE LAYOUT - MAIN BLOCKS POSITIONING
*************************************** */
.elgg-topbar {
	background:#333333 url(<?php echo elgg_get_site_url(); ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	color:#eeeeee;
	border-bottom:1px solid #000000;
	min-width:998px;
	position:relative;
	width:100%;
	height:24px;
	z-index: 9000;
}
.elgg-page > .elgg-header {
	x-overflow: hidden;
	position: relative;
	width: 100%;
	height:90px;
	background-color: #4690D6;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png);
	background-repeat: repeat-x;
	background-position: bottom left;
}
#elgg-header-contents {
	width:990px;
	position: relative;
	margin:0 auto;
	height:90px;
}
#elgg-search {
	bottom:5px;
	height:23px;
	position:absolute;
	right:0;
}
#elgg-main-nav {
	z-index: 7000;
	position: absolute;
	height:23px;
	bottom:0;
	left:0;
	width:auto;
}
#elgg-content { /* wraps sidebar and page contents */
	width:990px;
	position: relative;
	overflow:hidden;
	word-wrap:break-word;
	margin:0 auto;
	min-height:400px;
}
#elgg-content.sidebar { /* class on #elgg-content div to give a full-height sidebar background */
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif);
	background-repeat:repeat-y;
	background-position: right top;
}
.elgg-layout > .elgg-body {
	float:left;
	position: relative;
	min-height: 360px;
	margin:10px 20px 20px 10px;
}
.elgg-layout > .elgg-aside {
	background-color:#eeeeee;
	border-left:1px solid #DEDEDE;
	float:right;
	width:209px;
	padding:20px 10px;
	position: relative;
	min-height:360px;
}
#elgg-page-contents { /* main page contents */
	float:left;
	position: relative;
	min-height: 360px;
	margin:10px 20px 20px 10px;
}
#elgg-page-contents.one-column { /* class on #elgg-page-contents when no sidebar */
	width:970px;
	margin-right:10px;
}
.elgg-aside { /* elgg sidebar */

}
.elgg-footer {
	position: relative;
	z-index: 999;
}
.elgg-footer > .elgg-inner {
	border-top:1px solid #DEDEDE;
	padding:3px 0 10px 0;
}
#elgg-footer-contents {
	border-top:1px solid #DEDEDE;
	margin:0 auto;
	width:990px;
	padding:3px 0 10px 0;
	text-align: right;
}

.elgg-aside h3 {
	border-bottom:1px solid #CCCCCC;
	margin-bottom:5px;
	margin-top:20px;
	padding-bottom:5px;
}

.elgg-center {
	margin: 0 auto;
}

.elgg-width-classic {
	width: 990px;
}

.elgg-width-content {
	width: 730px;
}

.elgg-module {
}

.elgg-module-heading {
	float: left;
	max-width: 530px;
	margin-right: 10px;
}

.elgg-maincontent-header {
	border-bottom: 1px solid #CCCCCC;
	padding-bottom: 3px;
}

/* ***************************************
	ELGG TOPBAR
*************************************** */
#elgg-topbar-contents {
	float:left;
	height:24px;
	left:0px;
	top:0px;
	position:absolute;
	text-align:left;
	width:100%;
}
.elgg-topbar a {
	margin-right:30px;
	padding-top:2px;
	display:inline;
	float:left;
	text-align: left;
	color:#eeeeee;
}
.elgg-topbar a:hover {
	color:#71cbff;
	text-decoration: none;
}
.elgg-topbar a img.user-mini-avatar {
	border:1px solid #eeeeee;
	margin:1px 0 0 10px;
	display: block;
}
.elgg-topbar a img.site-logo {
	display: block;
	margin-left:5px;
	margin-top: -1px;
}
.elgg-topbar .log-out {
	float:right;
}
.elgg-topbar .log-out a {
	display: inline;
	text-align: right;
	margin-right:10px;
	color:#999999;
}
.elgg-topbar .log-out a:hover {
	color:#71cbff;
}
.elgg-topbar a.myfriends {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left -297px;
	margin-right:30px;
	text-indent: -900em;
	width:36px;
}
.elgg-topbar a.myfriends:hover {
	background-position: left -337px;
}
.elgg-topbar a.settings {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -41px;
	padding-left:20px !important;
	float:right;
	margin-right:30px;
}
.elgg-topbar a.admin {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -41px;
	padding-left:20px !important;
	float:right;
	margin-right:30px;
}
.elgg-topbar a.help {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -133px;
	padding-left:18px !important;
	float:right;
	margin-right:30px;
}


/* ***************************************
	HEADER CONTENTS
*************************************** */
.elgg-header > .elgg-inner {
	position: relative;
	height: 90px;
}

.elgg-header h1 a span.network-title {
	font-size: 2em;
	line-height:1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	display: block;
	text-decoration: none;
	text-shadow:1px 2px 4px #333333;
}
.elgg-header #elgg-search input.search-input {
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	background-color:transparent;
	border:1px solid #71b9f7;
	color:white;
	font-size:12px;
	font-weight:bold;
	margin:0;
	padding:2px 4px 2px 26px;
	width:198px;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position: 2px -220px;
	background-repeat: no-repeat;
}
.elgg-header #elgg-search input.search-input:focus {
	background-color:white;
	color:#0054A7;
	border:1px solid white;
	background-position: 2px -257px;
}
.elgg-header #elgg-search input.search-input:active {
	background-color:white;
	color:#0054A7;
	border:1px solid white;
	background-position: 2px -257px;
}
.elgg-header #elgg-search input.search-submit-button {
	display:none;
}


/* ***************************************
	ELGG SITE NAVIGATION in header
*************************************** */
.navigation,
.navigation ul {
	margin:0;
	padding:0;
	display:inline;
	float:left;
	list-style-type: none;
	z-index: 7000;
	position: relative;
}
.navigation li {
	list-style: none;
	font-weight: bold;
	position: relative;
	display:block;
	height:23px;
	float:left;
	margin:0;
	padding:0;
}
.navigation a {
	color:white;
	margin:0 1px 0 0px;
	text-decoration:none;
	font-weight: bold;
	font-size: 1em;
	padding:3px 13px 0px 13px;
	height:20px;
	cursor: pointer;
	display:block;
}
.navigation li a:hover {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
.navigation li.selected a {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	margin-top:1px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation-more {
	overflow:hidden;
}
li.navigation-more:hover {
	overflow:visible;
}
li.navigation-more:hover a {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation-more a.subnav span {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: -150px -56px;
	padding-left: 12px;
}
li.navigation-more:hover a.subnav span,
li.navigation-more a.subnav:hover span {
	background-position: -150px -76px;
}
li.navigation-more ul {
	z-index: 7000;
	min-width: 150px;
	margin-left:-1px;
	background-color:white;
	border-left:1px solid #999999;
	border-right:1px solid #999999;
	border-bottom:1px solid #999999;
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-left-radius:4px;
	-webkit-border-bottom-right-radius:4px;
	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation-more ul li {
	float:none;
}
.navigation li.navigation-more ul li a {
	background:white;
	color:#555555;
	margin:0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.navigation li.navigation-more ul li:last-child a,
.navigation li.navigation-more ul li:last-child a:hover {
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-left-radius:4px;
	-webkit-border-bottom-right-radius:4px;
}
.navigation li.navigation-more ul li a:hover {
	background:#4690D6;
	color:white;
	margin:0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.navigation li.navigation-more ul li.selected a {
	background:#4690D6;
	color:white;
}

/* ***************************************
	FOOTER CONTENTS
*************************************** */
#elgg-footer-contents,
#elgg-footer-contents a,
#elgg-footer-contents p {
	color:#999999;
}
#elgg-footer-contents a:hover {
	color:#666666;
}
.#elgg-footer-contents p {
	margin:0;
}
.powered-by-elgg-badge {
	float:right;
}


/* ***************************************
	SYSTEM MESSAGES
*************************************** */
.elgg-system-messages {
	position:fixed;
	top:24px;
	right:20px;
	max-width:500px;
	z-index:9600;
}
.elgg-system-messages li {
	color:white;
	font-weight:bold;
	display:block;
	padding:3px 10px;
	margin-top:10px;
	cursor:pointer;
	opacity:0.9;
	-webkit-box-shadow:0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow:0 2px 5px rgba(0, 0, 0, 0.45);
}
.elgg-state-success {
	background-color:black;
}
.elgg-state-error {
	background-color:red;
}

.elgg-system-message p {
	margin:0;
}


/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	font-size: 80%;
	line-height:1.2em;
	color:#bababa;
	position: relative;
	top:-6px;
	left:0;
}
.elgg-breadcrumbs a {
	color:#999999;
	font-weight:bold;
	text-decoration: none;
}
.elgg-breadcrumbs a:hover {
	color: #0054a7;
	text-decoration: underline;
}


/* ***************************************
	SUBMENU
*************************************** */
.submenu {
	margin:0;
	padding:0;
	list-style: none;
}
.submenu ul {
	margin-bottom:0;
	padding-left:0;
	list-style: none;
}
.submenu li.selected a,
.submenu li.selected li.selected a,
.submenu li.selected li.selected li.selected a {
	background: #4690D6;
	color:white;
}
.submenu li a {
	display:block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color:white;
	margin:0 0 3px 0;
	padding:2px 4px 2px 8px;
}
.submenu li a:hover {
	background:#0054A7;
	color:white;
	text-decoration:none;
}
.submenu .child li a {
	margin-left:15px;
	background-color:white;
	color:#4690D6;
}
.submenu .child li a:hover {
	background:#0054A7;
	color:white;
	text-decoration:none;
}



/* ***************************************
	PAGINATION
*************************************** */
.pagination {
	margin:5px 0 5px 0;
	padding:5px 0;
}
.pagination .pagination-number {
	display:block;
	float:left;
	background:#ffffff;
	border:1px solid #4690d6;
	text-align: center;
	color:#4690d6;
	font-size: 12px;
	font-weight: normal;
	margin:0 6px 0 0;
	padding:0px 4px;
	cursor: pointer;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
.pagination .pagination-number:hover {
	background:#4690d6;
	color:white;
	text-decoration: none;
}
.pagination .pagination-more {
	display:block;
	float:left;
	background:#ffffff;
	border:1px solid #ffffff;
	text-align: center;
	color:#4690d6;
	font-size: 12px;
	font-weight: normal;
	margin:0 6px 0 0;
	padding:0px 4px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
.pagination .pagination-previous,
.pagination .pagination-next {
	display:block;
	float:left;
	border:1px solid #cccccc;
	color:#4690d6;
	text-align: center;
	font-size: 12px;
	font-weight: normal;
	margin:0 6px 0 0;
	padding:0px 4px;
	cursor: pointer;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
.pagination .pagination-previous:hover,
.pagination .pagination-next:hover {
	background:#4690d6;
	border:1px solid #4690d6;
	color:white;
	text-decoration: none;
}
.pagination .pagination-currentpage {
	display:block;
	float:left;
	background:#4690d6;
	border:1px solid #4690d6;
	text-align: center;
	color:white;
	font-size: 12px;
	font-weight: bold;
	margin:0 6px 0 0;
	padding:0px 4px;
	cursor: pointer;
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

/* ***************************************
	WIDGETS
*************************************** */
.widget-column {
	float: right;
	min-height: 30px;
}
.widget-1-columns {
	width: 100%;
}
.widget-2-columns {
	width: 50%;
}
.widget-3-columns {
	width: 33%;
}
.widget-4-columns {
	width: 25%;
}
#widget-add-button {
	padding: 0px;
	text-align: right;
	margin-bottom: 15px;
	margin-right: 5px;
}
.widgets-add-panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #dedede;
}
.widgets-add-panel ul {
	padding: 0;
	margin: 0;
}
.widgets-add-panel li {
	float: left;
	margin: 2px 10px;
	list-style: none;
	width: 200px;
	padding: 4px;
	background-color: #cccccc;
}
.widgets-add-panel li a {
	display: block;
}
.widget-available {
	cursor: pointer;
}
.widget-unavailable {
	color: #888888;
}
.widget {
	background-color: #dedede;
	padding: 2px;
	margin: 0 5px 15px;
	position: relative;
}
.widget:hover {
	background-color: #cccccc;
}
.widget-title {
	background-color: #dedede;
	height: 30px;
	line-height: 30px;
	overflow: hidden;
}
.widget-title h3 {
	float: left;
	padding: 0 45px 0 20px;
	color: #333333;
}
.widget-controls a {
	position: absolute;
	top: 5px;
	display: block;
	width: 18px;
	height: 18px;
	border: 1px solid transparent;
}
a.widget-collapse-button {
	left: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 0px -385px;
}
.widget-controls a.widget-collapsed {
	background-position: 0px -365px;
}
a.widget-delete-button {
	right: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -198px 3px;
}
a.widget-edit-button {
	right: 25px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -1px;
}
a.widget-edit-button:hover, a.widget-delete-button:hover {
	border: 1px solid #cccccc;
}
.widget-container {
	background-color: white;
	width: 100%;
	overflow: hidden;
}
.widget-edit {
	display: none;
	width: 96%;
	padding: 2%;
	border-bottom: 2px solid #dedede;
}
.widget-content {
	padding: 10px;
}
.drag-handle {
	cursor: move;
}
.widget-placeholder {
	border: 2px dashed #dedede;
	margin-bottom: 15px;
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
.loginbox .submit-button {
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
#login-dropdown #signin-menu .submit-button {
	margin-right:15px;
}

/* ***************************************
	CONTENT HEADER
**************************************** */
#content-header {
	border-bottom:1px solid #CCCCCC;
}
#content-header:after {
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
.content-header-options .action-button {
	float:right;
	margin:0 0 5px 10px;
}


/* ***************************************
	DEFAULT COMMENTS
**************************************** */
.generic-comment {
	border-bottom:1px dotted #cccccc;
	clear:both;
	display:block;
	margin:0;
	padding:5px 0 7px;
	position:relative;
}
.generic-comment:first-child {
	border-top:1px dotted #cccccc;
}
.generic-comment-icon {
	float:left;
	margin-left:3px;
	margin-top:3px;
}
.generic-comment-icon img {
	width: auto;
}
.generic-comment-details {
	float:left;
	margin-left:7px;
	min-height:28px;
	width:693px;
}
.generic-comment-details p {
	margin:0;
}
.generic-comment-owner {
	line-height:1.2em;
}
.generic-comment-owner a {
	color:#0054A7;
}
.generic-comment-body {
	margin:3px 0 5px 0;
}
.generic-comment-body p {
	margin-bottom: 10px;
}
/* latest comments in sidebar */
.elgg-aside .generic-comment.latest {
	padding:2px 0;
}
.elgg-aside .generic-comment.latest .generic-comment-icon  {
	margin-left:1px;
	margin-top:5px;
}
.elgg-aside .generic-comment.latest .generic-comment-details {
	width:177px;
	line-height:1.1em;
	overflow:hidden;
}
.elgg-aside .generic-comment.latest .entity-title {
	font-size: inherit;
	line-height: inherit;
}


/* ***************************************
	DEFAULT ENTITY LISTINGS
**************************************** */
.entity-listing {
	border-bottom:1px dotted #cccccc;
	padding:4px 0;
	position:relative;
}
.entity-listing:first-child {
	border-top:1px dotted #cccccc;
}
.entity-listing:hover {
	background-color: #eeeeee;
}
.entity-listing .icon {
	margin-left:3px;
	margin-top:3px;
}
.entity-listing .info {
	min-height:28px;
	width:693px;
}
.entity-listing-info p {
	margin:0;
	/* line-height:1.2em; */
}
.entity-title {
	font-weight: bold;
	font-size: 1.1em;
	line-height:1.2em;
	color:#666666;
	padding-bottom:4px;
}
.entity-title a {
	color:#0054A7;
}
.entity-subtext {
	color:#666666;
	font-size: 85%;
	font-style: italic;
	line-height:1.2em;
}
/* entity metadata block */
.entity-metadata {
	float:right;
	margin:0 3px 0 15px;
	color:#aaaaaa;
	font-size: 90%;
}
.entity-metadata span {
	margin-left:14px;
	text-align:right;
}
.entity-metadata .entity-edit a {
	color:#aaaaaa;
}
.entity-metadata .entity-edit a:hover {
	color:#555555;
}
.entity-metadata .delete-button {
	margin-top:3px;
}
/* override hover for lists of site users/members */
.members-list .entity-listing:hover {
	background-color:white;
}


/* ***************************************
	USER SETTINGS
*************************************** */
.user-settings {
	margin-bottom:20px;
}
.user-settings h3 {
	background:#e4e4e4;
	color:#333333;
	padding:5px;
	margin-top:10px;
	margin-bottom:10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.user-settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.user-settings table.styled {
	width:100%;
}
.user-settings table.styled {
	border-top:1px solid #cccccc;
}
.user-settings table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
.user-settings table.styled td.column-one {
	width:200px;
}
.user-settings table.styled tr:hover {
	background: #E4E4E4;
}
.add-user form {
	width:300px;
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
.friendspicker-savebuttons .submit-button,
.friendspicker-savebuttons .cancel-button {
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
	LIKES
*************************************** */
.likes-list-holder {
	position: relative;
	float:right;
}
.likes-list-holder a.user-like {
	cursor:pointer;
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left -101px;
	text-indent: -9000em;
	text-align: left;
	display:block;
	width:20px;
	height:20px;
	margin:0;
	float:left;
}
.likes-list-holder a.user-like:hover {
	background-position: left -131px;
}
.likes-list-holder .likes-list-button.link {
	float:left;
	text-align: left;
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left -131px;
	padding-left:21px;
	height:20px;
}
.likes-list-holder .likes-list-button.link.not-liked {
	background:none;
	padding-left:0;
}
.likes-list-holder .likes-list {
	background-color: white;
	border:1px solid #cccccc;
	width: 345px;
	height: auto;
	position: absolute;
	text-align: left;
	z-index: 9999;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.likes-list-holder .elgg-likes-user {
	border-bottom:1px solid #cccccc;
	padding:3px;
}
.likes-list-holder .elgg-likes-user .entity-listing-info {
	width:305px;
}
.entity-listing .elgg-likes-user .entity-metadata {
	min-width:20px !important;
}
.elgg-likes-user .entity-listing-icon {
	margin:3px 0 4px 2px;
}
.elgg-likes-user .entity-metadata {
	margin-top:3px;
}
p.elgg-likes-owner {
	padding-top:4px;
}

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

/* ***************************************
	MISC
*************************************** */
#dashboard-info {
	float: left;
	width: 625px;
	margin: 0 5px 15px;
	padding: 5px;
	border: 2px solid #dedede;
}


<?php

// in case plugins are still extending the old 'css' view, display it
echo elgg_view('css', $vars);
