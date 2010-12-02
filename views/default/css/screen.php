<?php
/**
 * Elgg primary CSS view
 *
 */

// check if there is a theme overriding the old css view and use it, if it exists
$old_css_view = elgg_get_view_location('css');
if ($old_css_view != "{$CONFIG->viewpath}") {
	echo elgg_view('css');
	return true;
}

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
	/* list-style:none outside none; */
	margin: 0 0 10px 0;
	padding-left: 20px;
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
strong {
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
	border-bottom:1px solid #CCCCCC;
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
.margin_none {
	margin:0;
}
.margin_top {
	margin-top:10px;
}
.rss_link {
	margin-top:-10px;
	margin-bottom:10px;
}
.rss_link a {
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
.ajax_loader {
	background-color: white;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif);
	background-repeat: no-repeat;
	background-position: center center;
	min-height:33px;
	min-width:33px;
}
.ajax_loader.left {
	background-position: left center;
}
#elgg_sidebar h3 {
	border-bottom:1px solid #CCCCCC;
	margin-bottom:5px;
	margin-top:20px;
	padding-bottom:5px;
}

/* ***************************************
	PAGE LAYOUT - MAIN BLOCKS POSITIONING
*************************************** */
#elgg_topbar {
	background:#333333 url(<?php echo elgg_get_site_url(); ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	color:#eeeeee;
	border-bottom:1px solid #000000;
	min-width:998px;
	position:relative;
	width:100%;
	height:24px;
	z-index: 9000;
}
#elgg_header {
	x-overflow: hidden;
	position: relative;
	width: 100%;
	height:90px;
	background-color: #4690D6;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png);
	background-repeat: repeat-x;
	background-position: bottom left;
}
#elgg_header_contents {
	width:990px;
	position: relative;
	margin:0 auto;
	height:90px;
}
#elgg_search {
	bottom:5px;
	height:23px;
	position:absolute;
	right:0;
}
#elgg_main_nav {
	z-index: 7000;
	position: absolute;
	height:23px;
	bottom:0;
	left:0;
	width:auto;
}
#elgg_content { /* wraps sidebar and page contents */
	width:990px;
	position: relative;
	overflow:hidden;
	word-wrap:break-word;
	margin:0 auto;
	min-height:400px;
}
#elgg_content.sidebar { /* class on #elgg_content div to give a full-height sidebar background */
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif);
	background-repeat:repeat-y;
	background-position: right top;
}
#elgg_page_contents { /* main page contents */
	float:left;
	width:730px;
	position: relative;
	min-height: 360px;
	margin:10px 20px 20px 10px;
}
#elgg_page_contents.one_column { /* class on #elgg_page_contents when no sidebar */
	width:970px;
	margin-right:10px;
}
#elgg_sidebar { /* elgg sidebar */
	float:right;
	width:210px;
	margin:20px 10px;
	position: relative;
	min-height:360px;
}
#elgg_footer {
	position: relative;
	z-index: 999;
}
#elgg_footer_contents {
	border-top:1px solid #DEDEDE;
	margin:0 auto;
	width:990px;
	padding:3px 0 10px 0;
	text-align: right;
}


/* ***************************************
	ELGG TOPBAR
*************************************** */
#elgg_topbar_contents {
	float:left;
	height:24px;
	left:0px;
	top:0px;
	position:absolute;
	text-align:left;
	width:100%;
}
#elgg_topbar_contents a {
	margin-right:30px;
	padding-top:2px;
	display:inline;
	float:left;
	text-align: left;
	color:#eeeeee;
}
#elgg_topbar_contents a:hover {
	color:#71cbff;
	text-decoration: none;
}
#elgg_topbar_contents a img.user_mini_avatar {
	border:1px solid #eeeeee;
	margin:1px 0 0 10px;
	display: block;
}
#elgg_topbar_contents a img.site_logo {
	display: block;
	margin-left:5px;
	margin-top: -1px;
}
#elgg_topbar_contents .log_out {
	float:right;
}
#elgg_topbar_contents .log_out a {
	display: inline;
	text-align: right;
	margin-right:10px;
	color:#999999;
}
#elgg_topbar_contents .log_out a:hover {
	color:#71cbff;
}
#elgg_topbar_contents a.myfriends {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left -297px;
	margin-right:30px;
	text-indent: -900em;
	width:36px;
}
#elgg_topbar_contents a.myfriends:hover {
	background-position: left -337px;
}
#elgg_topbar_contents a.settings {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -41px;
	padding-left:20px !important;
	float:right;
	margin-right:30px;
}
#elgg_topbar_contents a.admin {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -41px;
	padding-left:20px !important;
	float:right;
	margin-right:30px;
}
#elgg_topbar_contents a.help {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -133px;
	padding-left:18px !important;
	float:right;
	margin-right:30px;
}


/* ***************************************
	HEADER CONTENTS
*************************************** */
#elgg_header_contents h1 a span.network_title {
	font-size: 2em;
	line-height:1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	display: block;
	text-decoration: none;
	text-shadow:1px 2px 4px #333333;
}
#elgg_header_contents #elgg_search input.search_input {
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
#elgg_header_contents #elgg_search input.search_input:focus {
	background-color:white;
	color:#0054A7;
	border:1px solid white;
	background-position: 2px -257px;
}
#elgg_header_contents #elgg_search input.search_input:active {
	background-color:white;
	color:#0054A7;
	border:1px solid white;
	background-position: 2px -257px;
}
#elgg_header_contents #elgg_search input.search_submit_button {
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
li.navigation_more {
	overflow:hidden;
}
li.navigation_more:hover {
	overflow:visible;
}
li.navigation_more:hover a {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation_more a.subnav span {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: -150px -56px;
	padding-left: 12px;
}
li.navigation_more:hover a.subnav span,
li.navigation_more a.subnav:hover span {
	background-position: -150px -76px;
}
li.navigation_more ul {
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
li.navigation_more ul li {
	float:none;
}
.navigation li.navigation_more ul li a {
	background:white;
	color:#555555;
	margin:0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.navigation li.navigation_more ul li:last-child a,
.navigation li.navigation_more ul li:last-child a:hover {
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-left-radius:4px;
	-webkit-border-bottom-right-radius:4px;
}
.navigation li.navigation_more ul li a:hover {
	background:#4690D6;
	color:white;
	margin:0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.navigation li.navigation_more ul li.selected a {
	background:#4690D6;
	color:white;
}

/* ***************************************
	FOOTER CONTENTS
*************************************** */
#elgg_footer_contents,
#elgg_footer_contents a,
#elgg_footer_contents p {
	color:#999999;
}
#elgg_footer_contents a:hover {
	color:#666666;
}
.#elgg_footer_contents p {
	margin:0;
}
.powered_by_elgg_badge {
	float:right;
}


/* ***************************************
	SYSTEM MESSAGES
*************************************** */
#elgg_system_messages {
	position:fixed;
	right:20px;
	max-width: 500px;
	z-index: 9600;
}

.elgg_system_message {
	background-color:black;
	color:white;
	font-weight: bold;
	display:block;
	padding:3px 10px;
	margin-top:10px;
	cursor: pointer;
	opacity:0.9;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	z-index: 9600;
}
.elgg_system_message.error {
	background-color:red;
}
.elgg_system_message p {
	margin:0;
}


/* ***************************************
	BREADCRUMBS
*************************************** */
.breadcrumbs {
	font-size: 80%;
	line-height:1.2em;
	color:#bababa;
	position: relative;
	top:-6px;
	left:0;
}
.breadcrumbs a {
	color:#999999;
	font-weight:bold;
	text-decoration: none;
}
.breadcrumbs a:hover {
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
.pagination .pagination_number {
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
.pagination .pagination_number:hover {
	background:#4690d6;
	color:white;
	text-decoration: none;
}
.pagination .pagination_more {
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
.pagination .pagination_previous,
.pagination .pagination_next {
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
.pagination .pagination_previous:hover,
.pagination .pagination_next:hover {
	background:#4690d6;
	border:1px solid #4690d6;
	color:white;
	text-decoration: none;
}
.pagination .pagination_currentpage {
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
.elgg_horizontal_tabbed_nav {
	margin-bottom:5px;
	padding: 0;
	border-bottom: 2px solid #cccccc;
	display:table;
	width:100%;
}
.elgg_horizontal_tabbed_nav ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
.elgg_horizontal_tabbed_nav li {
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
.elgg_horizontal_tabbed_nav a {
	text-decoration: none;
	display: block;
	padding:3px 10px 0 10px;
	text-align: center;
	height:21px;
	color:#999999;
}
.elgg_horizontal_tabbed_nav a:hover {
	background: #dedede;
	color:#4690D6;
}
.elgg_horizontal_tabbed_nav .selected {
	border-color: #cccccc;
	background: white;
}
.elgg_horizontal_tabbed_nav .selected a {
	position: relative;
	top: 2px;
	background: white;
}

/* ***************************************
	WIDGETS
*************************************** */
.widget_column {
	float: right;
	min-height: 30px;
}
.widget_1_columns {
	width: 100%;
}
.widget_2_columns {
	width: 50%;
}
.widget_3_columns {
	width: 33%;
}
.widget_4_columns {
	width: 25%;
}
#widget_add_button {
	padding: 0px;
	text-align: right;
	margin-bottom: 15px;
	margin-right: 5px;
}
.widgets_add_panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #dedede;
}
.widgets_add_panel ul {
	padding: 0;
	margin: 0;
}
.widgets_add_panel li {
	float: left;
	margin: 2px 10px;
	list-style: none;
	width: 200px;
	padding: 4px;
	background-color: #cccccc;
}
.widgets_add_panel li a {
	display: block;
}
.widget_available {
	cursor: pointer;
}
.widget_unavailable {
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
.widget_title {
	background-color: #dedede;
	height: 30px;
	line-height: 30px;
	overflow: hidden;
}
.widget_title h3 {
	float: left;
	padding: 0 45px 0 20px;
	color: #333333;
}
.widget_controls a {
	position: absolute;
	top: 5px;
	display: block;
	width: 18px;
	height: 18px;
	border: 1px solid transparent;
}
a.widget_collapse_button {
	left: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 0px -385px;
}
.widget_controls a.widget_collapsed {
	background-position: 0px -365px;
}
a.widget_delete_button {
	right: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -198px 3px;
}
a.widget_edit_button {
	right: 25px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -1px;
}
a.widget_edit_button:hover, a.widget_delete_button:hover {
	border: 1px solid #cccccc;
}
.widget_container {
	background-color: white;
	width: 100%;
	overflow: hidden;
}
.widget_edit {
	display: none;
	width: 96%;
	padding: 2%;
	border-bottom: 2px solid #dedede;
}
.widget_content {
	padding: 10px;
}
.drag_handle {
	cursor: move;
}
.widget_placeholder {
	border: 2px dashed #dedede;
	margin-bottom: 15px;
}

/* ***************************************
	LOGIN / REGISTER
*************************************** */
/* login in sidebar */
#elgg_sidebar #login {
	width:auto;
}
#elgg_sidebar #login form {
	width:auto;
}
#elgg_sidebar #login .login_textarea {
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
.loginbox .submit_button {
	margin-right: 15px;
}
#login .persistent_login {
	float:right;
	display:block;
	margin-top:-34px;
	margin-left:80px;
}
#login .persistent_login label {
	font-size:1.0em;
	font-weight: normal;
	cursor: pointer;
}
#login_dropdown {
	float:right;
	position: absolute;
	top:10px;
	right:0;
	z-index: 9599;
}
#login_dropdown #signin_button {
	padding:10px 0px 12px;
	line-height:23px;
	text-align:right;
}
#login_dropdown #signin_button a.signin {
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
#login_dropdown #signin_button a.signin span {
	padding:4px 0 6px 12px;
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position:-150px -51px;
	background-repeat:no-repeat;
}
#login_dropdown #signin_button a.signin:hover {
	background-color:#71B9F7;
	/* color:black; */
}
#login_dropdown #signin_button a.signin:hover span {
	/* background-position:-150px -71px; */
}
#login_dropdown #signin_button a.signin.menu_open {
	background:#cccccc !important;
	color:#666666 !important;
	border:1px solid #cccccc;
	outline:none;
}
#login_dropdown #signin_button a.signin.menu_open span {
	background-position:-150px -71px;
	color:#333333;
}
#login_dropdown #signin_menu {
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
#login_dropdown #signin_menu input[type=text],
#login_dropdown #signin_menu input[type=password] {
	width:203px;
	margin:0 0 5px;
}
#login_dropdown #signin_menu p {
	margin:0;
}
#login_dropdown #signin_menu label {
	font-weight:normal;
	font-size: 100%;
}
#login_dropdown #signin_menu .submit_button {
	margin-right:15px;
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
.content_header_title {
	float:left;
}
.content_header_title {
	margin-right:10px;
	max-width: 530px;
}
.content_header_title h2 {
	border:none;
	margin-bottom:0;
	padding-bottom:5px;
}
.content_header_options {
	float:right;
}
.content_header_options .action_button {
	float:right;
	margin:0 0 5px 10px;
}


/* ***************************************
	DEFAULT COMMENTS
**************************************** */
.generic_comment {
	border-bottom:1px dotted #cccccc;
	clear:both;
	display:block;
	margin:0;
	padding:5px 0 7px;
	position:relative;
}
.generic_comment:first-child {
	border-top:1px dotted #cccccc;
}
.generic_comment_icon {
	float:left;
	margin-left:3px;
	margin-top:3px;
}
.generic_comment_icon img {
	width: auto;
}
.generic_comment_details {
	float:left;
	margin-left:7px;
	min-height:28px;
	width:693px;
}
.generic_comment_details p {
	margin:0;
}
.generic_comment_owner {
	line-height:1.2em;
}
.generic_comment_owner a {
	color:#0054A7;
}
.generic_comment_body {
	margin:3px 0 5px 0;
}
.generic_comment_body p {
	margin-bottom: 10px;
}
/* latest comments in sidebar */
#elgg_sidebar .generic_comment.latest {
	padding:2px 0;
}
#elgg_sidebar .generic_comment.latest .generic_comment_icon  {
	margin-left:1px;
	margin-top:5px;
}
#elgg_sidebar .generic_comment.latest .generic_comment_details {
	width:177px;
	line-height:1.1em;
	overflow:hidden;
}
#elgg_sidebar .generic_comment.latest .entity_title {
	font-size: inherit;
	line-height: inherit;
}


/* ***************************************
	DEFAULT ENTITY LISTINGS
**************************************** */
.entity_listing {
	border-bottom:1px dotted #cccccc;
	padding:4px 0;
	position:relative;
}
.entity_listing:first-child {
	border-top:1px dotted #cccccc;
}
.entity_listing:hover {
	background-color: #eeeeee;
}
.entity_listing .icon {
	margin-left:3px;
	margin-top:3px;
}
.entity_listing .info {
	min-height:28px;
	width:693px;
}
.entity_listing_info p {
	margin:0;
	/* line-height:1.2em; */
}
.entity_title {
	font-weight: bold;
	font-size: 1.1em;
	line-height:1.2em;
	color:#666666;
	padding-bottom:4px;
}
.entity_title a {
	color:#0054A7;
}
.entity_subtext {
	color:#666666;
	font-size: 85%;
	font-style: italic;
	line-height:1.2em;
}
/* entity metadata block */
.entity_metadata {
	float:right;
	margin:0 3px 0 15px;
	color:#aaaaaa;
	font-size: 90%;
}
.entity_metadata span {
	margin-left:14px;
	text-align:right;
}
.entity_metadata .entity_edit a {
	color:#aaaaaa;
}
.entity_metadata .entity_edit a:hover {
	color:#555555;
}
.entity_metadata .delete_button {
	margin-top:3px;
}
/* override hover for lists of site users/members */
.members_list .entity_listing:hover {
	background-color:white;
}


/* ***************************************
	USER SETTINGS
*************************************** */
.user_settings {
	margin-bottom:20px;
}
.user_settings h3 {
	background:#e4e4e4;
	color:#333333;
	padding:5px;
	margin-top:10px;
	margin-bottom:10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.user_settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.user_settings table.styled {
	width:100%;
}
.user_settings table.styled {
	border-top:1px solid #cccccc;
}
.user_settings table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
.user_settings table.styled td.column_one {
	width:200px;
}
.user_settings table.styled tr:hover {
	background: #E4E4E4;
}
.add_user form {
	width:300px;
}


/* ***************************************
	GENERAL FORM ELEMENTS
*************************************** */
/* default elgg core input field classes */
.input_text,
.input_tags,
.input_url,
.input_textarea {
	width:98%;
}
.input_access {
	margin:5px 0 0 0;
}
.input_password {
	width:200px;
}
.input_textarea {
	height: 200px;
	width:718px;
}
input[type="checkbox"],
input.input_radio {
	margin:0 3px 0 0;
	padding:0;
	border:none;
}
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
textarea {
	font: 120% Arial, Helvetica, sans-serif;
	border: solid 1px #cccccc;
	padding: 5px;
	color:#666666;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
textarea:focus,
input[type="text"]:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333333;
}
.input_textarea.monospace {
	font-family:Monaco,"Courier New",Courier,monospace;
	font-size:13px;
}
a.longtext_control {
	float:right;
	margin-left:14px;
}
.submit_button {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #4690d6;
	background-color:#4690d6;
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
.submit_button:hover {
	color: white;
	border-color: #0054a7;
	text-decoration:none;
	background-color:#0054a7;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.submit_button.disabled {
	background-color:#999999;
	border-color:#999999;
	color:#dedede;
}
.submit_button.disabled:hover {
	background-color:#999999;
	border-color:#999999;
	color:#dedede;
}
input[type="password"]:focus {
	border: solid 1px #4690d6;
	background-color: #e4ecf5;
	color:#333333;
}
input[type="submit"] {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #4690d6;
	background-color:#4690d6;
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
input[type="submit"]:hover {
	border-color: #0054a7;
	text-decoration:none;
	background-color:#0054a7;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.cancel_button {
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
.cancel_button:hover {
	background-color: #999999;
	background-position:  left 10px;
	text-decoration:none;
	color:white;
}
input.action_button,
a.action_button {
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
input.action_button:hover,
a.action_button:hover,
input.action_button:focus,
a.action_button:focus {
	background-position:0 -15px;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	color:#111111;
	text-decoration: none;
	background-color:#cccccc;
	border:1px solid #999999;
}
.action_button:active {
	background-image:none;
}
.action_button.disabled {
	color:#999999;
	padding:2px 7px 2px 7px;
}
.action_button.disabled:hover {
	background-position:0 -15px;
	color:#111111;
	border:1px solid #999999;
}
.action_button.disabled:active {
	background-image:none;
}
.action_button.download {
	padding: 5px 9px 5px 6px;
}
.action_button.download:hover {

}
.action_button.download img {
	margin-right:6px;
	position:relative;
	top:5px;
}
.action_button.small {
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	width: auto;
	height:8px;
	padding: 4px;
	font-size: 0.9em;
	line-height: 0.6em;
}
.action_button.small:hover {
	background-color: #4690d6;
	background-image: none;
	border-color: #4690d6;
	color:white;
	text-shadow:0 -1px 0 black;
}
/* small round delete button */
.delete_button {
	width:14px;
	height:14px;
	margin:0;
	float:right;
}
.delete_button a {
	display:block;
	cursor: pointer;
	width:14px;
	height:14px;
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat -200px top;
	text-indent: -9000px;
	text-align: left;
}
.delete_button a:hover {
	background-position: -200px -16px;
}


/* ***************************************
	FRIENDS PICKER
*************************************** */
.friends_picker_container h3 {
	font-size:4em !important;
	text-align: left;
	margin:10px 0 20px 0 !important;
	color:#999999 !important;
	background: none !important;
	padding:0 !important;
}
.friends_picker .friends_picker_container .panel ul {
	text-align: left;
	margin: 0;
	padding:0;
}
.friends_picker_wrapper {
	margin: 0;
	padding:0;
	position: relative;
	width: 100%;
}
.friends_picker {
	position: relative;
	overflow: hidden;
	margin: 0;
	padding:0;
	width: 730px;
	height: auto;
}
.friendspicker_savebuttons {
	background: white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	margin:0 10px 10px 10px;
}
.friends_picker .friends_picker_container { /* long container used to house end-to-end panels. Width is calculated in JS  */
	position: relative;
	left: 0;
	top: 0;
	width: 100%;
	list-style-type: none;
}
.friends_picker .friends_picker_container .panel {
	float:left;
	height: 100%;
	position: relative;
	width: 730px;
	margin: 0;
	padding:0;
}
.friends_picker .friends_picker_container .panel .wrapper {
	margin: 0;
	padding:4px 10px 10px 10px;
	min-height: 230px;
}
.friends_picker_navigation {
	margin: 0 0 10px 0;
	padding:0 0 10px 0;
	border-bottom:1px solid #cccccc;
}
.friends_picker_navigation ul {
	list-style: none;
	padding-left: 0;
}
.friends_picker_navigation ul li {
	float: left;
	margin:0;
	background:white;
}
.friends_picker_navigation a {
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
.friends_picker_navigation li a:hover {
	background: #333333;
	color:white !important;
}
.friends_picker_navigation li a.current {
	background: #4690D6;
	color:white !important;
}
.friends_picker_navigation_l, .friends_picker_navigation_r {
	position: absolute;
	top: 46px;
	text-indent: -9000em;
}
.friends_picker_navigation_l a, .friends_picker_navigation_r a {
	display: block;
	height: 43px;
	width: 43px;
}
.friends_picker_navigation_l {
	right: 48px;
	z-index:1;
}
.friends_picker_navigation_r {
	right: 0;
	z-index:1;
}
.friends_picker_navigation_l {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat left top;
}
.friends_picker_navigation_r {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat -60px top;
}
.friends_picker_navigation_l:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat left -44px;
}
.friends_picker_navigation_r:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat -60px -44px;
}
.friendspicker_savebuttons .submit_button,
.friendspicker_savebuttons .cancel_button {
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
.likes_list_holder {
	position: relative;
	float:right;
}
.likes_list_holder a.user_like {
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
.likes_list_holder a.user_like:hover {
	background-position: left -131px;
}
.likes_list_holder .likes_list_button.link {
	float:left;
	text-align: left;
	background: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left -131px;
	padding-left:21px;
	height:20px;
}
.likes_list_holder .likes_list_button.link.not_liked {
	background:none;
	padding-left:0;
}
.likes_list_holder .likes_list {
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
.likes_list_holder .elgg_likes_user {
	border-bottom:1px solid #cccccc;
	padding:3px;
}
.likes_list_holder .elgg_likes_user .entity_listing_info {
	width:305px;
}
.entity_listing .elgg_likes_user .entity_metadata {
	min-width:20px !important;
}
.elgg_likes_user .entity_listing_icon {
	margin:3px 0 4px 2px;
}
.elgg_likes_user .entity_metadata {
	margin-top:3px;
}
p.elgg_likes_owner {
	padding-top:4px;
}

.user_picker .user_picker_entry {
	clear:both;
	height:25px;
	padding:5px;
	margin-top:5px;
	border-bottom:1px solid #cccccc;
}
.user_picker_entry .delete_button {
	margin-right:10px;
}

/* ***************************************
	MISC
*************************************** */
#dashboard_info {
	float: left;
	width: 625px;
	margin: 0 5px 15px;
	padding: 5px;
	border: 2px solid #dedede;
}


<?php

// in case plugins are still extending the old 'css' view, display it
echo elgg_view('css');
