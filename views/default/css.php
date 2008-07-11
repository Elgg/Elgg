<?php

	/**
	 * Elgg CSS
	 * The standard CSS file
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['wwwroot'] The site URL
	 */

?>

/* ***************************************
	RESET BASE STYLES
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
/* remember to define focus styles! */
:focus {
	outline: 0;
}
ol, ul {
	list-style: none;
}
/* tables still need 'cellspacing="0"' in the markup */
table {
	border-collapse: separate;
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
.clearfloat { 
	clear:both;
    height:0;
    font-size: 1px;
    line-height: 0px;
}

/* ***************************************
	DEFAULTS
*************************************** */
body {
	text-align:left;
	margin:0 auto;
	padding:0;
	background: #4690d6;
	font: 75%/1.5  "Lucida Grande", Verdana, sans-serif;
	color: #333333;
	background: url(<?php echo $vars['url']; ?>_graphics/page_back_linen.gif) repeat left 1px;
}
a {
	color: #4690d6;
	text-decoration: none;
	-moz-outline-style: none;
	outline: none;
}
a:visited {
	color: #0054a7;
}
a:hover {
	color: #0054a7;
	text-decoration: underline;
}
p {
	margin: 0px 0px 15px 0;
}
img {
	border: none;
}
ul {
	margin: 5px 0px 15px;
	padding-left: 20px;
}
ul li {
	margin: 0px;
}
ol {
	margin: 5px 0px 15px;
	padding-left: 20px;
}
ul li {
	margin: 0px;
}
form {
	margin: 0px;
	padding: 0px;
}
small {
	font-size: 90%;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: normal;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }


/* ***************************************
    PAGE LAYOUT - MAIN STRUCTURE
*************************************** */
#page_container {
	margin:0;
	padding:0;
	background: url(<?php echo $vars['url']; ?>_graphics/page_back_linen_shadow.gif) repeat-y center top;
}
#page_wrapper {
	width:998px;
	margin:0 auto;
	min-height: 300px;
	background: #f4f4f4;
	border-right: 1px solid #333333;
	border-bottom: 1px solid #333333;
}

#layout_header {
	text-align:left;
	/* position:relative; */
	width:100%;
	height:67px;
	border-bottom:1px solid #4690d6;
	background:white;
}
#wrapper_header {
	margin:0;
	padding:10px 20px 20px 20px;
}
#layout_sidebar_left {
	width: 160px;
	float: left;
}
#wrapper_sidebar_left {
	margin:0;
	padding:20px 0 0 20px;
}

#wrapper_sidebar_left p {
	margin:0;
}

#layout_canvas {
	margin:20px 20px 20px 160px;
	min-height: 360px;
}

#layout_sidebar_right {
	width:250px; /* 260-10*/
	min-height: 260px;
	float:right;
	padding:0px 0px 20px 10px;
}

#wrapper_sidebar_right.double_column {
	margin:0;
	background: white;
	padding:14px 10px 14px 10px;
	border-bottom:1px solid #cccccc;
	border-right:1px solid #cccccc;
}

#wrapper_sidebar_right {
	margin:0;
}
#layout_sidebar_right_narrow {
	width:160px;
	min-height: 260px;
	float:right;
	/* padding:0px 0px 20px 10px;*/
	background: white;
	padding:10px;
	border-bottom:1px solid #cccccc;
	border-right:1px solid #cccccc;
}

#layout_maincontent {
    padding:0px 10px 20px 20px;
}
#wrapper_maincontent {
	margin:0;
	
}
/* subclass for layout_maincontent when showing rhs sidebar */
.has_sidebar_right {
    margin:0 260px 0 0 !important;
}
.has_narrow_sidebar_right {
    margin:0 190px 0 0 !important;
}
/* IE6 fix */
* html #layout_maincontent { 
	height:360px;
}

#layout_maincontent.no_sidebar {
    padding:0px 0px 20px 20px !important;
}
#wrapper_maincontent.single_column {
	/* width:785px;*/
	background: white;
	padding:14px 20px 20px 20px;
	border-bottom:1px solid #cccccc;
	border-right:1px solid #cccccc;
}
#wrapper_maincontent.content_area {
	background: white;
	padding:14px 20px 20px 20px;
	border-bottom:1px solid #cccccc;
	border-right:1px solid #cccccc;
}



/* 
#wrapper_sidebar_right .collapsable_box_content {
	border-left: 1px solid #cccccc;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}
#wrapper_sidebar_right .collapsable_box_header {
	border: 1px solid #cccccc;
}
*/
#wrapper_maincontent .collapsable_box_content  {
	margin:0;
	/* padding:0; */
}

#layout_spotlight {
	padding:0;
}
#wrapper_spotlight {
	margin:0;
	padding:0;
	height:auto;
}
#wrapper_spotlight .collapsable_box_content  {
	margin:0;
	padding:20px 20px 10px 20px;
	background: url(<?php echo $vars['url']; ?>_graphics/spotlight_back.gif) repeat-x left bottom;
	min-height:60px;
	border:none;
}
#layout_spotlight .collapsable_box_content p {
	padding:0;
}
#wrapper_spotlight .collapsable_box_header  {
	border-left: none;
	border-right: none;
}
#layout_footer {
	background: url(<?php echo $vars['url']; ?>_graphics/footer_back.gif) repeat-x left top;
	height:80px;
}
#layout_footer table {
   margin:0 0 0 20px;
}
#layout_footer a, #layout_footer p {
   color:white;
   margin:0;
}
#layout_footer .footer_toolbar_links {
	text-align:right;
	padding:15px 0 0 0;
	font-size:1.2em;
}
#layout_footer .footer_legal_links {
	text-align:right;
}


/* ***************************************
  ELGG TOPBAR
*************************************** */
#elgg_topbar {
	background:#333333 url(<?php echo $vars['url']; ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	color:#eeeeee;
	border-bottom:1px solid #000000;
	min-width:998px;
	position:relative;
	width:100%;
	height:24px;
}

#elgg_topbar_container_left {
	float:left;
	height:24px;
	left:0px;
	top:0px;
	position:absolute;
	text-align:left;
	width:60%;
}

#elgg_topbar_container_right {
	float:right;
	height:24px;
	position:absolute;
	right:0px;
	top:0px;
	width:120px;
	text-align:right;
}

#elgg_topbar_container_search {
	float:right;
	height:21px;
	/*width:280px;*/
	position:relative;
	right:120px;
	text-align:right;
	margin:3px 0 0 0;
}

#elgg_topbar_container_left .toolbarlinks,
#elgg_topbar_container_left .toolbarimages {
	float:left;
}
#elgg_topbar_container_left .toolbarlinks {
	margin:3px 0 0 0;
}
#elgg_topbar_container_left a.loggedinuser {
	color:#eeeeee;
	font-weight:bold;
	margin:0 0 0 5px;
}
#elgg_topbar_container_left a.pagelinks {
	color:white;
	margin:0 15px 0 5px;
}
#elgg_topbar_container_left a.pagelinks:hover {
	color:#4690d6;
}

#elgg_topbar_container_left a.usersettings {
	margin:0 0 0 20px;
	color:#4690d6;
}
#elgg_topbar_container_left a.usersettings:hover {
	color:#eeeeee;
}


#elgg_topbar_container_left img {
	margin:2px 0 0 5px;
}
#elgg_topbar_container_left .user_mini_avatar {
	border:1px solid #eeeeee;
	margin:0 0 0 20px;
}
#elgg_topbar_container_right {
	padding:3px 0 0 0;
}
/*
#elgg_topbar_container_right img  {
	float:right;
	margin:0 20px 0 0;
	cursor:pointer;
}
#elgg_topbar_container_right a {
	color:#eeeeee;
	margin:0 5px 0 0;
}
*/
#elgg_topbar_container_right a {
	color:#eeeeee;
	margin:0 5px 0 0;
	background:transparent url(<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logout.gif) no-repeat right -1px;
	padding:0 21px 0 0;
}
#elgg_topbar_container_right a:hover {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logout_over.gif) no-repeat right -1px;
}

#elgg_topbar_panel {
	background:#333333;
	color:#eeeeee;
	height:200px;
	width:100%;
	padding:10px 20px 10px 20px;
	display:none;
	position:relative;
}

#searchform input.search_input {
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px;
	background-color:#FFFFFF;
	border:1px solid #BBBBBB;
	color:#999999;
	font-size:12px;
	font-weight:bold;
	margin:0pt;
	padding:2px;
	width:180px;
	
	height:12px;
}
#searchform input.search_submit_button {
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px;
	color:#ffffff;
	background: #cccccc;
	border:none;
	font-size:12px;
	font-weight:bold;
	margin:0px;
	padding:2px;
	width:auto;
	height:18px;
	cursor:pointer;
}
#searchform input.search_submit_button:hover {
	color:#000000;
}


/* ***************************************
  WIDGET PICKER (PROFILE & DASHBOARD)
*************************************** */
#customise_editpanel {
	display:none;
	margin: -20px 0 20px 20px;
	padding:20px;
	background: #dedede;
}

/* Top area - instructions */
.customise_editpanel_instructions {
	width:510px;
	padding:0 0 10px 0;
}
.customise_editpanel_instructions h2 {
	padding:0 0 10px 0;
}
.customise_editpanel_instructions p {
	margin:0 0 5px 0;
	line-height: 1.4em;
}

/* RHS (widget gallery area) */
#customise_editpanel_rhs {
	float:right;
	width:230px;
	background:white;
}
#customise_editpanel #customise_editpanel_rhs h2 {
	color:#333333;
	font-size: 1.4em;
	margin:0;
	padding:6px;
}
#widget_picker_gallery {
	/* float:right;*/
	border-top:1px solid #cccccc;
	background:white;
	width:210px;
	height:350px;
	padding:10px;
	overflow:scroll;
	overflow-x:hidden;
}

/* main page widget area */
#customise_page_view {
	width:490px;
	padding:10px;
	margin:0 0 10px 0;
	background:white;
}
#customise_page_view h2 {
	border-top:1px solid #cccccc;
	border-right:1px solid #cccccc;
	border-left:1px solid #cccccc;
	margin:0;
	padding:5px;
	color: #0054a7;
	background: #f5f5f5;
	font-size:1.25em;
	line-height: 1.2em;
}
#customise_page_view h2.mainwidgets {
	width:255px;
}

#main_widgets {
	width:255px;
	margin:0 10px 0 0;
	padding:5px 5px 40px 5px;
	min-height: 190px;
	border:1px solid #cccccc;
}
#rightsidebar_widgets {
	width:200px;
	padding:5px 5px 40px 5px;
	min-height: 190px;
	border:1px solid #cccccc;
}

/* IE6 fix */
* html #main_widgets { 
	height: 190px;
}
* html #rightsidebar_widgets { 
	height: 190px;
}

#customise_editpanel table.draggable_widget {
	width:200px;
	background: #cccccc;
	margin: 10px 0 0 0;
	vertical-align:text-top;
	border:1px solid #cccccc;
}

#widget_picker_gallery table.draggable_widget {
	width:200px;
	background: #cccccc;
	margin: 10px 0 0 0;
}

/* take care of long widget names */
#customise_editpanel table.draggable_widget h3 {
	word-wrap:break-word;/* safari, webkit, ie */
	width:140px;
	line-height: 1.1em;
	overflow: hidden;/* ff */
	padding:4px;
}
#widget_picker_gallery table.draggable_widget h3 {
	word-wrap:break-word;
	width:140px;
	line-height: 1.1em;
	overflow: hidden;
	padding:4px;
}
#customise_editpanel img.drag_handle {
	cursor:move;
	padding-top: 4px;
}
#customise_editpanel img.remove_me {
	padding-top: 4px;
}
#customise_editpanel img.more_info {
	padding-top: 4px;
}
#widget_moreinfo {
	position:absolute;
	border:1px solid #333333;
	background:#e4ecf5;
	color:#333333;
	padding:5px;
	display:none;
	width: 200px;
}
/* droppable area hover class  */
.droppable-hover {
	/* outline: 2px dotted red; */
	background:#fdffc3;
}
/* target drop area class */
.placeholder {
	border:2px dashed #AAA;
	width:196px !important;
	margin: 10px 0 10px 0;
}
/* class of widget while dragging */
.ui-sortable-helper {
	background: #4690d6;
	color:white;
	padding: 4px;
	margin: 10px 0 0 0;
}
/* IE6 fix */
* html .placeholder { 
	margin: 0;
}
/* IE7 */
*:first-child+html .placeholder {
	margin: 0;
}
/* IE6 fix */
* html .ui-sortable-helper h3 { 
	padding: 4px;
}
* html .ui-sortable-helper img.drag_handle, * html .ui-sortable-helper img.remove_me, * html .ui-sortable-helper img.more_info {
	padding-top: 4px;
}
/* IE7 */
*:first-child+html .ui-sortable-helper h3 {
	padding: 4px;
}
*:first-child+html .ui-sortable-helper img.drag_handle, *:first-child+html .ui-sortable-helper img.remove_me, *:first-child+html .ui-sortable-helper img.more_info {
	padding-top: 4px;
}


/* ***************************************
  COLLAPSABLE BOXES
*************************************** */
/* open 'customise page' panel button */
a.toggle_customise_edit_panel { 
	float:right;
	color: #4690d6;
	background: #f5f5f5;
	border:1px solid #cccccc;
	padding: 5px 10px 5px 10px;
	margin:0 0 20px 0;
}
a.toggle_customise_edit_panel:hover { 
	color: #ffffff;
	background: #0054a7;
	text-decoration:none;
}

.collapsable_box {
	margin: 0 0 20px 0;
	background: white;
	height:auto;
}
/* IE6 fix */
* html .collapsable_box  { 
	height:10px;
}
.collapsable_box_header {
	color: #4690d6;
	background: #f5f5f5;
	border-top:2px solid #4690d6;
	padding: 5px 10px 5px 10px;
	margin:0;
	
	border-left: 1px solid #cccccc;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}
.collapsable_box_content {
	padding: 10px;
	margin:0;
	height:auto;
	/* border-left:2px solid white;
	border-right:2px solid white;
	border-bottom:2px solid white; */
	
	border-left: 1px solid #cccccc;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}
.collapsable_box_editpanel {
	display: none;
	background: #dedede;
	padding:5px 10px 5px 10px;
	/* font-size: 9px;*/
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
/* used for collapsing a content box */
.display_none {
	display:none;
}
/* used on spotlight box - to cancel default box margin */
.no_space_after {
	margin: 0 0 0 0;
}


/* ***************************************
  System messages
*************************************** */
.messages {
    /* 
    border:1px solid #D3322A;
    background:#F7DAD8;
    */
    border:1px solid #00cc00;
    background:#ccffcc;
    color:#000000;
    padding:3px 10px 3px 10px;
    margin:20px 20px 0px 180px;
    z-index: 99999;
    position:absolute;
    width:776px;
}
.messages_error {
    border:1px solid #D3322A;
    background:#F7DAD8;
    color:#000000;
    padding:3px 10px 3px 10px;
    margin:20px 20px 0px 180px;
    z-index: 99999;
    position:absolute;
    width:776px;
}
/* IE6 fix */
* html .messages { 
	margin:20px 20px 0px 20px;
}
* html .messages_error { 
	margin:20px 20px 0px 20px;
}
/* IE7 */
*:first-child+html .messages {
	margin:20px 20px 0px 20px;
}
*:first-child+html .messages_error {
	margin:20px 20px 0px 20px;
}


/* ***************************************
	ELGG TOOLBAR
*************************************** */
.elggtoolbar .elggtoolbar_header {
	color: #4690d6;
	background: #f5f5f5;
	border-top:2px solid #333333;
	border-bottom:1px solid #999999;
	padding: 5px 10px 5px 10px;
	margin:0;
}

.elggtoolbar {
	border-left:2px solid #333333;
	border-right:2px solid #333333;
	border-bottom:2px solid #333333;
	margin: 0 0 20px 0;
}

.elggtoolbar ul.drawers {
	width: 136px;
	margin: 0;
	padding: 0;
	
}
.elggtoolbar li.drawer ul li {
	line-height: 1.2em;
	margin: 0;
	padding: 3px 0 3px 0;
}
.elggtoolbar ul {
	list-style: none;
	margin: 0;
	padding: 3px 3px 3px 10px;
}
.elggtoolbar li {
	background: #f4f4f4;
}
.elggtoolbar li a {
	text-decoration: none;
	color: #4690d6;
	line-height:0.5em;
	padding-left:5px;
}
.elggtoolbar li a:hover {
	text-decoration: underline;
	color: #0054a7;
}
.elggtoolbar h2.drawer-handle {
	margin: 0;
	padding: 1px 1px 1px 10px;
	background-color: white;
	border-top: 1px solid #999999;
	cursor: pointer;
	font-size: 100%;
	font-weight: normal;
	line-height: 2em;
}
.elggtoolbar h2.drawer-handle:hover {
	background-color: #cccccc;
}
.elggtoolbar h2.drawer-handle.open {
	color:#000000;
	font-weight: bold;
	background: #cccccc;
	border-bottom: none;
}


/* ***************************************
	GENERAL FORM ELEMENTS
*************************************** */
label {
	font-weight: bold;
	color:#333333;
	font-size: 140%;
}
input {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #cccccc;
	color:#666666;
}
textarea {
	font: 120% Arial, Helvetica, sans-serif;
	border: solid 1px #cccccc;
	padding: 5px;
	color:#666666;
}
textarea:focus, input[type="text"]:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333333;
}

.submit_button {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#4690d6;
	border: 1px solid #4690d6;
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px;
	width: auto;
	height: 25px;
	padding: 2px 6px 2px 6px;
	margin:10px 0 10px 0;
	cursor: pointer;
}
.submit_button:hover, input[type="submit"]:hover {
	background: #0054a7;
}

input[type="submit"] {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#4690d6;
	border: 1px solid #4690d6;
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px;
	width: auto;
	height: 25px;
	padding: 2px 6px 2px 6px;
	margin:10px 0 10px 0;
	cursor: pointer;
}

.cancel_button {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #999999;
	background:#dddddd;
	border: 1px solid #999999;
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px;
	width: auto;
	height: 25px;
	padding: 2px 6px 2px 6px;
	margin:10px 0 10px 10px;
	cursor: pointer;
}
.cancel_button:hover {
	background: #cccccc;
}

.input-text,
.input-tags,
.input-url,
.input-textarea {
	width:98%;
}

.input-textarea {
	height: 200px;
}


/* ***************************************
	LOGIN / REGISTER
*************************************** */
#login-box {
	margin-top: 20px;
    text-align:left;
    border:1px solid #ddd;
    width:300px;
    padding:10px;
    background: #ffffff;
}
#login-box-openid {
	margin-top: 20px;
    text-align:left;
    border:1px solid #ddd;
    width:300px;
    padding:10px;
    background: #ffffff;
}
#login-box h2,
#login-box-openid h2,
#register-box h2,
#add-box h2 {
	color: #0054a7;
	font-size:1.5em;
	line-height: 1.5em;
	margin:0 0 20px 0;
	border-bottom: 1px solid #ddd;
}

#register-box {
    text-align:left;
    border:1px solid #ddd;
    width:300px;
    padding:10px;
    background: #ffffff;
}


/* ***************************************
	MAIN CONTENT ELEMENTS
*************************************** */
#wrapper_header h1 {
	margin:10px 0 0 0;
	letter-spacing: -0.03em;
}

/* title within main content area */
.page_title {
	padding:0px 10px 20px 0px;
}


.elggtoolbar .elggtoolbar_header h1,
.collapsable_box_header h1 {
	/*color: #4690d6;*/
	color: #0054a7;
	font-size:1.25em;
	line-height: 1.2em;
}




/* ***************************************
	PROFILE
*************************************** */
#profile_info {
	margin:0 0 20px 0;
	padding:10px;
	border-bottom:1px solid #cccccc;
	border-right:1px solid #cccccc;
	background: white;
}
#profile_menu_wrapper {
	margin:10px 0 10px 0;
}
#profile_menu_wrapper p {
	border-bottom:1px solid #cccccc;
}
#profile_menu_wrapper p:first-child {
	border-top:1px solid #cccccc;
}
#profile_menu_wrapper a {
	display:block;
	padding:0 0 0 3px;
}
#profile_menu_wrapper a:hover {
	color:#ffffff;
	background:#4690d6;
	text-decoration:none;
}
p.user_menu_friends, p.user_menu_profile, 
p.user_menu_removefriend, 
p.user_menu_friends_of {
	margin:0;
}
#profile_info_column_left {
	float:left;
	width:200px;
	padding: 0 20px 0 0;
}
#profile_info_column_right {
	float:left;
	width:280px;
	/* width:100%; */
}
/* IE6 fix */
* html #profile_info_column_right { 
	width:270px;
}
#profile_info_column_right p {
	margin:7px 0 7px 0;
	line-height:1.3em;
	padding:2px 4px 2px 4px;
}
#profile_info_column_right .odd {
	background:#f5f5f5;
}
#profile_info_wide p {
	margin:7px 0 7px 0;
	line-height:1.4em;
}
#profile_info_column_right .profile_info_edit_buttons {
	float:right;
	margin:0;
	padding:0;
}
#profile_info_column_right .profile_info_edit_buttons a {
	margin:0;
	padding:2px 4px 2px 4px;
	border:1px solid #4690d6;
}


/* ***************************************
	RIVER
*************************************** */
.river_item p {
	margin:0;
	padding:0 0 0 20px;
	line-height:1.3em;
}
.river_item {
	border-bottom:1px solid #dddddd;
	padding:2px 0 2px 0;
}
.river_item_time {
	font-size:90%;
	color:#666666;
}
/* IE6 fix */
* html .river_item p { 
	padding:3px 0 3px 20px;
}
/* IE7 */
*:first-child+html .river_item p {
	min-height:17px;
}
.river_user_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
}
.river_annotate {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.river_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/dummy_river_icon.gif) no-repeat left -1px;
}
.river_sharing_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_shares.gif) no-repeat left -1px;
}
.river_status_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_status.gif) no-repeat left -1px;
}
.river_file_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_files.gif) no-repeat left -1px;
}
.river_widget_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_plugin.gif) no-repeat left -1px;
}
.river_forums_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.river_widget_update {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_plugin.gif) no-repeat left -1px;	
}
.river_blog_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;
}
.river_forumtopic_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
}
.river_relationship_friend_create {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_friends.gif) no-repeat left -1px;
}

/* 
STILL TO ADD
messageboard
background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_messageboard.gif) no-repeat left -1px;

feed - river icon:
background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_feed.gif) no-repeat left -1px;

private message - river icon:
background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_privatemessage.gif) no-repeat left -1px;
*/

/* ***************************************
	SEARCH LISTINGS	
*************************************** */

.search_listing {
	display: block;
	background-color: #eee;
	padding: 5px;
	margin-bottom: 10px;
}

.search_listing_icon {
	position: absolute;
}

.search_listing_icon img {
	width: 40px;
}

.search_listing_icon .avatar_menu_button img {
	width: 15px;
}
	
.search_listing_info {
	margin-left: 50px;
	min-height: 40px;
}

.search_listing_info p {
	margin:0 0 3px 0;
	line-height:1.2em;
}

table.search_gallery {
	border-spacing: 5px;
	margin:0 0 20px 0;
}
.search_gallery td {
	padding: 5px;
}

.search_gallery_item {
	border:1px dotted silver;
    background-color: white;
    width: 179px;
    height: 179px;
}
.search_gallery_item:hover {
	border:1px dotted black;
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

/* ***************************************
	SPOTLIGHT
*************************************** */
#wrapper_spotlight #spotlight_table h2 {
	color:#4690d6;
	font-size:1.25em;
	line-height:1.2em;
}
#wrapper_spotlight #spotlight_table li {
	list-style: square;
	line-height: 1.2em;
	margin:5px 20px 5px 0;
}

/* ***************************************
	FRIENDS
*************************************** */
/* friends widget */
#widget_friends_list {
	display:table;
	width:100%;
}
.widget_friends_singlefriend {
	float:left;
	margin:0 5px 5px 0;
}


/* ***************************************
	PLUGIN SETTINGS
*************************************** */
#plugin_details {
	margin:0 0 20px 0;
	padding:10px;
}
#plugin_details.active {
	border:1px solid lime;
}
#plugin_details.not-active {
	border:1px solid red;
}


/* ***************************************
	GENERIC COMMENTS
*************************************** */
.generic_comment_owner {
	font-size: 90%;
	color:#666666;
}

.generic_comment {
	margin-bottom: 10px;
	padding-bottom: 10px;
}

.generic_comment_icon {
	position: absolute;
}

.generic_comment_details {
	margin-left: 60px;
	border-bottom: 1px solid #aaaaaa;
}

.generic_comment_owner {
	color:#666666;
	margin: 0px;
	font-size:90%;
}



/* ***************************************
	STUFF BELOW NEEDS SORTING
*************************************** */

/* not needed? - replaced by #wrapper_maincontent.single_column */
#forums, #forum_topics, #topic_posts {
	/* background:white;*/
}	

/* tag icon */	
.object_tag_string {
	background: url(<?php echo $vars['url']; ?>_graphics/icon_tag.gif) no-repeat left 2px;
	padding:0 0 0 14px;
	margin:0;
}	

/* profile picture upload n crop page */	
#profile_picture_form {
	height:145px;
}	
#current_user_avatar {
	float:left;
	width:160px;
	height:130px;
	border-right:1px solid #cccccc;
	margin:0 20px 0 0;
}	
#profile_picture_croppingtool {
	border-top: 1px solid #cccccc;
	margin:20px 0 0 0;
	padding:10px 0 0 0;
}	
#profile_picture_croppingtool #user_avatar {
	float: left;
	margin-right: 20px;
}	
#profile_picture_croppingtool #applycropping {

}
#profile_picture_croppingtool #user_avatar_preview {
	float: left;
	position: relative;
	overflow: hidden;
	width: 100px;
	height: 100px;
}	
	
/* ***************************************
	page titles and submenu
*************************************** */
/* page titles 
#canvas_header {
	margin:0 0 20px 0;
	padding:0 0 5px 0;
	border-bottom:1px solid #4690d6;
}
*/
#canvas_header_icon {
	float:left;
	margin:0 10px 0 0;
}
	
#canvas_header #canvas_header_content h2 {


}	
#canvas_header_content {
	margin:0 0 10px 0;
	padding:0 0 5px 0;
	border-bottom:1px solid #4690d6;
}
/*
#wrapper_maincontent.single_column {
	margin:0;
	padding:10px 20px;
}
*/
#canvas_header_submenu {
	margin:10px 0 10px 0;
	padding: 0;
	border-bottom: 1px solid #cccccc;
	display:table;
	width:100%;
}

#canvas_header_submenu ul {
	list-style: none;
	padding: 0;
	margin: 0;
}

#canvas_header_submenu li {
	float: left;
	border: 1px solid #ffffff;
	border-bottom-width: 0;
	margin: 0;
}

#canvas_header_submenu a {
	text-decoration: none;
	display: block;
	padding: 0.24em 1em;
	color: #666666;
	text-align: center;
}

#canvas_header_submenu a:hover {
	color: #4690d6;
}

#canvas_header_submenu .selected {
	border-color: #cccccc;
}

#canvas_header_submenu .selected a {
	position: relative;
	top: 1px;
	background: white;
	color: #4690d6;
}
	
	
	
	
	
	
	
	