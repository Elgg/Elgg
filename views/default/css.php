/**
 * ELGG DEFAULT CSS
 * @uses $vars['wwwroot'] The site URL
*/

/* Table of Contents:

	RESET CSS 					reduce browser inconsistencies in line height, margins, font size...
	CSS BASICS					<body> <p> <a> <h1>
	PAGE LAYOUT					main page content blocks: header, sidebar, footer...
	GENERIC SELECTORS			reusable generic classes
	ELGG TOPBAR					elgg topbar
	TOOLS MENU					drop-down tools menu in topbar
	HEADER CONTENTS
	ELGG SITE NAVIGATION		Primary site navigation in header
	FOOTER CONTENTS
	SYSTEM MESSAGES				system messages overlay
	BREADCRUMBS
	SUBMENU						current page/tool submenu in sidebar
	PAGINATION					re-usable default page navigation
	ELGG TABBED NAVIGATION 		re-usable tabbed navigation
	LOGIN / REGISTER			login box, register, and lost password page styles
	CONTENT HEADER
	ENTITY LISTINGS				elgg's default entity listings
	USER SETTINGS & ADMIN AREA	styles for user settings and default admin area
	GENERAL FORM ELEMENTS		default styles for all elgg input/form elements
	
	

*/
/* Colors:	

	#4690D6 - elgg light blue
	#0054A7 - elgg dark blue
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
	list-style:none outside none;
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
h2 { font-size: 1.5em; }
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
.clearfloat:after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}
.link {
	cursor:pointer;
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
	background-image:url(<?php echo $vars['url']; ?>_graphics/icon_rss.png);
	background-repeat: no-repeat;
	background-position: left top;
	text-indent: -1000em;
}
.tags {
	background-image:url(<?php echo $vars['url']; ?>_graphics/icon_tag.png);
	background-repeat: no-repeat;
	background-position: left 2px;
	padding:1px 0 0 16px;
	font-size: 90%;
}
.ajax_loader {
	background-color: white;
	background-image: url(<?php echo $vars['url']; ?>_graphics/ajax_loader_bw.gif);
	background-repeat: no-repeat;
	background-position: center 30px;
	/*
	width:auto;
	height:100px;
	margin:0 10px 0 10px;
*/
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
	background:#333333 url(<?php echo $vars['url']; ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	color:#eeeeee;
	border-bottom:1px solid #000000;
	min-width:998px;
	position:relative;
	width:100%;
	height:24px;
	z-index: 9000;
}
#elgg_header {
	overflow: hidden;
	position: relative;
	width: 100%;
	height:90px;
	background-color: #4690D6;
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
	margin:0 auto;
	min-height:400px;
}
#elgg_content.sidebar { /* class on #elgg_content div to give a full-height sidebar background */
	background-image:url(<?php echo $vars['url']; ?>_graphics/sidebar_background.gif);
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
	margin-right:20px;
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
#elgg_topbar_contents a.settings {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/topbar_icons.png) no-repeat left -41px;
	padding-left:20px !important;
	float:right;
	margin-right:30px;
}
#elgg_topbar_contents a.admin {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/topbar_icons.png) no-repeat left -41px;
	padding-left:20px !important;
	float:right;
	margin-right:30px;
}
#elgg_topbar_contents a.help {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/topbar_icons.png) no-repeat 0 -133px;
	padding-left:18px !important;
	float:right;
	margin-right:30px;
}




/* @todo PH check + move into shared access? */
#elgg_topbar_contents a.shared_access {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/icons_shared_access.png) no-repeat 0 -15px;
	padding-left:20px !important;
	float:right;
}




/* ***************************************
	TOOLS MENU
*************************************** */
#elgg_topbar_contents ul.tools_menu,
#elgg_topbar_contents ul.tools_menu ul {
	margin:0;
	padding:0;
	display:inline;
	float:left;
	list-style-type: none;
	z-index: 9000;
	position: relative;
}
#elgg_topbar_contents ul.tools_menu {
	margin:0 20px 0 5px;
}
#elgg_topbar_contents li.menu a.tools {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/topbar_icons.png) no-repeat 3px 1px;
	padding-left:24px !important;
}
#elgg_topbar_contents ul.tools_menu li { 
	display: block;
	list-style: none;
	margin: 0;
	padding: 0;
	float: left;
	position: relative;
}
#elgg_topbar_contents ul.tools_menu a {
	display:block;
}
#elgg_topbar_contents ul.tools_menu ul {
	display: none;
	position: absolute;
	left: 0;
	margin: 0;
	padding: 0;
}
#elgg_topbar_contents ul.tools_menu ul li {
	float: none;
}   
/* elgg toolbar drop-down menu style */
#elgg_topbar_contents ul.tools_menu ul {
	width: 150px;
	top: 24px;
	overflow: hidden;
	border-top:1px solid black;
}
#elgg_topbar_contents ul.tools_menu *:hover {
	background-color: none;
}
#elgg_topbar_contents ul.tools_menu a {
	padding:2px 7px 4px 7px;
	text-decoration:none;
	color:white;
	overflow-y: hidden;
}
#elgg_topbar_contents ul.tools_menu li.hover a {
	background-color: #333333;
	text-decoration: none;
}
#elgg_topbar_contents ul.tools_menu ul li a {
	background-color: #333333; /* menu off-state color */
	font-weight: bold;
	padding-left:6px;
	padding-top:4px;
	padding-bottom:0;
	height:22px;
	width:150px;
	display: block;
	border-bottom: 1px solid white;
}
#elgg_topbar_contents ul.tools_menu ul a.hover {
	background-color: #0054a7; /* menu hover-state color */
}
#elgg_topbar_contents ul.tools_menu ul a {
	opacity: 0.9;
}



/* ***************************************
  HEADER CONTENTS
*************************************** */
#elgg_header_contents h1 a {
	font-size: 2em;
	line-height:1.7em;
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
	background-image: url(<?php echo $vars['url']; ?>_graphics/search.png);
	background-position: 3px 0;
	background-repeat: no-repeat;
}
#elgg_header_contents #elgg_search input.search_input:focus {
	background-color:white;
	color:#0054A7;
	border:1px solid white;
	background-position: 3px -37px;
}
#elgg_header_contents #elgg_search input.search_input:active {
	background-color:white;
	color:#0054A7;
	border:1px solid white;
	background-position: 3px -37px;
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
	display:block;
}
.navigation a {
	color:white;
	margin:0 1px 0 0px;
	text-decoration:none;
	font-weight: bold;
	font-size: 1em;
	padding:3px 13px 0px 13px;
	height:20px;
}
.navigation li a.hover,
.navigation a:hover {
	background:white;
	color:#43575e;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
}
.navigation li.selected a {
	background:white;
	color:#43575e;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	margin-top:2px;
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
#elgg_system_message {
    background-color:black;
    color:white;
    font-weight: bold;
	display:block;
	padding:3px 10px;
    z-index: 9600;
	position:fixed;
	right:20px;
	margin-top:10px;
	width:auto;
	cursor: pointer;
	opacity:0.9;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45); /* safari v3+ */
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45); /* FF v3.5+ */
}
#elgg_system_message.error {
    background-color:red;
}
#elgg_system_message p {
	margin:0;
}


/* ***************************************
	BREADCRUMBS
*************************************** */
#breadcrumbs {
	font-size: 80%;
	line-height:1.2em;
	color:#bababa;
	position: relative;
	top:-8px;
	left:0;
}
#breadcrumbs a {
	color:#999999;
	font-weight:bold;
	text-decoration: none;
}
#breadcrumbs a:hover {
	color: #0054a7;
	text-decoration: underline;
}


/* ***************************************
	SUBMENU
*************************************** */
.submenu ul {
	margin:0;
	padding:0;
	list-style: none;
}
.submenu ul li.selected a {
	background: #4690D6;
	color:white;
}
.submenu ul li a {
	display:block;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background-color:white;
	margin:0 0 3px 0;
	padding:2px 4px 2px 8px;
}
.submenu ul li a:hover {
	background:#0054A7;
	color:white;
	text-decoration:none;
}



/* ***************************************
	PAGINATION
*************************************** */
.pagination {
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background:white;
	margin:5px 10px 5px 10px;
	padding:5px;
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
	border:1px solid #4690d6;
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
#login label,
.register label {
	font-size: 1.2em;
	color:gray;
}
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
#login .persistent_login {
	float:left;
	display:block;
	margin-top:-34px;
	margin-left:100px;
}
#login .persistent_login label {
	font-size:1.0em;
	font-weight: normal;
	cursor: pointer;
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
.content_header_title,
.content_header_filter {
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
.content_header_filter {
	background: url(<?php echo $vars['url']; ?>_graphics/content_header_filter.gif) no-repeat 0 0;
}
.content_header_filter select.styled {
	height:25px;
	cursor: pointer;
}
span.select {
	position: absolute;
	width: 342px;
	height: 25px;
	padding: 3px 24px 0 8px;
	color: #fff;
	font-weight: bold;
	font-size: 1.35em;
	background: url(<?php echo $vars['url']; ?>_graphics/content_header_filter.gif) no-repeat;
	overflow: hidden;
	text-align: left;
}
select.styled .select_option {
	font-size:1.3em;
	margin:0;
	padding:3px;
}



/* ***************************************
	DEFAULT ENTITY LISTINGS
**************************************** */
.entity_listing {
	border-bottom:1px dotted #cccccc;
	clear:both;
	display:block;
	margin:0;
	padding:5px 0 7px;
	position:relative;
}
.entity_listing:first-child {
	border-top:1px dotted #cccccc;
}
.entity_listing:hover {
	background-color: #eeeeee;
}
.entity_listing_icon {
	float:left;
	margin-left:3px;
	margin-top:3px;
}
.entity_listing_icon img {
	width: auto;
}
.entity_listing_info {
	float:left;
	margin-left:7px;
	min-height:28px;
	width:693px;
}
.entity_listing_info p {
	margin:0;
	line-height:1.2em;
}
p.entity_title {
	font-weight: bold;
	font-size: 1.1em;
}
p.entity_title a {
	color:#0054A7;
}
p.entity_subtext {
	margin:0;
	padding:0;
	color:#666666;
	font-size: 90%;
}
/* entity metadata block */
.entity_metadata {
	float:right;
	margin:0 3px 0 15px;
	color:#aaaaaa;
	font-size: 90%;
}
.entity_metadata table {
	width:280px;
}
.entity_metadata table td {
	height:30px;
	vertical-align:middle;
}
.entity_metadata table td.entity_edit {
	text-align:right;
}
.entity_metadata table td.entity_edit a {
	color:#aaaaaa;
	margin:0 0 0 10px;
}
.entity_metadata table td.entity_edit a:hover {
	color:#0054a7;
}
.entity_metadata table td.entity_delete {
	padding-top:2px;
	width:26px;
}


/* ***************************************
	USER SETTINGS & ADMIN AREA 
	@todo - pull admin css into stand-alone css
*************************************** */
/* GENERAL STYLES */
.user_settings,
.admin_settings {
	margin-bottom:20px;
}
.user_settings p,
.admin_settings p {
	margin-bottom:6px;
}
.user_settings h3,
.admin_settings h3 {
	background:#e4e4e4;
	color:#333333;
	padding:5px;
	margin-top:10px;
	margin-bottom:10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.user_settings label,
.admin_settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.user_settings table.styled,
.admin_settings table.styled {
	width:100%;
}
.user_settings table.styled,
.admin_settings table.styled {
	border-top:1px solid #cccccc;
}
.user_settings table.styled td,
.admin_settings table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
.user_settings table.styled td.column_one,
.admin_settings table.styled td.column_one {
	width:200px;
}
.user_settings table.styled tr:hover,
.admin_settings table.styled tr:hover {
	background: #E4E4E4;
}
.add_user form {
	width:300px;
}
/* PLUGINS MANAGEMENT (ADMIN) */
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
.plugin_details a.pluginsettings_link {
	cursor:pointer;
	font-size:80%;
}
.plugin_details a.manifest_details {
	cursor:pointer;
	font-size:80%;
}
.active {
	border:1px solid #999999;
	background:white;
}
.not_active {
	border:1px solid #999999;
	background:#dedede;
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
}
input[type="checkbox"] {
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
.submit_button {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #4690d6;
	background-color:#4690d6;
	background-image: url(<?php echo $vars['url']; ?>_graphics/button_graduation.png);
	background-repeat: repeat-x;
	background-position: left 10px;
	-webkit-border-radius: 7px; 
	-moz-border-radius: 7px;
	width: auto;
	padding: 2px 4px;
	margin:0 10px 10px 0;
	cursor: pointer;
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40); /* safari v3+ */
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40); /* FF v3.5+ */
}
.submit_button:hover {
	color: white;
	border-color: #0054a7;
	text-decoration:none;
	background-color:#0054a7;
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
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
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	-webkit-border-radius: 7px; 
	-moz-border-radius: 7px;
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 0;
	cursor: pointer;
	-moz-outline-style: none;
	outline: none;	
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40); /* safari v3+ */
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40); /* FF v3.5+ */	
}
input[type="submit"]:hover {
	border-color: #0054a7;
	text-decoration:none;
	background-color:#0054a7;
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.cancel_button {
	font-size: 14px;
	font-weight: bold;
	text-decoration:none;
	color: #333333;
	background-color:#dddddd;
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	border: 1px solid #999999;
	-webkit-border-radius: 7px; 
	-moz-border-radius: 7px;
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
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	background-position: 0 0;
	border:1px solid #999999;
	color:#333333;
	/*display:block;*/
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
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_background.gif);
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
	color: white;
	background-color:#4690d6;
	background-image:  url(<?php echo $vars['url']; ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	/*display:inline-block;*/
	width:auto;
	height:auto;
	padding: 3px 6px 3px 6px;
	margin:0 0 10px 0;
}
.action_button.download:hover {
	background: #0054a7;
	border-color: #0054a7;
	color:white;
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
	text-shadow:0 -1px 0 #999999;
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
	background: url("<?php echo $vars['url']; ?>_graphics/icon_delete.png") no-repeat 0 0;
	text-indent: -9000px;
}
.delete_button a:hover {
	background-position: 0 -16px;
}
/*  //////////////////////////////////////////////////////////////////////////////////////////	>>>End Verified */










/* @todo OLD ADMIN AREA - will be replaced/updated with multi-admin */
.admin_settings.users_online .profile_status {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	background:#bbdaf7;
	line-height:1.2em;
	padding:2px 4px;
}
.admin_settings.users_online .profile_status span {
	font-size:90%;
	color:#666666;
}
.admin_settings.users_online  p.owner_timestamp {
	padding-left:3px;
}
.admin_users_online .search_listing {
	margin:0 0 5px 0;
	padding:5px;
	border:1px solid #cccccc;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
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
 .enableallplugins,
 .disableallplugins {
	float:right;
}
 .enableallplugins {
	margin-left:10px;
}
 .enableallplugins,
.not_active .admin_plugin_enable_disable a {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#4690d6;
	border: 1px solid #4690d6;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	width: auto;
	padding: 4px;
	cursor: pointer;
}
 .enableallplugins:hover,
.not_active .admin_plugin_enable_disable a:hover {
	background: #0054a7;
	border: 1px solid #0054a7;
	text-decoration: none;
}
 .disableallplugins,
.active .admin_plugin_enable_disable a {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#999999;
	border: 1px solid #999999;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	width: auto;
	padding: 4px;
	cursor: pointer;
}
 .disableallplugins:hover,
.active .admin_plugin_enable_disable a:hover {
	background: #333333;
	border: 1px solid #333333;
	text-decoration: none;
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


/*


#elgg_topbar_panel {
	background:#333333;
	color:#eeeeee;
	height:200px;
	width:100%;
	padding:10px 20px 10px 20px;
	display:none;
	position:relative;
}
#elggFooterContents .footer_legal_links {
	text-align:right;
}
#elggFooterContents .footersidebar {
	text-align:right;
}
#elggFooterContents table td.footersidebar a img {
	float:right;
	padding:5px 0;
}
 
.generic_comment_owner {
	font-size: 90%;
	color:#666666;
}
.generic_comment {
	background:white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:10px;
	margin:0 10px 10px 10px;
}
.generic_comment_icon {
	float:left;
}
.generic_comment_details {
	margin-left: 60px;
}
.generic_comment_details p {
	margin: 0 0 5px 0;
}
.generic_comment_owner {
	color:#666666;
	margin: 0px;
	font-size:90%;
	border-top: 1px solid #aaaaaa;
}


Object page headers blogs/pages/files etc


table.search_gallery {
	border-spacing: 10px;
	margin:0 0 0 0;
}
.search_gallery td {
	padding: 5px;
}
.search_gallery_item {
	background: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	width:170px;
}
.search_gallery_item:hover {
	background: black;
	color:white;
}
.search_gallery_item .search_listing {
	background: none;
	text-align: center;
}
.search_gallery_item .search_listing_header {
	text-align: center;
}
.search_gallery_item .search_listing_icon {
	position: relative;
	text-align: center;
}
.search_gallery_item .search_listing_info {
	margin: 5px;
}
.search_gallery_item .search_listing_info p {
	margin: 5px;
	margin-bottom: 10px;
}
.search_gallery_item .search_listing {
	background: none;
	text-align: center;
}
.search_gallery_item .search_listing_icon {
	position: absolute;
	margin-bottom: 20px;
}
.search_gallery_item .search_listing_info {
	margin: 5px;
}
.search_gallery_item .search_listing_info p {
	margin: 5px;
	margin-bottom: 10px;
}

#NewListStyle  {
	padding:0;
	margin:0;
}
#NewListStyle  .search_listing {
   	-webkit-border-radius: 0; 
	-moz-border-radius: 0;
	background:none;
	border-bottom:1px dotted #CCCCCC;
	margin:0;
	padding:5px 0 7px 0;
	display: inline-block;
	position: relative;
	clear:both;
}
#NewListStyle  .ListTop {
	margin:0 0;
	clear:both;
	width:auto;
}
#NewListStyle  .search_listing:hover {
	background-color: #eeeeee;
}
#NewListStyle  .search_listing:last-child {
	border-bottom:1px dotted #CCCCCC;
}

#NewListStyle .search_listing_icon {
	float:left;
	margin-left:3px;
	margin-top:3px;
}
#NewListStyle .search_listing .search_listing_info {
	float:left;
	margin-left:7px;
	min-height:28px;
	width:693px;
}
#NewListStyle .search_listing .search_listing_info .ItemMetaData {
	float:right;
	margin-left:15px;
	margin-top:0;
	margin-right: 3px;
	color:#AAAAAA;
	font-size: 90%;
}

.ItemMetaData.special {
	float:right;
	margin-left:15px;
	margin-top:0;
	margin-right: 3px;
	color:#AAAAAA;
	font-size: 90%;
}

used on groups members listings where there is only a delete button in table 
#NewListStyle .search_listing .search_listing_info .ItemMetaData table.singlecell {
	width:auto; 
}
#NewListStyle .search_listing_info p.tags {
	font-size:90%;
	margin:0;
	padding-top:1px;
}
*/


/* ***************************************
  COLLAPSABLE BOXES - for widgets?
*************************************** */
/*
.collapsable_box {
	margin: 0 0 20px 0;
	height:auto;

}
.collapsable_box_header {
	color: #4690d6;
	padding: 5px 10px 5px 10px;
	margin:0;
	border-left: 1px solid white;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	-webkit-border-top-right-radius:8px;
	-webkit-border-top-left-radius:8px;
	background:#dedede;
}
.collapsable_box_header h1 {
	color: #0054a7;
	font-size:1.25em;
	line-height: 1.2em;
}
.collapsable_box_content {
	padding: 10px 0 10px 0;
	margin:0;
	height:auto;
	background:#dedede;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-webkit-border-bottom-right-radius:8px;
	-webkit-border-bottom-left-radius:8px;
	border-left: 1px solid white;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}
.collapsable_box_content  {
	margin-bottom:5px;
}
.collapsable_box_editpanel {
	display: none;
	background: #a8a8a8;
	padding:10px 10px 5px 10px;
	border-left: 1px solid white;
	border-bottom: 1px solid white;
}
.collapsable_box_editpanel p {
	margin:0 0 5px 0;
}
.collapsable_box_header a.toggle_box_contents {
	color: #4690d6;
	cursor:pointer;
	font-family: Arial, Helvetica, sans-serif;
	font-size:20px;
	font-weight: bold;
	text-decoration:none;
	float:right;
	margin: 0;
	margin-top: -7px;
}
.collapsable_box_header a.toggle_box_edit_panel {
	color: #4690d6;
	cursor:pointer;
	font-size:9px;
	text-transform: uppercase;
	text-decoration:none;
	font-weight: normal;
	float:right;
	margin: 3px 10px 0 0;
}
.collapsable_box_editpanel label {
	font-weight: normal;
	font-size: 100%;
}
*/
